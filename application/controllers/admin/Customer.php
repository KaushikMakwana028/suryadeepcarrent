<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends Admin_Controller
{
    public function index()
    {
        $data['page_title'] = 'Customers';
        $data['current_user'] = $this->current_user;

        $vehicle_id     = (int) $this->input->get('vehicle_id');
        $filter_month   = (int) $this->input->get('month');
        $filter_year    = (int) $this->input->get('year');
        $payment_filter = $this->input->get('payment_filter', true);
        $payment_filter = in_array($payment_filter, array('received', 'pending'), true) ? $payment_filter : '';

        $data['filter_vehicle_id'] = $vehicle_id;
        $data['filter_month']      = $filter_month;
        $data['filter_year']       = $filter_year;
        $data['filter_payment']    = $payment_filter;

        $per_page = 10;
        $page     = max(1, (int) $this->input->get('page'));
        $offset   = ($page - 1) * $per_page;

        $matching_ids = null;
        $is_filtered = ($vehicle_id > 0 && $filter_month > 0 && $filter_year > 0);

        $filter_vehicle = null;
        if ($vehicle_id > 0) {
            $filter_vehicle = $this->General_model->get_row('vehicles', array('id' => $vehicle_id));
        }
        $data['filter_vehicle'] = $filter_vehicle;

        if ($is_filtered) {
            $matching_ids = $this->General_model->get_customer_ids_for_vehicle_month($vehicle_id, $filter_year, $filter_month, $payment_filter);

            $coll_summary = $this->General_model->get_vehicle_collection_summary($vehicle_id, $filter_year, $filter_month);
            $data['stats_total_customers']  = is_array($matching_ids) ? count($matching_ids) : 0;
            $data['stats_active_customers'] = (int) $coll_summary['total_bookings'];
            $data['stats_pending_docs']     = null;
            if ($payment_filter === 'pending') {
                $data['stats_total_spent'] = (float) $coll_summary['pending_amount'];
            } elseif ($payment_filter === 'received') {
                $data['stats_total_spent'] = (float) $coll_summary['received_amount'];
            } else {
                $data['stats_total_spent'] = (float) $coll_summary['total_amount'];
            }
        } else {
            // Small, cheap counts for the dashboard stat cards — these use
            // COUNT()/SUM() at the DB level, never loading full row sets.
            $data['stats_total_customers']  = $this->General_model->count_customers_overview('', null);
            $data['stats_active_customers'] = (int) $this->db
                ->select('COUNT(DISTINCT customer_id) as c', false)
                ->where('customer_id IS NOT NULL')
                ->get('bookings')->row('c');
            $data['stats_pending_docs']      = null;
            $data['stats_total_spent']       = (float) $this->db->select_sum('amount')->get('bookings')->row()->amount;
        }

        $total = $this->General_model->count_customers_overview('', $matching_ids);
        $customers = $this->General_model->get_customers_overview_paginated($per_page, $offset, '', $matching_ids);

        $month_names = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $m_label = isset($month_names[$filter_month]) ? $month_names[$filter_month] : '';
        $v_name = !empty($filter_vehicle['name']) ? $filter_vehicle['name'] : 'Vehicle #' . $vehicle_id;

        foreach ($customers as &$customer) {
            if ($is_filtered) {
                $filtered_bookings = $this->General_model->get_customer_filtered_bookings((int) $customer['id'], $vehicle_id, $filter_year, $filter_month, $payment_filter);
                $all_bookings = $this->General_model->get_bookings(array('bookings.customer_id' => (int) $customer['id']));

                $f_total_amount = 0.0;
                $f_paid_amount = 0.0;
                $f_pending_amount = 0.0;
                $f_last_pickup = '';

                foreach ($filtered_bookings as $fb) {
                    $f_total_amount += (float) $fb['amount'];
                    $f_paid_amount += (float) $fb['paid_amount'];
                    $f_pending_amount += (float) $fb['balance_amount'];
                    if ($f_last_pickup === '' || $fb['pickup_date'] > $f_last_pickup) {
                        $f_last_pickup = $fb['pickup_date'];
                    }
                }

                $customer['total_bookings']  = count($filtered_bookings);
                $customer['total_spent']     = $f_total_amount;
                $customer['total_amount']    = $f_total_amount;
                $customer['paid_amount']     = $f_paid_amount;
                $customer['pending_amount']  = $f_pending_amount;
                if (!empty($f_last_pickup)) {
                    $customer['last_booking'] = $f_last_pickup;
                }

                $customer['detail'] = array(
                    'documents'    => $this->General_model->get_customer_documents_matrix((int) $customer['id']),
                    'bookings'     => $filtered_bookings,
                    'all_bookings' => $all_bookings,
                    'is_filtered'  => true,
                    'filter_label' => $v_name . ' · ' . $m_label . ' ' . $filter_year . ($payment_filter ? ' (' . ucfirst($payment_filter) . ')' : '')
                );
            } else {
                $customer['detail'] = $this->General_model->get_customer_activity_detail((int) $customer['id']);
                $payment_summary = $this->General_model->get_customer_payment_summary((int) $customer['id']);
                $customer['total_amount']   = $payment_summary['total_amount'];
                $customer['paid_amount']    = $payment_summary['paid_amount'];
                $customer['pending_amount'] = $payment_summary['pending_amount'];
            }
        }
        unset($customer);

        $data['customers']    = $customers;
        $data['current_page'] = $page;
        $data['per_page']     = $per_page;
        $data['total_rows']   = $total;
        $data['total_pages']  = max(1, (int) ceil($total / $per_page));

        $this->render_view('admin/customers_list', $data);
    }

    /**
     * Returns customer_id list who have a booking for the given vehicle
     * whose pickup_date falls in the given month/year, optionally filtered
     * by payment status for that booking ('received' = something paid,
     * 'pending' = balance still due).
     */
    // private function get_customer_ids_for_vehicle_month($vehicle_id, $year, $month, $payment_filter = '')
    // {
    //     $bookings = $this->db
    //         ->select('id as booking_id, customer_id, amount')
    //         ->from('bookings')
    //         ->where('vehicle_id', (int) $vehicle_id)
    //         ->where('YEAR(pickup_date)', (int) $year)
    //         ->where('MONTH(pickup_date)', (int) $month)
    //         ->get()
    //         ->result_array();

    //     if (empty($bookings)) {
    //         return array();
    //     }

    //     $customer_ids = array();

    //     foreach ($bookings as $booking) {
    //         $paid_row = $this->db
    //             ->select_sum('amount')
    //             ->where('booking_id', (int) $booking['booking_id'])
    //             ->get('payments')
    //             ->row();

    //         $paid = $paid_row && $paid_row->amount !== null ? (float) $paid_row->amount : 0.0;
    //         $balance = max(0, (float) $booking['amount'] - $paid);

    //         if ($payment_filter === 'received' && $paid <= 0) {
    //             continue;
    //         }

    //         if ($payment_filter === 'pending' && $balance <= 0.01) {
    //             continue;
    //         }

    //         $customer_ids[] = (int) $booking['customer_id'];
    //     }

    //     return array_values(array_unique($customer_ids));
    // }

    public function ajax_filter()
    {
        $this->output->set_content_type('application/json');

        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
            return;
        }

        $vehicle_id     = (int) $this->input->get_post('vehicle_id');
        $filter_month   = (int) $this->input->get_post('month');
        $filter_year    = (int) $this->input->get_post('year');
        $payment_filter = $this->input->get_post('payment_filter', true);
        $payment_filter = in_array($payment_filter, array('received', 'pending'), true) ? $payment_filter : '';
        $search         = trim($this->input->get_post('search', true));
        $per_page       = 10;
        $page           = max(1, (int) $this->input->get_post('page'));
        $offset         = ($page - 1) * $per_page;

        $matching_ids = null;
        if ($vehicle_id > 0 && $filter_month > 0 && $filter_year > 0) {
            $matching_ids = $this->General_model->get_customer_ids_for_vehicle_month($vehicle_id, $filter_year, $filter_month, $payment_filter);
        }

        $total     = $this->General_model->count_customers_overview($search, $matching_ids);
        $customers = $this->General_model->get_customers_overview_paginated($per_page, $offset, $search, $matching_ids);

        $rows = array();

        $month_names = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $m_label = isset($month_names[$filter_month]) ? $month_names[$filter_month] : '';
        $is_filtered = ($vehicle_id > 0 && $filter_month > 0 && $filter_year > 0);

        foreach ($customers as $customer) {
            if ($is_filtered) {
                $filtered_bookings = $this->General_model->get_customer_filtered_bookings((int) $customer['id'], $vehicle_id, $filter_year, $filter_month, $payment_filter);
                $all_bookings = $this->General_model->get_bookings(array('bookings.customer_id' => (int) $customer['id']));

                $f_total_amount = 0.0;
                $f_paid_amount = 0.0;
                $f_pending_amount = 0.0;
                $f_last_pickup = '';

                foreach ($filtered_bookings as $fb) {
                    $f_total_amount += (float) $fb['amount'];
                    $f_paid_amount += (float) $fb['paid_amount'];
                    $f_pending_amount += (float) $fb['balance_amount'];
                    if ($f_last_pickup === '' || $fb['pickup_date'] > $f_last_pickup) {
                        $f_last_pickup = $fb['pickup_date'];
                    }
                }

                $detail = array(
                    'documents'    => $this->General_model->get_customer_documents_matrix((int) $customer['id']),
                    'bookings'     => $filtered_bookings,
                    'all_bookings' => $all_bookings,
                    'is_filtered'  => true,
                    'filter_label' => "Vehicle #{$vehicle_id} · {$m_label} {$filter_year}" . ($payment_filter ? ' (' . ucfirst($payment_filter) . ')' : '')
                );

                $rows[] = array(
                    'id'              => (int) $customer['id'],
                    'full_name'       => $customer['full_name'],
                    'email'           => $customer['email'],
                    'phone'           => $customer['phone'],
                    'total_bookings'  => count($filtered_bookings),
                    'doc_status'      => $customer['doc_status'],
                    'last_booking'    => !empty($f_last_pickup) ? date('d M Y', strtotime($f_last_pickup)) : (!empty($customer['last_booking']) ? date('d M Y', strtotime($customer['last_booking'])) : 'No bookings'),
                    'total_amount'    => (float) $f_total_amount,
                    'paid_amount'     => (float) $f_paid_amount,
                    'pending_amount'  => (float) $f_pending_amount,
                    'total_spent'     => (float) $f_total_amount,
                    'detail'          => $detail,
                );
            } else {
                $payment_summary = $this->General_model->get_customer_payment_summary((int) $customer['id']);
                $detail          = $this->General_model->get_customer_activity_detail((int) $customer['id']);

                $rows[] = array(
                    'id'              => (int) $customer['id'],
                    'full_name'       => $customer['full_name'],
                    'email'           => $customer['email'],
                    'phone'           => $customer['phone'],
                    'total_bookings'  => (int) $customer['total_bookings'],
                    'doc_status'      => $customer['doc_status'],
                    'last_booking'    => !empty($customer['last_booking']) ? date('d M Y', strtotime($customer['last_booking'])) : 'No bookings',
                    'total_amount'    => (float) $payment_summary['total_amount'],
                    'paid_amount'     => (float) $payment_summary['paid_amount'],
                    'pending_amount'  => (float) $payment_summary['pending_amount'],
                    'total_spent'     => (float) $customer['total_spent'],
                    'detail'          => $detail,
                );
            }
        }

        echo json_encode(array(
            'success'     => true,
            'customers'   => $rows,
            'page'        => $page,
            'per_page'    => $per_page,
            'total_rows'  => $total,
            'total_pages' => max(1, (int) ceil($total / $per_page)),
        ));
    }

    public function delete($customer_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $customer_id = (int) $customer_id;

        if ($customer_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid customer.');
            redirect('admin/customers');
            return;
        }

        // Check if customer has bookings
        $booking_count = $this->General_model->count_rows('bookings', array('customer_id' => $customer_id));

        if ($booking_count > 0) {
            $this->session->set_flashdata('error', 'This customer has ' . $booking_count . ' booking(s) and cannot be deleted. Delete their bookings first.');
            redirect('admin/customers');
            return;
        }

        // Delete documents files from disk first
        $documents = $this->db->where('customer_id', $customer_id)->get('documents')->result_array();
        foreach ($documents as $doc) {
            if (!empty($doc['file_path'])) {
                $full_path = FCPATH . $doc['file_path'];
                if (file_exists($full_path)) {
                    @unlink($full_path);
                }
            }
        }

        // Delete document records
        $this->db->where('customer_id', $customer_id)->delete('documents');

        // Delete the user/customer account — use your actual table name
        $this->db->where('id', $customer_id)->delete('users');

        $this->session->set_flashdata('success', 'Customer deleted successfully.');
        redirect('admin/customers');
    }
}
