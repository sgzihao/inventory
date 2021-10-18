<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {

    private $checkMunu = null;

    public function Admin_Controller() {

        parent::__construct();

        $this->load->library('encrypt');

        $this->load->library('session');

        $this->load->library('pagination');

        $this->load->library('uri');

        $this->load->database();

        $this->load->helper(array('form'));

        $this->load->library('form_validation');

        $this->load->library('message');
        $this->load->library('record');
        $this->form_validation->set_error_delimiters('<p class="msg error">', '</p>');

        $this->output->enable_profiler(false);
        //echo $this->router->class,'----',$this->router->method;
        if (!$this->_checkLogin()) {
            redirect('welcome');
        }
        //check user permission list
        if (!$this->_checkUserPermmison()) {
            $information = "I'm sorry, you don't have permission to access it.";
            show_error($information, 505);
            exit;
        }
        //get system setting information
        $this->systemSetting();
        //$this->_permmisonlist();
        //var_dump($this->session->userdata('locationlist'));
        $this->template
                ->set_layout('portal')
                ->enable_parser(FALSE)
                ->set('administrator', $this->session->userdata('username'))
                ->set('submenulist', $this->_permmisonlist())
                ->set('currentlyController', $this->router->class)
                ->set('checkedMenu', $this->checkMunu)
                //->set('inventoryMenuList', $this->inventoryMenuList())
                ->set_partial('header2', 'admin/partials/header2')
                ->set_partial('menu', 'admin/partials/menu')
                ->set_partial('footer', 'admin/partials/footer');

        //get provider information from database
        //$this->_getProviderInfo();
    }

    /**
     * 
     */
    private function _getProviderInfo() {
        $this->load->model('provider/provider_m', 'model_p');
        $this->session->set_userdata('provider', $this->model_p->get_profile());
    }

    /**
     *
     * @return type 
     */
    private function _checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata("locationlist");

            $this->load->model('user/user_m', 'model_user');
            //$name = html_escape($username);
            $userInfo = $this->model_user->getUserByName($this->session->userdata('username'));
            //
            $locationlist = $userInfo->location_id;
            $permission_list = $userInfo->permission_list;
            //
            $this->session->set_userdata(array('locationlist' => $locationlist));
            $this->session->set_userdata(array('permission_list' => $permission_list));
            return true;
        }
        else
            return false;
    }

    private function _checkUserPermmison() {
        $permission_list = unserialize($this->session->userdata('permission_list'));
        //var_dump($permission_list);
        //echo $this->router->class,'----',$this->router->method;
        //exit;
        if ($this->router->class == "setting")
            return true;
        if ($this->router->class == "welcome")
            return true;
        if (isset($permission_list[$this->router->class])) {
            if ($this->router->method == "index")
                return true;
            if (in_array($this->router->method, $permission_list[$this->router->class])) {
                return true;
            } elseif ($this->router->class == "report") {
                return true;
            }
            return true;
        }
    }

    private function _permmisonlist() {
        $permission_list = unserialize($this->session->userdata('permission_list'));
        //var_dump($permission_list);
        //echo $this->router->class,'----',$this->router->method;
        $authlist = array();

        foreach ($permission_list as $controller => $item) {
            $tmp = array();
            $tmp[] = "<li><strong><a href=''>{$controller} list</a></strong></li>";
            foreach ($item as $key => $value) {
                $url = site_url($controller . '/' . $value);
                $tmp[] = "<li><a href='{$url}'>- {$value} {$controller}</a></li>";
            }
        }
        /**
        $xml = simplexml_load_file("config/menu.xml");
        //echo $xml->getName() . "<br />";
        foreach ($xml->children() as $module) {
             echo var_dump($module->attributes()) . "\n";
             foreach ($module->children() as $child) {
                 echo var_dump($child->attributes()) . "\n";
                 echo var_dump($child);
                 
             }
        }
        */
        $inventory = array();
        if (isset($permission_list['inventory'])) {
            if (in_array('newitem', $permission_list['inventory']))
                $inventory[] = "<li><a href='" . site_url('inventory/newitem') . "'><span>- New Inventory</span></a></li>";
            $inventory[] = "<li><a href='" . site_url('inventory') . "'><span>- Inventory List</span></a></li>";
            $inventory[] = "<li><a href='" . site_url('inventory/batchUpdateCategory') . "'><span>- Batch Update Category</span></a></li>";
            $authlist['inventory'] = $inventory;
        }

        $model = array();
        if (isset($permission_list['model'])) {
            if (in_array('newmodel', $permission_list['model']))
                $model[] = "<li><a href='" . site_url('model/newmodel') . "'><span>- New Inventory Model</span></a></li>";

            $model[] = "<li><a href='" . site_url('model') . "'><span>- Inventory Model List</span></a></li>";
            $authlist['model'] = $model;
        }

        $category = array();
        if (isset($permission_list['category'])) {
            if (in_array('newcategory', $permission_list['category']))
                $category[] = "<li><a href='" . site_url('category/newcategory') . "'><span>- New Category</span></a></li>";

            $category[] = "<li><a href='" . site_url('category') . "'><span>- Category List</span></a></li>";

            $category[] = "<li><a href='" . site_url('category/ipv6MinVersion') . "'><span>- IPV6 Min Version List</span></a></li>";

            $authlist['category'] = $category;
        }

        //report permission list.
        $report = array();
        if (isset($permission_list['report'])) {
            $authlist['report'] = array("<li><strong><a href='#'><span>Report</span></a></strong></li>",
                "<li><a href='" . site_url('report/upload') . "'><span>- Report Setting</span></a></li>",
                "<li><a href='" . site_url('report/a') . "'><span>- Report by Appls</span></a></li>",
                "<li><a href='" . site_url('report/b') . "'><span>- Report by Overall Condition</span></a></li>",
                "<li><a href='" . site_url('report/c') . "'><span>- Report by Components</span></a></li>",
                "<li><strong><a href='#'><span>IPV6 Report</span></a></strong></li>",
                "<li><a href='" . site_url('report/ipv6list') . "'><span>- IPV6 Report</span></a></li>",
                "<li><strong><a href='#'><span>System Log</span></a></strong></li>",
                "<li><a href='" . site_url('report/userlog') . "'><span>- System Log</span></a></li>");
        }

        $report = array();

        $authlist['setting']['setting'] = array("<li><strong><a href='#'><span>Setting</span></a></strong></li>",
            "<li><a href='" . site_url('setting/profile') . "'><span>- Personal Profile</span></a></li>",
            "<li><a href='" . site_url('setting/upload') . "'><span>- upload file</span></a></li>");

        $location = array();
        if (isset($permission_list['location'])) {
            $location[] = "<li><strong><a href=''><span>Location List</span></a></strong></li>";
            if (in_array('newlocation', $permission_list['location']))
                $location[] = "<li><a href='" . site_url('location/newlocation') . "'><span>- New Location</span></a></li>";
            $location[] = "<li><a href='" . site_url('location') . "'><span>- Location List</span></a></li>";

            $authlist['setting']['location'] = $location;
        }

        $user = array();
        if (isset($permission_list['user'])) {
            $location[] = "<li><strong><a href=''><span>Roles and Admin List</span></a></strong></li>";
            if (in_array('newuser', $permission_list['user']))
                $user[] = "<li><a href='" . site_url('user/newuser') . "'><span>- New User</span></a></li>";
            $user[] = "<li><a href='" . site_url('user') . "'><span>- User List</span></a></li>";
            $authlist['setting']['user'] = $user;
        }


        $this->checkMunu = $this->router->class;

        return $authlist;
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    private function systemSetting($param = array()) {
        $this->load->model('admin/admin_m', 'model_admin');

        $config = $this->model_admin->getSystemSetting();
        $result = array();
        foreach ($config as $item) {
            $result[$item->category] = $item->value;
        }

        $this->session->set_userdata($result);

        return;
    }

}
