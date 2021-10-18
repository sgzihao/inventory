<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cardtype extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
		$this->load->model('cardtype/cardtype_m', 'model_ctype');
        $this->load->library('form_validation');
    }

    public function index() {
        // Render the layout
        $this->template
                ->title('商户管理')
                ->set('title_info', '商户管理')
                ->build('department/list.php');
    }

    /**
     * 
     */
    public function add() {
		$this->form_validation->set_rules('cardtypename', '卡类型名称', 'trim|required|min_length[1]');

        if ($this->form_validation->run() == FALSE)
            $this->template
                    ->title('卡类型')
                    ->set('title_info', '添加卡类型')
                    ->build('cardtype/add.php');
        else {
			$this->model_ctype->insertCardtype($_POST);
            redirect('cardtype/clist');
		}
    }

    public function clist() {

		//get the total count of the corp list.
        $totalNumber = $this->model_ctype->get_card_type_total_num();
		$config['base_url'] = 'http://localhost/member/index.php/cardtype/clist/page';
		$config['total_rows'] = $totalNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                ->title('卡类型列表')
                ->set('title_info', '卡类型列表')
				->set('ct_data',$this->model_ctype->getCardtypeList(null,$startIndex))
				->set('paginationlinks',$this->pagination->create_links())
				->set('totalNumber',$totalNumber)
                ->build('cardtype/list.php');
    }
}