<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
		$this->load->model('member/member_m', 'model_member');
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
		$this->form_validation->set_rules('user_name', '姓名', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('user_pwd', '密码', 'trim|required|min_length[6]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('user_pwd_cf', '密码确认', 'trim|required|min_length[6]|max_length[15]|xss_clean|matches[user_pwd]');
		$this->form_validation->set_rules('user_email', '邮箱', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE)
            $this->template
                    ->title('注册会员')
                    ->set('title_info', '注册会员')
                    ->build('member/add.php');
        else {
			$this->model_member->insertMember($_POST);
            redirect('member/mlist');
		}
    }

    public function mlist() {

		//get the total count of the corp list.
        $totalMemberNumber = $this->model_member->get_member_total_num();
		$config['base_url'] = 'http://localhost/member/index.php/member/mlist/page';
		$config['total_rows'] = $totalMemberNumber;
		
		$this->pagination->initialize($config); 
		
		//Get the Current page number.
		$startIndex = ($this->uri->segment(4) > 0)? ($this->uri->segment(4)):0;

        $this->template
                ->title('会员列表')
                ->set('title_info', '会员列表管理')
				->set('member_data',$this->model_member->getMemberlist(null,$startIndex))
				->set('paginationlinks',$this->pagination->create_links())
				->set('totalNumber',$totalMemberNumber)
                ->build('member/userlist.php');
    }

    public function addlevel() {
        // Render the layout
        $this->template
                ->title('添加会员分类')
                ->set('title_info', '会员中心')
                ->build('member/addlevel.php');
    }

    public function memberlevel() {
        $query = $this->db->query('SELECT gp_name, gp_desc FROM cm_user_level');

        foreach ($query->result() as $row) {
            echo $row->gp_name;
            echo $row->gp_desc;
        }

        $this->template
                ->title('会员等级列表')
                ->set('title_info', '会员中心')
                ->build('member/level.php');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */