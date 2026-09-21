<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function index()
    {
        redirect('dashboard');
    }

    public function pay($booking_id)
    {
        $customer_id = (int) $this->input->get('customer_id');
        if ($customer_id <= 0) {
            $customer_id = $this->get_active_customer_id();
        }
        $booking_id = (int) $booking_id;

        if ($customer_id <= 0 || !$this->customer_can_access_booking($booking_id, $customer_id)) {
            $this->session->set_flashdata('error', 'Please start your booking again.');
            redirect('dashboard');
        }

        $document_gate = $this->General_model->get_required_documents_status($customer_id);
        if ((int) $document_gate['missing_count'] > 0) {
            $this->session->set_flashdata('error', 'Upload both required documents before payment.');
            redirect('documents?booking_id=' . $booking_id . '&customer_id=' . $customer_id);
        }

        $this->set_public_booking_session($customer_id, $booking_id);
        $booking = $this->General_model->get_booking_for_flow($booking_id, $customer_id);

        if (empty($booking)) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('dashboard');
        }

        $this->config->load('razorpay', true);
        $razorpay_cfg = $this->config->item('razorpay');

        $total_amount = (float) $booking['amount'];
        $advance_due  = isset($booking['advance_due']) && (float) $booking['advance_due'] > 0
            ? (float) $booking['advance_due']
            : ((isset($booking['advance_amount']) && (float) $booking['advance_amount'] > 0) ? (float) $booking['advance_amount'] : 1000.00);

        if ($total_amount > 0 && $advance_due > $total_amount) {
            $advance_due = $total_amount;
        }

        $payable_amount = $advance_due;
        if (!empty($razorpay_cfg['razorpay_test_mode']) && !empty($razorpay_cfg['razorpay_test_amount'])) {
            $payable_amount = (float) $razorpay_cfg['razorpay_test_amount'];
        }

        $data['page_title'] = 'Payment';
        $data['page_subtitle'] = 'Select Cash to pay on pickup, or Pay Online securely via Razorpay.';
        $data['current_user'] = $this->current_user;
        $data['is_customer_logged_in'] = $this->is_logged_in() && $this->current_role() === 0;
        $data['current_step'] = 3;
        $data['booking'] = $booking;
        $data['total_amount'] = $total_amount;
        $data['advance_due'] = $advance_due;
        $data['payable_amount'] = $payable_amount;
        $data['razorpay_cfg'] = $razorpay_cfg;
        $data['payment_settings'] = $this->General_model->get_payment_settings();
        $data['existing_request'] = $this->General_model->get_payment_request_for_booking($booking_id, $customer_id);
        $data['cancel_url'] = base_url('bookings/cancel/' . $booking_id . '?customer_id=' . $customer_id);
        $data['back_url'] = base_url('documents?booking_id=' . $booking_id . '&customer_id=' . $customer_id);

        $this->render_customer_view('payment_pay', $data);
    }

    public function confirm_cash()
    {
        $customer_id = (int) $this->input->post('customer_id');
        if ($customer_id <= 0) {
            $customer_id = $this->get_active_customer_id();
        }
        $booking_id = (int) $this->input->post('booking_id');
        $booking = $this->General_model->get_booking_for_flow($booking_id, $customer_id);

        if (empty($booking)) {
            $this->session->set_flashdata('error', 'Please start your booking again.');
            redirect('dashboard');
        }

        $this->General_model->update('bookings', array('id' => $booking_id), array(
            'status' => 'pending',
            'payment_mode' => 'Cash',
            'payment_status' => 'pending',
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        $this->clear_public_booking_session();

        $this->session->set_flashdata('swal', array(
            'icon' => 'success',
            'title' => 'Booking Request Submitted',
            'text' => 'Your booking request has been submitted with Cash payment. Admin will review and contact you shortly.',
            'identity' => array(
                'Booking ID' => isset($booking['booking_code']) ? $booking['booking_code'] : ('#' . $booking_id),
                'Customer' => isset($booking['customer_name']) ? $booking['customer_name'] : '',
                'Mobile' => isset($booking['customer_phone']) ? $booking['customer_phone'] : '',
                'Payment Mode' => 'Cash (Pay on pickup)',
            ),
        ));

        redirect('dashboard');
    }

    public function create_razorpay_order()
    {
        $this->output->enable_profiler(false);
        $this->output->set_content_type('application/json');

        $customer_id = (int) $this->input->post('customer_id');
        if ($customer_id <= 0) {
            $customer_id = $this->get_active_customer_id();
        }
        $booking_id = (int) $this->input->post('booking_id');

        if ($customer_id <= 0 || !$this->customer_can_access_booking($booking_id, $customer_id)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Invalid booking access.')));
            return;
        }

        $booking = $this->General_model->get_booking_for_flow($booking_id, $customer_id);
        if (empty($booking)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Booking not found.')));
            return;
        }

        $this->config->load('razorpay', true);
        $cfg = $this->config->item('razorpay');

        $total_amount = (float) $booking['amount'];
        $advance_due  = isset($booking['advance_due']) && (float) $booking['advance_due'] > 0
            ? (float) $booking['advance_due']
            : ((isset($booking['advance_amount']) && (float) $booking['advance_amount'] > 0) ? (float) $booking['advance_amount'] : 1000.00);

        if ($total_amount > 0 && $advance_due > $total_amount) {
            $advance_due = $total_amount;
        }

        $payable_amount = $advance_due;

        if (!empty($cfg['razorpay_test_mode']) && !empty($cfg['razorpay_test_amount'])) {
            $payable_amount = (float) $cfg['razorpay_test_amount'];
        }

        if ($payable_amount <= 0) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Invalid payable amount.')));
            return;
        }

        $amount_in_paise = round($payable_amount * 100);
        $currency = !empty($cfg['razorpay_currency']) ? $cfg['razorpay_currency'] : 'INR';
        $key_id = $cfg['razorpay_key_id'];
        $key_secret = $cfg['razorpay_key_secret'];

        $order_id = '';
        $error_message = '';

        if (function_exists('curl_init') && !empty($key_id) && !empty($key_secret)) {
            $payload = array(
                'amount' => $amount_in_paise,
                'currency' => $currency,
                'receipt' => 'bk_' . $booking_id . '_' . time(),
                'notes' => array(
                    'booking_id' => (string) $booking_id,
                    'customer_name' => isset($booking['customer_name']) ? (string) $booking['customer_name'] : '',
                    'customer_phone' => isset($booking['customer_phone']) ? (string) $booking['customer_phone'] : '',
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
                        'payment_mode' => 'Razorpay',
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

        $this->output->set_output(json_encode(array(
            'success' => true,
            'order_id' => $order_id,
            'amount' => $amount_in_paise,
            'currency' => $currency,
            'key_id' => $key_id,
            'company_name' => !empty($cfg['razorpay_company_name']) ? $cfg['razorpay_company_name'] : 'SURYA DEEP CAR RENT',
            'logo_url' => !empty($cfg['razorpay_logo_url']) ? $cfg['razorpay_logo_url'] : '',
            'theme_color' => !empty($cfg['razorpay_theme_color']) ? $cfg['razorpay_theme_color'] : '#2563eb',
            'customer_name' => isset($booking['customer_name']) ? $booking['customer_name'] : '',
            'customer_phone' => isset($booking['customer_phone']) ? $booking['customer_phone'] : '',
            'customer_email' => isset($booking['customer_email']) ? $booking['customer_email'] : '',
            'description' => 'Booking ' . (isset($booking['booking_code']) ? $booking['booking_code'] : ('#' . $booking_id)) . ' - ' . (isset($booking['vehicle_name']) ? $booking['vehicle_name'] : 'Car Rental'),
        )));
    }

    public function verify_razorpay()
    {
        $this->output->enable_profiler(false);
        $this->output->set_content_type('application/json');

        $customer_id = (int) $this->input->post('customer_id');
        if ($customer_id <= 0) {
            $customer_id = $this->get_active_customer_id();
        }
        $booking_id = (int) $this->input->post('booking_id');

        if ($customer_id <= 0 || !$this->customer_can_access_booking($booking_id, $customer_id)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Invalid booking access.')));
            return;
        }

        $booking = $this->General_model->get_booking_for_flow($booking_id, $customer_id);
        if (empty($booking)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Booking not found.')));
            return;
        }

        $razorpay_payment_id = trim((string) $this->input->post('razorpay_payment_id', true));
        $razorpay_order_id   = trim((string) $this->input->post('razorpay_order_id', true));
        $razorpay_signature  = trim((string) $this->input->post('razorpay_signature', true));

        if (empty($razorpay_payment_id)) {
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Razorpay Payment ID is missing.')));
            return;
        }

        $this->config->load('razorpay', true);
        $cfg = $this->config->item('razorpay');
        $key_secret = $cfg['razorpay_key_secret'];

        // Verify HMAC SHA256 signature if order_id and signature provided
        $signature_verified = false;
        if (!empty($razorpay_order_id) && !empty($razorpay_signature)) {
            $expected_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $key_secret);
            if (hash_equals($expected_signature, $razorpay_signature)) {
                $signature_verified = true;
            }
        }

        // If signature check passed OR if orderless payment, verify directly with Razorpay API
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
            $this->output->set_output(json_encode(array('success' => false, 'message' => 'Payment signature verification failed. Please contact support.')));
            return;
        }

        $total_amount = (float) $booking['amount'];
        $advance_due  = isset($booking['advance_due']) && (float) $booking['advance_due'] > 0
            ? (float) $booking['advance_due']
            : ((isset($booking['advance_amount']) && (float) $booking['advance_amount'] > 0) ? (float) $booking['advance_amount'] : 1000.00);

        if ($total_amount > 0 && $advance_due > $total_amount) {
            $advance_due = $total_amount;
        }

        $payable_amount = $advance_due;
        if (!empty($cfg['razorpay_test_mode']) && !empty($cfg['razorpay_test_amount'])) {
            $payable_amount = (float) $cfg['razorpay_test_amount'];
        }

        // Insert into payments table
        $existing_payment = $this->General_model->get_row('payments', array('reference_no' => $razorpay_payment_id));
        if (empty($existing_payment)) {
            $payment_type = ($payable_amount < $total_amount && $total_amount > 0) ? 'advance' : 'payment';
            $this->General_model->insert('payments', array(
                'booking_id' => $booking_id,
                'payment_type' => $payment_type,
                'amount' => $payable_amount,
                'other_expenses' => 0.00,
                'payment_mode' => 'Razorpay',
                'reference_no' => $razorpay_payment_id,
                'notes' => 'Online advance payment via Razorpay. Order ID: ' . $razorpay_order_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));
        }

        // Update bookings table
        $is_full_payment = ($payable_amount >= $total_amount && $total_amount > 0);
        $this->General_model->update('bookings', array('id' => $booking_id), array(
            'status' => 'confirmed',
            'payment_mode' => 'Razorpay',
            'payment_status' => $is_full_payment ? 'paid' : 'advance_paid',
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature,
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        // Sync vehicle and booking status
        $this->General_model->sync_booking_status_from_payment($booking_id);
        $this->clear_public_booking_session();

        $this->session->set_flashdata('swal', array(
            'icon' => 'success',
            'title' => 'Advance Payment Successful!',
            'text' => 'Your advance payment of ₹' . number_format($payable_amount, 2) . ' via Razorpay was confirmed. Your booking is confirmed!',
            'identity' => array(
                'Booking ID' => isset($booking['booking_code']) ? $booking['booking_code'] : ('#' . $booking_id),
                'Payment ID' => $razorpay_payment_id,
                'Advance Paid' => '₹' . number_format($payable_amount, 2),
                'Payment Mode' => 'Razorpay (Online)',
            ),
        ));

        $this->output->set_output(json_encode(array(
            'success' => true,
            'redirect' => base_url('dashboard'),
        )));
    }

    public function store()
    {
        $customer_id = (int) $this->input->post('customer_id');
        if ($customer_id <= 0) {
            $customer_id = $this->get_active_customer_id();
        }
        $booking_id = (int) $this->input->post('booking_id');
        $booking = $this->General_model->get_booking_for_flow($booking_id, $customer_id);

        if (empty($booking)) {
            $this->session->set_flashdata('error', 'Please start your booking again.');
            redirect('dashboard');
        }

        $upload_dir = FCPATH . 'uploads/payments/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        $config = array(
            'upload_path' => $upload_dir,
            'allowed_types' => 'jpg|jpeg|png|pdf|webp',
            'max_size' => 8192,
            'file_ext_tolower' => true,
            'remove_spaces' => true,
            'file_name' => 'payment_' . $customer_id . '_' . $booking_id . '_' . time(),
        );

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('receipt_file')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
            redirect('payments/pay/' . $booking_id . '?customer_id=' . $customer_id);
        }

        $advance_amount = isset($booking['advance_due']) && (float) $booking['advance_due'] > 0
            ? (float) $booking['advance_due']
            : (float) $booking['amount'];
        $upload_data = $this->upload->data();
        $existing_request = $this->General_model->get_payment_request_for_booking($booking_id, $customer_id);
        $payload = array(
            'booking_id' => $booking_id,
            'customer_id' => $customer_id,
            'payment_type' => 'advance',
            'amount' => $advance_amount,
            'payment_mode' => trim($this->input->post('payment_mode', true)),
            'reference_no' => trim($this->input->post('reference_no', true)),
            'receipt_file_name' => $upload_data['file_name'],
            'receipt_path' => 'uploads/payments/' . $upload_data['file_name'],
            'customer_notes' => trim($this->input->post('customer_notes', true)),
            'admin_notes' => '',
            'status' => 'pending',
        );

        if (!empty($existing_request)) {
            $payload['approved_at'] = null;
            $payload['reviewed_by'] = 0;
            $this->General_model->update_payment_request((int) $existing_request['id'], $payload);
        } else {
            if ((int) $this->General_model->create_payment_request($payload) <= 0) {
                $this->session->set_flashdata('error', 'Payment request table is missing. Please ask admin to update the database first.');
                redirect('payments/pay/' . $booking_id . '?customer_id=' . $customer_id);
            }
        }

        $this->General_model->update('bookings', array('id' => $booking_id), array(
            'status' => 'pending',
            'payment_mode' => trim($this->input->post('payment_mode', true)),
            'payment_status' => 'pending',
            'updated_at' => date('Y-m-d H:i:s'),
        ));
        $this->clear_public_booking_session();
        $this->session->set_flashdata('swal', array(
            'icon' => 'success',
            'title' => 'Booking Request Submitted',
            'text' => 'Your booking request and payment receipt were submitted successfully. Admin will review them shortly.',
            'identity' => array(
                'Booking ID' => isset($booking['booking_code']) ? $booking['booking_code'] : ('#' . $booking_id),
                'Customer' => isset($booking['customer_name']) ? $booking['customer_name'] : '',
                'Mobile' => isset($booking['customer_phone']) ? $booking['customer_phone'] : '',
                'Payment Mode' => trim($this->input->post('payment_mode', true)),
            ),
        ));
        redirect('dashboard');
    }
}
