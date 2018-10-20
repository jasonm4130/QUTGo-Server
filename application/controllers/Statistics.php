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
        $data = array();
        $this->load->library('session');
        $id = $this->session->userdata('user_id');
        $data['steps'] = $this->stats_model->get_stats($id);
        $data['lastWeek'] = $this->stats_model->getLastWeekDates();
        $data['thisWeek'] = $this->stats_model->getThisWeekDates();

        $data['friends'] = $this->stats_model->getFriends($id);

        $data['title'] = 'Statistics';

        $this->load->view('templates/header', $data);
        $this->load->view('stats/index', $data);
        $this->load->view('templates/footer');
    }


}