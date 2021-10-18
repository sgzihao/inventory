<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends Admin_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->library('form_validation');
    }

    public function index() {
        // Render the layout
        $this->template
                ->title('test')
                ->build('welcome_message');
        //$this->template->set_partial('content', 'welcome_message');
        //$this->load->view('welcome_message');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */