<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Makecard_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function insertMakeCard($param=array()) {
		$data = array();
		for($i = $param['cardstartno'];$i<=$param['cardendno'];$i++) {
			$data[] = array(
					'card_no' => $i,
					'card_type' => $param['cardtype'],
					'card_face_value' => $param['cardfacevalue'],
					'card_status' => 1,
					'track_1_data' => md5(time()),
					'track_2_data' => md5(time()),
					'track_3_data' => md5(time()),
					'make_time' => $param['maketime'],
					'start_time' => $param['starttime'],
					'warranty' => $param['warranty'],
					'made_by' => $param['madeby'],
					'made_desc' => $param['madedesc'],
					'password' => $param['cardpassword']
			);
		}

        $this->db->insert_batch('cm_make_card',$data);
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
        $this->db->update('cm_make_card',$data);
        return true;
    }
	/**
     *
     * @param type $param
     * @return type 
     */
    function getMakecardList($param = array(),$startIndex) {
        $this->db->order_by('make_id','desc');
		$this->db->limit(30,$startIndex);
        $query = $this->db->get('cm_make_card');

        return $query->result();
    }
	
	function get_make_card_total_num($param = array()) {

		return $this->db->count_all('cm_make_card');
	}
	 /**
     *
     * @param type $param
     * @return type 
     */
    function getCardtypeList($param = array()) {
        $this->db->order_by('ct_id','desc');
        $query = $this->db->get('cm_card_type');

        return $query->result();
    }
	private function _paramArr($param=array()) {
		
		return null;
	}
	
}