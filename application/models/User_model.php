<?php
class User_model extends CI_model{

    public function register_user($user){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email',$user['email']);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            $this->db->where('email',$user['email']);
            $this->db->update('user', $user);
        } else {
            $this->db->insert('user', $user);
        }
    }
 
    public function login_user($email,$pass){
    
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email',$email);
        $this->db->where('password',$pass);
    
        if($query=$this->db->get())
        {
            return $query->row_array();
        }
        else{
            return false;
        }
    }

    public function email_check($email){
    
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email',$email);
        $query=$this->db->get();
    
        if($query->num_rows() == 1){
            foreach ($query->result() as $row) {
                if(!$row->password){
                    return true;
                }
                return false;
            }
            return false;
        } elseif ($query->num_rows() > 1) {
            return false;
        } else {
            return true;
        }
    }
}