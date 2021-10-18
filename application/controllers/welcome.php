<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * @desc        administrator login page
 * @author      szihao@example.com
 * @category    Auth login
 * @version     1.0
 * 
 * 
 */
class Welcome extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('encrypt');

        $this->load->library('session');

        $this->load->library('pagination');

        $this->load->library('uri');

        $this->load->database();

        $this->load->helper(array('form'));

        $this->load->library('form_validation');
    }

    /**
     * 
     */
    public function index() {
        $data = array();
        $data['loginfomsg'] = null;
        //var_dump($this->session->flashdata('error_text'));
        if ($this->session->flashdata('error_text')) {
            $data['loginfomsg'] = $this->session->flashdata('error_text');
        }
        if ($this->session->userdata('logged_in'))
            redirect('inventory');
        else
            $this->load->view('login/login', $data);
    }

    /**
     * 
     */
    public function login() {

        //Load
        $this->load->helper('url');
        $this->form_validation->set_rules('username', 'Name', 'required|min_length[3]|max_length[32]|alpha_dash');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[32]|alpha_dash');

        if ($this->form_validation->run() == false) {
            /**/
            //If you are using OBSession you can uncomment these lines
            $flashdata = array('error' => true, 'error_text' => validation_errors());
            $this->session->set_flashdata($flashdata);
            $this->session->set_flashdata($_POST);
            /**/
            redirect('');
        } else {
            if ($this->auth($this->input->post('username'), $this->input->post('password'))) {
                redirect('inventory');
            } else {
                //
                //If you are using OBSession you can uncomment these lines
                $flashdata = array('error' => true, 'error_text' => 'wrong username or password.');
                $this->session->set_flashdata($flashdata);
                $this->session->set_flashdata($_POST);
                //
                redirect('');
            }
        }
    }

    /**
     * 
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('');
    }

    /**
     * @ How to get the auth list from the database or the config file.
     * 
     * @param type $username
     * @param type $password
     * @return type 
     */
    private function auth($username, $password) {
        $this->load->model('user/user_m', 'model_user');
        //$name = html_escape($username);
        $userInfo = $this->model_user->getUserByName($username);
        if(!$userInfo->user_id) {
            return false;
        }
        $md5password = md5($password);
        if ($md5password != $userInfo->password) {
            return false;
        }

        $loginData = array(
            'username' => $username,
            'userid' => $userInfo->user_id,
            'email' => $userInfo->email,
            'locationlist' => $userInfo->location_id,
            'logged_in' => TRUE
        );

        $this->session->set_userdata($loginData);
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
