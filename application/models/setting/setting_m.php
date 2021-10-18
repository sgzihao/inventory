<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Setting_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getUserList($param = array()) {
        $this->db->order_by('user_id','desc');
        $query = $this->db->get('user');

        return $query->result();
    }
	
	/**
     *
     * @param type $param
     * @return type 
     */
    function getLocationList($param = array()) {
        $this->db->order_by('location_name','asc');
        $query = $this->db->get('location');

        return $query->result();
    }

}