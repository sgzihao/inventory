<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Location_m extends CI_Model {

    public $num = null;
    protected $_record = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function addNewOffice($param = array()) {
        $officeName = $param['gsofficename'];

        if ($this->checkOfficeName($officeName)) {
            $this->message->set('Office Name has already existed, Please check it.', 'error');
            return false;
        }

        $officeData = array('location_name' => $officeName, 'long_description' => $param['gsofficedesc']);
        $officeData['created_by']      = $this->session->userdata('username');
        $officeData['creation_date']    = date("Y-m-d H:i:s");
        
        $this->db->insert('location', $officeData);
        $lastInsertID = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            //record message
            $messageData = array();
            $messageData['location'] = $officeData;

            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'location';
            $this->_record['target_id'] = $lastInsertID;

            $this->_record['title'] = "Add Location";
            $this->_record['name'] = $officeName;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            return true;
        } else {
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        }
    }

    function updateOffice($id, $param=array()) {
        $officeName = $param['gsofficename'];

        if ($this->checkOfficeName($officeName, $id)) {
            $this->message->set('Office Name has already existed, Please use another one.', 'error');
            return false;
        }


        $officeData = array('location_name' => $officeName, 'long_description' => $param['gsofficedesc']);
        $officeData['modified_by']      = $this->session->userdata('username');
        //$officeData['modified_date']    = date("Y-m-d H:i:s");
                
        $this->db->where('location_id', $id);
        $this->db->update('location', $officeData);

        $messageData = array();
        $messageData['location'] = $officeData;

        $this->_record['user_id'] = $this->session->userdata('userid');
        $this->_record['user_name'] = $this->session->userdata('username');
        $this->_record['target'] = 'location';
        $this->_record['target_id'] = $id;

        $this->_record['title'] = "Edit Location";
        $this->_record['name'] = $officeName;
        $this->_record['message'] = serialize($messageData);
        $this->_record['date'] = date("Y-m-d H:i:s");
        $this->record->add($this->_record);
        return true;
    }

    /**
     *
     * @param type $id
     * @return type 
     */
    function getLocationById($id) {
        $this->db->where('location_id = ', $id);
        $query = $this->db->get('location');

        return $query->row();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getOfficeList($param = array()) {
        $this->db->order_by('location_name', 'asc');

        if (!empty($param['location_name']))
            $this->db->like("location_name", $param['location_name']);
        $query = $this->db->get('location');
        $this->num = $query->num_rows();
        return $query->result();
    }

    /**
     *
     * @param type $officeName 
     */
    function checkOfficeName($officeName, $id=0) {
        $this->db->select("location_id");
        $this->db->from("location");
        $this->db->where("location_name = ", $officeName);
        if ($id)
            $this->db->where('location_id !=', $id);
        $query = $this->db->get();
        $row = $query->num_rows();
        if ($row > 0)
            return true;
        else
            return false;
    }

    function invtotalNumberBylocation($locationid) {
        $this->db->select("count(1) as num");
        $this->db->from("inventory");
        $this->db->where("location_id = ", $locationid);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     *
     * @param type $id
     * @return type 
     */
    function deletelocation($id) {
        $this->db->trans_start();
        $locationData = $this->getLocationById($id);
        $this->db->delete('location', array('location_id' => $id));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            
            //record message
            $messageData = array();
            $messageData['location'] = $locationData;

            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'location';
            $this->_record['target_id'] = $id;

            $this->_record['title'] = "Delete Location";
            $this->_record['name'] = $locationData->location_name;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            
            return true;
        }
    }

}