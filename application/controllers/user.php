<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends Admin_Controller {

    private $_permission = null;

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('user/user_m', 'model_user');
    }

    public function index() {
        $userdata = $this->model_user->getUserList($_POST);
        $totalNumber = $this->model_user->num;
        $config['base_url'] = site_url($this->router->class . '/' . $this->router->method . '/page');
        $config['total_rows'] = $totalNumber;

        $this->pagination->initialize($config);

        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;


        $this->form_validation->set_rules('username', 'User Name', 'required');
        $locationArr = $this->model_user->getLocationList();
        $this->template
                ->title('User List')
                ->set('title_info', 'User List')
                ->set('userData', $userdata)
                ->set('locationArr', $locationArr)
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('totalNumber', $totalNumber)
                ->build('user/list.php');
    }

    public function newuser() {

        $this->form_validation->set_rules('username', 'Loginname', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'password Confirmation', 'required');
        $this->form_validation->set_rules('useremail', 'Email', 'required');
        if ($this->form_validation->run() == true) {

            $locationArr = array();
            if (isset($_POST['location'])) {
                foreach ($_POST['location'] as $key => $v) {
                    $tmparr = explode('_', $v);
                    $locationArr[] = $tmparr[1];
                }
            }
            sort($locationArr);
            $password = md5($_POST['password']);
            $status = isset($_POST['useractive']) ? 'active' : 'inactive';
            $locationlist = serialize($locationArr);

            //permission list
            $permissionlist = null;

            //$permission['preport'] = array('report' => 'report');
            if (isset($_POST['permission_inventory']))
                $permission['inventory'] = $_POST['permission_inventory'];
            if (isset($_POST['permission_model']))
                $permission['model'] = $_POST['permission_model'];
            if (isset($_POST['permission_category']))
                $permission['category'] = $_POST['permission_category'];
            if (isset($_POST['permission_invpermission_locationentory']))
                $permission['location'] = $_POST['permission_location'];
            if (isset($_POST['permission_user']))
                $permission['user'] = $_POST['permission_user'];
            //permission_report
            if (isset($_POST['permission_report']))
                $permission['user'] = $_POST['permission_report'];

			if (isset($_POST['permission_rack']))
                $permission['rack'] = $_POST['permission_rack'];
				
            $userData = array(
                'first_name' => $_POST['firstname'],
                'last_name' => $_POST['lastname'],
                'password' => md5($_POST['password']),
                'email' => $_POST['useremail'],
                'username' => $_POST['username'],
                'location_id' => $locationlist,
                'permission_list' => serialize($permission),
                'status' => $status,
                'created_by' => $this->session->userdata('username'),
                'creation_date' => date("Y-m-d H:i:s")
            );

            if ($this->model_user->addUser($_POST['username'], $userData)) {
                redirect('user');
            }
        }
        //
        $this->permissionList();
        $this->template
                ->title('Create New User')
                ->set('title_info', 'Create New User')
                ->set('locationlist', $this->locationList())
                ->set('permission', $this->_permission)
                ->build('user/add.php');
    }

    public function edituser() {
        $params = $this->uri->uri_to_assoc();
        $userid = isset($params['userid']) ? $params['userid'] : $_REQUEST['userid'];
        $userinfo = $this->model_user->getUserById($userid);
        $this->permissionList($userinfo->permission_list);
        //var_dump($userinfo->permission_list);
        
        $this->form_validation->set_rules('username', 'Loginname', 'required');
        $this->form_validation->set_rules('password', 'Password', 'matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'password Confirmation', '');
        $this->form_validation->set_rules('useremail', 'Email', 'required');
        if ($this->form_validation->run() == true) {

            $locationArr = array();
            if (isset($_POST['location'])) {
                foreach ($_POST['location'] as $key => $v) {
                    $tmparr = explode('_', $v);
                    $locationArr[] = $tmparr[1];
                }
            }
            sort($locationArr);
            $status = isset($_POST['useractive']) ? 'active' : 'inactive';
            $locationlist = serialize($locationArr);

            //permission list
            $permissionlist = array();

            //$permission['preport'] = array('report' => 'report');
            if (isset($_POST['permission_inventory']))
                $permission['inventory'] = $_POST['permission_inventory'];
            if (isset($_POST['permission_model']))
                $permission['model'] = $_POST['permission_model'];
            if (isset($_POST['permission_category']))
                $permission['category'] = $_POST['permission_category'];
            if (isset($_POST['permission_location']))
                $permission['location'] = $_POST['permission_location'];
            if (isset($_POST['permission_user']))
                $permission['user'] = $_POST['permission_user'];
            //permission_report
            if (isset($_POST['permission_report']))
                $permission['report'] = $_POST['permission_report'];
				
			if (isset($_POST['permission_rack']))
                $permission['rack'] = $_POST['permission_rack'];

            $userData = array(
                'first_name' => $_POST['firstname'],
                'last_name' => $_POST['lastname'],
                'email' => $_POST['useremail'],
                'username' => $_POST['username'],
                'location_id' => $locationlist,
                'permission_list' => serialize($permission),
                'status' => $status,
                'modified_by' => $this->session->userdata('username')
            );
            if (!empty($_POST['password']))
                $userData['password'] = md5($_POST['password']);

            if ($this->model_user->updateUser($userid, $userData))
                redirect('user');
        }
        
        //
        $this->template
                ->title('Edit User')
                ->set('title_info', 'Edit User')
                ->set('userinfo', $userinfo)
                ->set('locationlist', $this->locationList($userinfo->location_id))
                ->set('permission', $this->_permission)
                ->build('user/edit.php');
    }

    /**
     * 
     */
    public function deleteuser() {
        //$params = $this->uri->uri_to_assoc();
        $userid = isset($_POST['userid']) ? $_POST['userid'] : $_REQUEST['userid'];
        if ($userid < 1) {
            die(json_encode(false));
        }
        $rs = $this->model_user->deleteuser($userid);
        die(json_encode($rs));
        return false;
    }

    private function locationList($userinfo=null) {
        if (isset($userinfo))
            $userLocation = unserialize($userinfo);
        else
            $userLocation = array();
        $locationArr = $this->model_user->getLocationList();
        //var_dump($categoryArr);
        $locationlist = "<tr>";

        $i = 0;
        foreach ($locationArr as $row) {
            if ($i > 0 && ($i % 3) == 0)
                $locationlist .= "</tr>";

            //check if it has already exits in the rel table
            $checked = '';
            if (in_array($row->location_id, $userLocation))
                $checked = "checked='checked'";

            $locationlist .= "<td width='33%'>
                        <input type='checkbox' id='cid_{$row->location_id}' value='cid_{$row->location_id}' name=location[] {$checked} />
			<label for='cid_{$row->location_id}'>" . $row->location_name . "</label>
			</td>";

            $i++;
        }
        $locationlist .= "</tr>";
        return $locationlist;
    }

    private function permissionList($param = null) {
        if (isset($param))
            $userPermission = unserialize($param);
        else
            $userPermission = array();
        //var_dump($userPermission);
        
        $permission = null;
        //
        $permission['permission_inventory'] = array('create' => 'newitem', 'edit' => 'edititem', 'delete' => 'delinventory');

        //
        $permission['permission_model'] = array('create' => 'newmodel', 'edit' => 'editmodel', 'delete' => 'delmodel');
        $permission['permission_category'] = array('create' => 'newcategory', 'edit' => 'editcategory', 'delete' => 'delcategory');
        $permission['permission_location'] = array('create' => 'newlocation', 'edit' => 'edit', 'delete' => 'deletelocation');
        $permission['permission_user'] = array('create' => 'newuser', 'edit' => 'edituser', 'delete' => 'deleteuser');
        //
        $permission['permission_report'] = array('report' => 'report');
        $permission['permission_rack'] = array('rack' => 'index','upload' =>'upload');
        
        foreach ($permission as $key => $item) {
            $keyArr = explode("_",$key);
            //$key = $keyArr[1];

            $this->_permission .= "<tr>";
            foreach ($item as $eachKey => $eachMenu) {

                //check if it has already exits in the rel table
                $checked = '';
                if(isset ($userPermission[$keyArr[1]])) {
                    if (in_array($eachMenu, $userPermission[$keyArr[1]]))
                        $checked = "checked='checked'";
                }
                
                $this->_permission .= "<td class='fieldarea'>";
                $this->_permission .= "<input type='checkbox' name='{$key}[]' value='{$eachMenu}' id='{$key}{$eachMenu}' {$checked}>";
                $this->_permission .= "<label for='{$key}{$eachMenu}'>{$eachKey} {$key}</label>";
                $this->_permission .= "</td>";
            }
            $this->_permission .= "</tr>";
        }
    }

}
