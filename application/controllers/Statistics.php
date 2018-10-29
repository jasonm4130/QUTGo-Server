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

        $i = 0;

        foreach($this->stats_model->getFriends($id) as $friend){
            $data['friendsInfo'] = $friend;
            if($friend['user_one'] == $id){
                $data['friendsNames'][$i] = $this->stats_model->getFriendsNames($friend['user_two'])[0]['first_name'];
                $data['friendStats'][$i] = $data['steps'] = $this->stats_model->get_stats($friend['user_two']);
            } else {
                $data['friendsNames'][$i] = $this->stats_model->getFriendsNames($friend['user_one'])[0]['first_name'];
                $data['friendStats'][$i] = $data['steps'] = $this->stats_model->get_stats($friend['user_one']);
            }
            $i++;
        }

        $data['title'] = 'Statistics';

        $this->load->view('templates/header', $data);
        $this->load->view('stats/index', $data);
        $this->load->view('templates/footer');
    }


}