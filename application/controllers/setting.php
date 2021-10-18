<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * 
 */
class Setting extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('setting/setting_m', 'model_system');
    }

    /**
     * 
     */
    public function index() {
        redirect("setting/profile");
    }

    /**
     * Add New Categry 
     */
    public function newuser() {

        $this->form_validation->set_rules('username', 'User Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            //show_error('test');
            $this->template
                    ->title('System Setting')
                    ->set('title_info', 'System Setting')
                    ->set('locationlist', $this->locationList())
                    ->build('setting/add.php');
        } else {
            redirect('system');
        }
    }
    
    public function upload() {
        $this->template
                ->title('upload file')
                ->set('title_info', 'upload file')
                ->set('error','')
                ->build('setting/upload.php');
    }
    public function do_upload() {
        $config['upload_path'] = 'upload/';
        $config['allowed_types'] = 'gif|jpg|png|txt|csv';
        $config['max_size'] = '1000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = $this->upload->display_errors();

            $this->template
                ->title('upload file')
                ->set('title_info', 'upload file')
                ->set('error',$error)
                ->build('setting/upload.php');
        } else {
            $data = $this->upload->data();
            $this->template
                ->title('upload file')
                ->set('title_info', 'upload file')
                ->set('upload_data',$data)
                ->build('setting/doupload.php');
        }
        
    }
    
    public function profile() {
        //load model
        $this->load->model('user/user_m', 'model_user');
        
        //
        $userid = $this->session->userdata('userid');
        $userinfo = $this->model_user->getUserById($userid);
        
        //var_dump($userinfo->permission_list);
        
        $this->form_validation->set_rules('username', 'Loginname', 'required');
        $this->form_validation->set_rules('password', 'Password', 'matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'password Confirmation', '');
        $this->form_validation->set_rules('useremail', 'Email', 'required');
        if ($this->form_validation->run() == true) {

            $status = isset($_POST['useractive']) ? 'active' : 'inactive';

            $userData = array(
                'first_name' => $_POST['firstname'],
                'last_name' => $_POST['lastname'],
                'email' => $_POST['useremail'],
                'username' => $_POST['username'],
                'status' => $status,
                'modified_by' => $this->session->userdata('username')
            );
            if (!empty($_POST['password']))
                $userData['password'] = md5($_POST['password']);

            //
            if ($this->model_user->updateUser($userid, $userData)) {
                
                $this->message->set('updated successfully.', 'error');
            } else {
                $this->message->set('There is something wrong with the database, Please try again.', 'error');
            }
        }
        
        //
        $this->template
                ->title('Edit Personal Profile')
                ->set('title_info', 'Personal Profile')
                ->set('userinfo', $userinfo)
                ->build('setting/profile.php');
    }


    private function locationList($param = array()) {

        $locationArr = $this->model_system->getLocationList();
        //var_dump($categoryArr);
        $locationlist = "<tr>";

        $i = 0;
        foreach ($locationArr as $row) {
            if ($i > 0 && ($i % 3) == 0)
                $locationlist .= "</tr>";

            //check if it has already exits in the rel table
            $locationlist .= "<td width='33%'>
                <input type='checkbox' id='cid_{$row->location_id}' value='cid_{$row->location_id}' name=category[]>
                <label for='cid_{$row->location_id}'>" . $row->location_name . "</label>
            </td>";

            $i++;
        }
        $locationlist .= "</tr>";
        return $locationlist;
    }

}
