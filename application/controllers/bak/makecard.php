<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Makecard extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
		$this->load->model('makecard/makecard_m', 'model_makecard');
    }

    public function index() {
    }

    /**
     * 
     */
    public function add() {
		$this->form_validation->set_rules('cardtype', '卡类型名称', 'trim|required|min_length[1]');
		$this->form_validation->set_rules('cardstartno', '卡号', 'trim|required|min_length[1]');
        if ($this->form_validation->run() == FALSE)
            $this->template
                    ->title('制卡操作')
                    ->set('title_info', '制卡')
					->set('cardtypelist',$this->cardTypeList())
                    ->build('makecard/add.php');
        else {
			$this->model_makecard->insertMakeCard($_POST);
            redirect('makecard/mlist');
		}
    }

    public function mlist() {

		//get the total count of the corp list.
        $totalNumber = $this->model_makecard->get_make_card_total_num();
		$config['base_url'] = 'http://localhost/member/index.php/makecard/mlist/page';
		$config['total_rows'] = $totalNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                ->title('制卡列表')
                ->set('title_info', '制卡列表')
				->set('mc_data',$this->model_makecard->getMakecardList(null,$startIndex))
				->set('paginationlinks',$this->pagination->create_links())
				->set('totalNumber',$totalNumber)
                ->build('makecard/list.php');
    }

	private function cardTypeList ($param=array()) {
		$cardtypeArr = $this->model_makecard->getCardtypeList();
		$cardtypeList = "<select name='cardtype'>";
		foreach ($cardtypeArr as $item) {
			$cardtypeList .= "<option value='{$item->ct_id}'> {$item->ct_name} </option>";
		}
		$cardtypeList .= "</select>";
		return $cardtypeList;
	}
}