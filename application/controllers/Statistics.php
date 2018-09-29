<?php

class Statistics extends CI_Controller {

    public function __construct()
    {
            parent::__construct();
            $this->load->model('stats_model');
            $this->load->helper('url_helper');
    }

    public function index()
    {
        $data['stats_current'] = $this->stats_model->get_stats_current();
        $data['stats_past'] = $this->stats_model->get_stats_past();
        $data['title'] = 'Statistics';

        $this->load->view('templates/header', $data);
        $this->load->view('stats/index', $data);
        $this->load->view('templates/footer');
    }
}