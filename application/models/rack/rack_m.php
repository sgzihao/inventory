<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Rack_m extends CI_Model {

    protected $_record = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	function uploadFile($param = array())
	{
		$this->db->insert('rack_upload_list', $param);
        
        if ($this->db->affected_rows() > 0) {
			return true;
		} else {
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        }
		
	}
	
	function getUploadFileList ($param = array()) 
	{
		$this->db->order_by('id', 'desc');
		$this->db->limit(30, 0);
        $query = $this->db->get('rack_upload_list');
		
        return $query->result();
	}

}