<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Department_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function insertDepartment($param=array()) {

        $this->db->set('corp_id', $param['corplist']);
        $this->db->set('dp_name', $param['dpname']);
        $this->db->set('dp_location', $param['dpaddress']);
        $this->db->set('dp_contact', $param['dpcontact']);
        $this->db->set('dp_phone', $param['dpphone']);
        $this->db->set('dp_cell_phone', $param['dpcellphone']);
        $this->db->set('dp_email', $param['dpemail']);
		$this->db->set('dp_long_desc', $param['dplongdesc']);

        $this->db->set('dp_create_time', time());
        $this->db->set('dp_create_by', 1);
        
        $this->db->insert('cm_department');
        return TRUE;
    }
    /**
     *
     * @param type $param
     * @return boolean
     */
    function update_corp($param = array(),$corp_id) {
        
        $data = array(
            'p_name'  =>  $param['pname'],
            'p_en_name'  =>  $param['penname'],
            'p_street'  =>  $param['pstreet'],
            'p_city'  =>  $param['pcity'],
            'p_state'  =>  $param['pstate'],
            'p_country'  =>  $param['pcountry'],
            'p_postcode'  =>  $param['ppostcode'],
            'p_contact'  =>  $param['pcontact'],
            'p_phone'  =>  $param['pphone'],
            'p_cellphone'  =>  $param['pcellphone'],
            'p_email'  =>  $param['pemail'],
            'p_domain'  =>  $param['pdomain'],
            'p_ip'  =>  $param['pip'],
            'p_modify_time'  =>  time()
        );
        $this->db->where('p_id',$param['pid']);
        $this->db->update('cm_provider',$data);
        return true;
    }
    /**
     *
     * @param type $param
     * @return type 
     */
    function getDepartmentlist($param = array(),$startIndex) {
        $this->db->order_by('dp_id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get('cm_department');

        return $query->result();
    }

	function get_dp_total_num($param = array()) {

		return $this->db->count_all('cm_department');
	}
	/**
     *
     * @param type $param
     * @return type 
     */
    function get_department_list() {
        $this->db->order_by('dp_id','desc');
        $query = $this->db->get('cm_department');

        return $query->result();
    }
	/**
     *
     * @param type $param
     * @return type 
     */
    function get_corp_list() {
        $this->db->order_by('cp_id','desc');
        $query = $this->db->get('cm_corp');

        return $query->result();
    }
	/**
     *
     * @param type $param
     * @return type 
     */
    function insertStaff($param=array()) {

        $this->db->set('dp_id', $param['deplist']);
        $this->db->set('corp_no', $param['corplist']);
        $this->db->set('sf_no', $param['sf_no']);
        $this->db->set('sf_name', $param['sf_name']);
        $this->db->set('sf_en_name', $param['sf_en_name']);
        $this->db->set('sf_address', $param['sf_address']);
        $this->db->set('sf_address2', $param['sf_address2']);
		$this->db->set('sf_city', $param['sf_city']);
		$this->db->set('sf_province', $param['sf_province']);
        $this->db->set('sf_country', $param['sf_country']);
        $this->db->set('sf_postcode', $param['sf_postcode']);
        $this->db->set('sf_cellphone', $param['sf_cellphone']);
		$this->db->set('sf_phone', $param['sf_phone']);
        $this->db->set('sf_email', $param['sf_email']);
        $this->db->set('sf_desc', $param['sf_desc']);
		$this->db->set('sf_city', $param['sf_city']);
        $this->db->set('sf_create_time', time());
        $this->db->set('sf_create_by', 1);
        
        $this->db->insert('cm_staff');
        return TRUE;
    }

	/**
     *
     * @param type $param
     * @return type 
     */
    function getStafflist($param = array(),$startIndex) {
		$this->db->select('cm_staff.*,cm_corp.cp_name,cm_department.dp_name');
		$this->db->from('cm_staff');
		$this->db->join('cm_corp','cm_staff.corp_no=cm_corp.cp_id');
		$this->db->join('cm_department','cm_staff.dp_id=cm_department.dp_id');
        $this->db->order_by('cm_staff.sf_id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get();

        return $query->result();
    }

	function get_staff_total_num($param = array()) {

		return $this->db->count_all('cm_staff');
	}

	private function _paramArr($param=array()) {
		
		return null;
	}
	
}