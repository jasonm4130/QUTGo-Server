<?php
class stats_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        public function get_stats_past($userId = 262)
        {
                                $this->db->select('*');
                $this->db->from('step');
                $this->db->where('user_id', $userId);

                $this->load->helper('date');
                $week = getLastWeekDates();
                $this->db->where_in('date', $week);

                $query = $this->db->get();
                return $query->result_array();
        }

        public function get_stats_current($userId = 262)
        {
                                $this->db->select('*');
                $this->db->from('step');
                $this->db->where('user_id', $userId);

                $this->load->helper('date');
                $week = getThisWeekDates();
                $this->db->where_in('date', $week);

                $query = $this->db->get();
                return $query->result_array();
        }
}