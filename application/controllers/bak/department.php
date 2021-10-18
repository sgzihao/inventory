<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
		$this->load->model('provider/department_m', 'model_dp');
        $this->load->library('form_validation');
    }

    public function index() {
        // Render the layout
		//get the total count of the corp list.
        $totalDepNumber = $this->model_dp->get_dp_total_num();
		$config['base_url'] = 'http://localhost/member/index.php/department/index/page';
		$config['total_rows'] = $totalDepNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                ->title('商户管理')
                ->set('title_info', '商户管理')
				->set('dep_data',$this->model_dp->getDepartmentlist(null,$startIndex))
				->set('paginationlinks',$this->pagination->create_links())
				->set('totalDpNumber',$totalDepNumber)
                ->build('department/list.php');
    }
    /**
     * 
     */
    public function adddepartment() {
        $this->form_validation->set_rules('dpname', '部门名称', 'trim|required|min_length[2]|max_length[12]|xss_clean');
        if ($this->form_validation->run() == FALSE)
			$this->template
               ->title('添加部门')
                ->set('title_info','添加部门')
				->set('corplistinfo',$this->_corpList())
                ->build('department/add.php');
        else {
			$this->model_dp->insertDepartment($_POST);
            redirect('department/');
		}
    }

    public function stafflist() {

		//get the total count of the corp list.
        $totalDepNumber = $this->model_dp->get_staff_total_num();
		$config['base_url'] = 'http://localhost/member/index.php/department/stafflist/page';
		$config['total_rows'] = $totalDepNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                ->title('商户管理')
                ->set('title_info', '商户管理')
				->set('sf_data',$this->model_dp->getStafflist(null,$startIndex))
				->set('paginationlinks',$this->pagination->create_links())
				->set('totalDpNumber',$totalDepNumber)
				->set('corplistinfo',$this->_corpList())
				->set('deplistinfo',$this->_departmentList())
                ->build('department/stafflist.php');
    }
	
    public function addstaff() {
        $this->form_validation->set_rules('sf_no', '员工编号', 'trim|required|min_length[3]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('sf_name', '员工姓名', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        if ($this->form_validation->run() == FALSE)
			$this->template
               ->title('添加新员工')
                ->set('title_info','添加新员工')
				->set('corplistinfo',$this->_corpList())
				->set('deplistinfo',$this->_departmentList())
                ->build('department/addstaff.php');
		else {
			$this->model_dp->insertStaff($_POST);
            redirect('department/stafflist/');
		}
    }
    
	private function _corpList() {
		$corpArr = $this->model_dp->get_corp_list();
		$corpSelectList = "<select name='corplist'><option value=''>-请选择-</option>";
		foreach ($corpArr as $item) {
			$corpSelectList .= "<option value='{$item->cp_id}'>{$item->cp_name} - ($item->cp_en_name}</option>";
		}
		
		$corpSelectList .= "</select>";
		return $corpSelectList;
	}
	private function _departmentList() {
		
		$depArr = $this->model_dp->get_department_list();
		$depSelectList = "<select name='deplist'><option value=''>-请选择-</option>";
		foreach ($depArr as $item) {
			$depSelectList .= "<option value='{$item->dp_id}'> {$item->dp_name} </option>";
		}
		
		$depSelectList .= "</select>";
		return $depSelectList;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */