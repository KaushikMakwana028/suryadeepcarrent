<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function privacy_policy()
    {
        $data = array();

        // $this->load->view('partials/header', $data);
        $this->load->view('privacy_policy', $data);
        // $this->load->view('partials/footer', $data);
    }

    public function terms_condition()
    {
        $data = array();

        // $this->load->view('partials/header', $data);
        $this->load->view('terms_condition', $data);
        // $this->load->view('partials/footer', $data);
    }

    public function refund_policy()
    {
        $data = array();

        // $this->load->view('partials/header', $data);
        $this->load->view('refund_policy', $data);
        // $this->load->view('partials/footer', $data);
    }
}