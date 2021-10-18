<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Provider extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('provider/provider_m', 'model_p');
        $this->load->model('provider/corp_m', 'model_cp');
    }

    public function index() {

        $this->template
                ->title('商户管理')
                ->set('title_info', '商户管理')
                ->set('p_data', $this->model_p->get_profile())
                ->build('provider/portal.php');
    }

    public function updateprovider() {

        $this->form_validation->set_rules('pname', '发卡结构名称', 'required');

        if ($this->form_validation->run() == FALSE)
            $this->template
                    ->title('修改发卡商户信息')
                    ->set('title_info', '修改发卡商户基本信息')
                    ->set('p_data', $this->model_p->get_profile())
                    ->build('provider/updateprovider.php');
        else {
            $this->model_p->update_provider($_POST);
            redirect('provider/');
        }
    }

    /**
     * 
     */
    public function add() {

        $this->form_validation->set_rules('cp_name', '商户名称', 'required');

        if ($this->form_validation->run() == FALSE){
			//show_error('test');
			$this->template
                    ->title('添加商户')
                    ->set('title_info', '添加商户')
                    ->build('provider/add.php');
		}
        else {
            $this->model_cp->insert_corp($_POST);
            redirect('provider/corplist');
        }
    }
    public function modifycorp() {
        
    }

    /**
     * 
     */
    public function corplist() {
		//get the total count of the corp list.
        $totalCorpNumber = $this->model_cp->get_corp_total_num();
		
		$config['total_rows'] = $totalCorpNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                    ->title('商户列表')
                    ->set('title_info', '商户列表')
                    ->set('corp_data',$this->model_cp->get_corp_list(null,$startIndex))
					->set('paginationlinks',$this->pagination->create_links())
                    ->build('provider/corplist.php');
    }
}
