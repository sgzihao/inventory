<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Location extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('location/location_m', 'model_location');
    }

    public function index() {
        
        $locationData = $this->model_location->getOfficeList($_POST);
        $totalNumber = $this->model_location->num;
        $config['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/page');
        $config['total_rows'] = $totalNumber;

        $this->pagination->initialize($config);
        
        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;
        $this->template
                ->title('Global Sources Office')
                ->set('title_info', 'location list')
                ->set('officeData', $locationData)
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('totalNumber', $totalNumber)
                ->build('location/list.php');
    }

    /**
     * Add New Categry 
     */
    public function newlocation() {
        $this->form_validation->set_rules('gsofficename', 'Office Name', 'required');

        if ($this->form_validation->run() == true) {
            $result = $this->model_location->addNewOffice($_POST);
            if ($result)
                redirect('location/');
            else {
                // an error
                //$this->message->set('You forgot a required field.', 'error');
                // a notice after a redirect
                //$this->message->set('Your account will expire in 5 days.', 'notice', TRUE);
                // a success message after a redirect in a specific group
                //$this->message->set('Your subscription has been received.', 'success', TRUE, 'newsletter');
            }
        }

        $this->template
                ->title('Office Management')
                ->set('title_info', 'New Office')
                ->build('location/add.php');
    }

    /**
     * 
     */
    function edit() {
        $params = $this->uri->uri_to_assoc();
        $id = isset($params['id']) ? $params['id'] : $_REQUEST['id'];
        $locationInfo = $this->model_location->getLocationById($id);

        $this->form_validation->set_rules('gsofficename', 'Office Name', 'required');

        if ($this->form_validation->run() == true) {
            $result = $this->model_location->updateOffice($id, $_POST);
            if ($result)
                redirect('location/');
        }

        $this->template
                ->title('Office Management')
                ->set('title_info', 'Edit Office')
                ->set('locationinfo', $locationInfo)
                ->build('location/edit.php');
    }

    function deletelocation() {
        //$params = $this->uri->uri_to_assoc();
        $locationid = isset ($_POST['id'])?$_POST['id']:$_REQUEST['id'];
        if ($locationid < 1) {
            die(json_encode(false));
        }
        $invNum = $this->model_location->invtotalNumberBylocation($locationid);
        if($invNum->num > 0)
            die(json_encode($invNum->num));
        
        $rs = $this->model_location->deletelocation($locationid);
        if($rs)
            die(json_encode('0'));
        else
            die(json_encode('-1'));
        return false;
    }
}
