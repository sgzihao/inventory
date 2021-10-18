<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Corp_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_corp($params = array()) {
        $query = $this->db->get_where('cm_provider', $params)
                    ->order_by('cp_id',desc);
        
        return $query->row();
    }
    /**
     *
     * @param type $param
     * @return type 
     */
    function insert_corp($param=array()) {
        $this->db->set('cp_name', $param['cp_name']);
        $this->db->set('cp_en_name', $param['cp_en_name']);
        $this->db->set('cp_business_license', $param['cp_business_license']);
        $this->db->set('cp_address', $param['cp_address']);
        $this->db->set('cp_address2', $param['cp_address2']);
        $this->db->set('cp_city', $param['cp_city']);
        $this->db->set('cp_province', $param['cp_province']);
        $this->db->set('cp_country', $param['cp_country']);
        $this->db->set('cp_postcode', $param['cp_postcode']);
        $this->db->set('cp_phone', $param['cp_phone']);
        $this->db->set('cp_cellphone', $param['cp_cellphone']);
        $this->db->set('cp_fax', $param['cp_fax']);
        $this->db->set('cp_email', $param['cp_email']);
        $this->db->set('cp_create_time', time());
        $this->db->set('cp_notes', $param['cp_notes']);
        
        $this->db->insert('cm_corp');
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
    function get_corp_list($param = array(),$startIndex) {
        $this->db->order_by('cp_id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get('cm_corp');

        return $query->result();
    }
	
	function get_corp_total_num($param = array()) {

		return $this->db->count_all('cm_corp');
	}

}