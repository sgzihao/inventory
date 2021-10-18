<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Cardtype_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function insertCardtype($param=array()) {

        $this->db->set('ct_name', $param['cardtypename']);
        $this->db->set('discount', $param['discount']);
        $this->db->set('integral', $param['integral']);
        $this->db->set('times', $param['times']);
        $this->db->set('discountrate', $param['discountrate']);
        $this->db->set('integralrate', $param['integralrate']);
        $this->db->set('timesrate', $param['timesrate']);
		$this->db->set('ct_desc', $param['cardtype_desc']);
        $this->db->set('ct_create_time', date("Y-m-d H:i:s"));
        $this->db->set('ct_create_by', '1');
        
        $this->db->insert('cm_card_type');
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
    function getCardtypeList($param = array(),$startIndex) {
        $this->db->order_by('ct_id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get('cm_card_type');

        return $query->result();
    }
	
	function get_card_type_total_num($param = array()) {

		return $this->db->count_all('cm_card_type');
	}

	private function _paramArr($param=array()) {
		
		return null;
	}
	
}