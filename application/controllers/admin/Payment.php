<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends Admin_Controller
{
    public function index()
    {
        $data['page_title'] = 'Payment Requests';
        $data['current_user'] = $this->current_user;
        $data['payment_requests'] = $this->General_model->get_admin_payment_requests();
        $data['payment_request_counts'] = $this->General_model->get_payment_request_counts();
        $data['payment_settings'] = $this->General_model->get_payment_settings();
        $this->render_view('admin/payment_requests', $data);
    }

    public function settings()
    {
        $data['page_title'] = 'Payment Details';
        $data['current_user'] = $this->current_user;
        $data['payment_settings'] = $this->General_model->get_payment_settings();
        $this->render_view('admin/payment_settings', $data);
    }

    public function save_settings()
    {
        $existing = $this->General_model->get_payment_settings();
        $qr_path = !empty($existing['qr_image']) ? $existing['qr_image'] : '';

        if (!empty($_FILES['qr_image']['name'])) {
            $upload_dir = FCPATH . 'uploads/payment-settings/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }

            $config = array(
                'upload_path' => $upload_dir,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size' => 4096,
                'file_ext_tolower' => true,
                'remove_spaces' => true,
                'file_name' => 'payment_qr_' . time(),
            );

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('qr_image')) {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
                redirect('admin/payments/settings');
            }

            $upload_data = $this->upload->data();
            $qr_path = 'uploads/payment-settings/' . $upload_data['file_name'];
        }

        $payload = array(
            'account_holder' => trim($this->input->post('account_holder', true)),
            'bank_name' => trim($this->input->post('bank_name', true)),
            'account_number' => trim($this->input->post('account_number', true)),
            'ifsc_code' => trim($this->input->post('ifsc_code', true)),
            'branch_name' => trim($this->input->post('branch_name', true)),
            'upi_id' => trim($this->input->post('upi_id', true)),
            'qr_image' => $qr_path,
            'payment_instructions' => trim($this->input->post('payment_instructions', true)),
        );

        if ($this->General_model->save_payment_settings($payload) === false) {
            $this->session->set_flashdata('error', 'Payment settings table is missing. Please run the database query first.');
            redirect('admin/payments/settings');
        }

        $this->session->set_flashdata('success', 'Payment details updated successfully.');
        redirect('admin/payments/settings');
    }

    public function approve($request_id)
    {
        $request = $this->General_model->get_payment_request_by_id((int) $request_id);
        if (empty($request)) {
            show_404();
        }

        if ($request['status'] !== 'approved') {
            $payload = array(
                'booking_id' => (int) $request['booking_id'],
                'payment_type' => !empty($request['payment_type']) ? $request['payment_type'] : 'advance',
                'amount' => (float) $request['amount'],
                'payment_mode' => !empty($request['payment_mode']) ? $request['payment_mode'] : 'UPI',
                'reference_no' => !empty($request['reference_no']) ? $request['reference_no'] : '',
                'notes' => 'Customer receipt approved by admin.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($this->db->table_exists('payments') && $this->db->field_exists('payment_request_id', 'payments')) {
                $existing_payment = $this->General_model->get_row('payments', array('payment_request_id' => (int) $request['id']));
                if (empty($existing_payment)) {
                    $payload['payment_request_id'] = (int) $request['id'];
                    $this->General_model->insert('payments', $payload);
                    $this->General_model->sync_booking_status_from_payment((int) $request['booking_id']);
                }
            } elseif ($this->db->table_exists('payments')) {
                $this->General_model->insert('payments', $payload);
                $this->General_model->sync_booking_status_from_payment((int) $request['booking_id']);
            }
        }

        $this->General_model->update_payment_request((int) $request['id'], array(
            'status' => 'approved',
            'admin_notes' => trim($this->input->post('admin_notes', true)),
            'reviewed_by' => (int) $this->current_user['id'],
            'approved_at' => date('Y-m-d H:i:s'),
        ));

        $this->session->set_flashdata('success', 'Payment request approved successfully.');
        redirect('admin/payments/requests');
    }

    public function reject($request_id)
    {
        $request = $this->General_model->get_payment_request_by_id((int) $request_id);
        if (empty($request)) {
            show_404();
        }

        $this->General_model->update_payment_request((int) $request['id'], array(
            'status' => 'rejected',
            'admin_notes' => trim($this->input->post('admin_notes', true)),
            'reviewed_by' => (int) $this->current_user['id'],
            'approved_at' => null,
        ));

        $this->session->set_flashdata('success', 'Payment request rejected.');
        redirect('admin/payments/requests');
    }

    public function store()
    {
        $payload = array(
            'booking_id' => (int) $this->input->post('booking_id'),
            'payment_type' => trim($this->input->post('payment_type', true)),
            'amount' => (float) $this->input->post('amount'),
            'other_expenses' => (float) $this->input->post('other_expenses') ?: 0.00,
            'payment_mode' => trim($this->input->post('payment_mode', true)),
            'reference_no' => trim($this->input->post('reference_no', true)),
            'notes' => trim($this->input->post('notes', true)),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->General_model->insert('payments', $payload);
        $this->General_model->sync_booking_status_from_payment((int) $payload['booking_id']);
        $this->session->set_flashdata('success', 'Payment recorded successfully.');

        $redirect_to = trim($this->input->post('redirect_to', true));
        if ($redirect_to === 'admin/bookings') {
            redirect('admin/bookings');
        }

        redirect('admin/payments/requests');
    }

    public function create_razorpay_order()
    {
        $this->output->enable_profiler(false);
        $this->output->set_content_type('application/json');

        $booking_id = (int) $this->input->post('booking_id');
        $amount = (float) $this->input->post('amount');

        if ($booking_id <= 0) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Invalid booking ID.')));
            return;
        }

        $booking = $this->General_model->get_row('bookings', array('id' => $booking_id));
        if (empty($booking)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Booking not found.')));
            return;
        }

        // Get customer details
        $customer = $this->General_model->get_row('users', array('id' => (int) $booking['customer_id']));
        $customer_name = !empty($customer['full_name']) ? $customer['full_name'] : 'Customer';
        $customer_phone = !empty($customer['phone']) ? $customer['phone'] : '';
        $customer_email = !empty($customer['email']) ? $customer['email'] : '';

        // If amount was not specified or <= 0, compute remaining balance
        if ($amount <= 0) {
            $paid_amount = (float) $this->db->select_sum('amount')->where('booking_id', $booking_id)->get('payments')->row()->amount;
            $amount = max(0, (float) $booking['amount'] - $paid_amount);
        }

        if ($amount <= 0) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'No remaining balance to collect.')));
            return;
        }

        $this->config->load('razorpay', true);
        $cfg = $this->config->item('razorpay');

        $payable_amount = $amount;
        if (!empty($cfg['razorpay_test_mode']) && !empty($cfg['razorpay_test_amount'])) {
            $payable_amount = (float) $cfg['razorpay_test_amount'];
        }

        $amount_in_paise = round($payable_amount * 100);
        $currency = !empty($cfg['razorpay_currency']) ? $cfg['razorpay_currency'] : 'INR';
        $key_id = $cfg['razorpay_key_id'];
        $key_secret = $cfg['razorpay_key_secret'];

        $order_id = '';
        $error_message = '';

        if (function_exists('curl_init') && !empty($key_id) && !empty($key_secret)) {
            $booking_code = $this->General_model->format_booking_code($booking_id, $booking['created_at']);
            $payload = array(
                'amount' => $amount_in_paise,
                'currency' => $currency,
                'receipt' => 'bk_bal_' . $booking_id . '_' . time(),
                'notes' => array(
                    'booking_id' => (string) $booking_id,
                    'booking_code' => (string) $booking_code,
                    'customer_name' => (string) $customer_name,
                    'customer_phone' => (string) $customer_phone,
                    'type' => 'admin_collect',
                ),
            );

            $ch = curl_init('https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 && !empty($response)) {
                $order_data = json_decode($response, true);
                if (!empty($order_data['id'])) {
                    $order_id = $order_data['id'];
                    $this->General_model->update('bookings', array('id' => $booking_id), array(
                        'razorpay_order_id' => $order_id,
                    ));
                }
            } else {
                $err = json_decode($response, true);
                $error_message = isset($err['error']['description']) ? $err['error']['description'] : 'Failed to create Razorpay order.';
            }
        }

        if (empty($order_id)) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => !empty($error_message) ? $error_message : 'Unable to connect to Razorpay server. Please try again or select Cash.',
            )));
            return;
        }

        $booking_code = $this->General_model->format_booking_code($booking_id, $booking['created_at']);

        $this->output->set_output(json_encode(array(
            'success' => true,
            'order_id' => $order_id,
            'amount' => $amount_in_paise,
            'display_amount' => number_format($payable_amount, 2),
            'currency' => $currency,
            'key_id' => $key_id,
            'company_name' => !empty($cfg['razorpay_company_name']) ? $cfg['razorpay_company_name'] : 'SURYA DEEP CAR RENT',
            'logo_url' => !empty($cfg['razorpay_logo_url']) ? $cfg['razorpay_logo_url'] : '',
            'theme_color' => !empty($cfg['razorpay_theme_color']) ? $cfg['razorpay_theme_color'] : '#2563eb',
            'customer_name' => $customer_name,
            'customer_phone' => $customer_phone,
            'customer_email' => $customer_email,
            'description' => 'Booking ' . $booking_code . ' - Balance Payment (₹' . number_format($payable_amount, 2) . ')',
        )));
    }

    public function verify_razorpay()
    {
        $this->output->enable_profiler(false);
        $this->output->set_content_type('application/json');

        $booking_id = (int) $this->input->post('booking_id');
        $amount = (float) $this->input->post('amount');
        $other_expenses = (float) $this->input->post('other_expenses') ?: 0.00;
        $notes = trim($this->input->post('notes', true));

        $razorpay_payment_id = trim((string) $this->input->post('razorpay_payment_id', true));
        $razorpay_order_id   = trim((string) $this->input->post('razorpay_order_id', true));
        $razorpay_signature  = trim((string) $this->input->post('razorpay_signature', true));

        if ($booking_id <= 0 || empty($razorpay_payment_id)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Invalid payment parameters.')));
            return;
        }

        $booking = $this->General_model->get_row('bookings', array('id' => $booking_id));
        if (empty($booking)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Booking not found.')));
            return;
        }

        $this->config->load('razorpay', true);
        $cfg = $this->config->item('razorpay');
        $key_secret = $cfg['razorpay_key_secret'];

        // Verify HMAC SHA256 signature
        $signature_verified = false;
        if (!empty($razorpay_order_id) && !empty($razorpay_signature)) {
            $expected_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $key_secret);
            if (hash_equals($expected_signature, $razorpay_signature)) {
                $signature_verified = true;
            }
        }

        if (!$signature_verified) {
            $key_id = $cfg['razorpay_key_id'];
            if (function_exists('curl_init') && !empty($key_id) && !empty($key_secret)) {
                $ch = curl_init('https://api.razorpay.com/v1/payments/' . urlencode($razorpay_payment_id));
                curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                $resp = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($http_code === 200 && !empty($resp)) {
                    $payment_obj = json_decode($resp, true);
                    if (isset($payment_obj['status']) && in_array($payment_obj['status'], array('captured', 'authorized'), true)) {
                        $signature_verified = true;
                    }
                }
            }
        }

        if (!$signature_verified) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Payment verification failed. Please contact support.')));
            return;
        }

        $payable_amount = $amount;
        if (!empty($cfg['razorpay_test_mode']) && !empty($cfg['razorpay_test_amount'])) {
            $payable_amount = (float) $cfg['razorpay_test_amount'];
        }

        // Insert payment into payments table
        $existing_payment = $this->General_model->get_row('payments', array('reference_no' => $razorpay_payment_id));
        if (empty($existing_payment)) {
            $payment_notes = (!empty($notes) ? ($notes . ' | ') : '') . 'Online payment via Razorpay. Order ID: ' . $razorpay_order_id;
            $this->General_model->insert('payments', array(
                'booking_id' => $booking_id,
                'payment_type' => 'payment',
                'amount' => $payable_amount,
                'other_expenses' => $other_expenses,
                'payment_mode' => 'Razorpay',
                'reference_no' => $razorpay_payment_id,
                'notes' => $payment_notes,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));
        }

        // Check if fully paid
        $total_paid = (float) $this->db->select_sum('amount')->where('booking_id', $booking_id)->get('payments')->row()->amount;
        $total_booking_fare = (float) $booking['amount'];
        $is_fully_paid = ($total_booking_fare > 0 && $total_paid >= $total_booking_fare);

        $booking_update = array(
            'payment_mode' => 'Razorpay',
            'payment_status' => $is_fully_paid ? 'paid' : 'part_paid',
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_signature' => $razorpay_signature,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if ($booking['status'] === 'pending') {
            $booking_update['status'] = 'confirmed';
        }
        $this->General_model->update('bookings', array('id' => $booking_id), $booking_update);
        $this->General_model->sync_booking_status_from_payment($booking_id);

        $booking_code = $this->General_model->format_booking_code($booking_id, $booking['created_at']);
        $this->session->set_flashdata('success', 'Payment of ₹' . number_format($payable_amount, 2) . ' received via Razorpay for booking ' . $booking_code . '. Status is now ' . ($is_fully_paid ? 'PAID' : 'Part Paid') . '!');

        $this->output->set_output(json_encode(array(
            'success' => true,
            'redirect' => base_url('admin/bookings'),
        )));
    }
}
