<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Member_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function insertMember($param=array()) {
		
        $this->db->set('name', $param['user_name']);
        $this->db->set('password', $param['user_pwd']);
        $this->db->set('email', $param['user_email']);
        $this->db->set('sex', $param['user_sex']);
        $this->db->set('birthday', $param['user_birthday']);
        $this->db->set('id_type', $param['user_id_type']);
        $this->db->set('id_no', $param['user_id_no']);
		$this->db->set('cellphone', $param['user_cellphone']);
		$this->db->set('phone', $param['user_phone']);
        $this->db->set('fax', $param['user_fax']);
        $this->db->set('address_street', $param['user_address']);
        $this->db->set('address_city', $param['user_city']);
        $this->db->set('address_state', $param['user_province']);
        $this->db->set('address_postcode', $param['user_postcode']);
        $this->db->set('address_country', $param['user_country']);
		$this->db->set('description', $param['user_desc']);
		$this->db->set('website', $param['user_website']);

        $this->db->set('date_entered', date("Y-m-d H:i:s"));
        $this->db->set('created_by', 'system');
        
        $this->db->insert('cm_user');
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
    function getMemberlist($param = array(),$startIndex) {
        $this->db->order_by('id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get('cm_user');

        return $query->result();
    }
	
	function get_member_total_num($param = array()) {

		return $this->db->count_all('cm_user');
	}

	private function _paramArr($param=array()) {
		
		return null;
	}
	
}