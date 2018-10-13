<?php
 
class User extends CI_Controller {
 
    public function __construct(){
    
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->library('session');
    
    }

    public function index(){
        $this->load->view("register.php");
    }
	
    public function register_user(){
    
        $user=array(
        'username'=>$this->input->post('user_name'),
        'email'=>$this->input->post('user_email'),
        'password'=>md5($this->input->post('user_password')),
        'first_name'=>$this->input->post('first_name'),
        'last_name'=>$this->input->post('last_name'),
        );
        $email_check=$this->user_model->email_check($user['email']);
        if($email_check){
            $this->user_model->register_user($user);
            $this->session->set_flashdata('success_msg', 'Registered successfully.Now login to your account.');
            redirect('user/login_view');
        }
        else{
            $this->session->set_flashdata('error_msg', 'That Email Address is already Registered');
            redirect('user');
        }
    }

    public function login_view(){
        $this->load->view("login.php");
    }

    function login_user(){
        $user_login=array(
        
        'user_email'=>$this->input->post('user_email'),
        'user_password'=>md5($this->input->post('user_password'))
        
        );
        
        $data=$this->user_model->login_user($user_login['user_email'],$user_login['user_password']);
        if($data)
        {
            $this->session->set_userdata('user_id', $data['user_id']);
            $this->session->set_userdata('user_email', $data['email']);
            $this->session->set_userdata('user_name', $data['username']);
            $this->session->set_userdata('firstname', $data['first_name']);
            $this->session->set_userdata('lastname', $data['last_name']);

            echo 'success';
        }
    }

    function user_profile(){
        $this->load->view('user_profile.php');
    }

    public function user_logout(){
        $this->session->sess_destroy();
        redirect(base_url());
    }
}