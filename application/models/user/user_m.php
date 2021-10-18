<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class User_m extends CI_Model {

    public $num = null;
    public $_record = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getUserList($param = array()) {
        $this->db->order_by('user_id', 'desc');
        $this->db->from('user');
        if (!empty($param['username']))
            $this->db->like('username', $param['username']);
        if (!empty($param['useremail']))
            $this->db->like('email', $param['useremail']);
        $this->db->where('status !=', 'deleted');
        $query = $this->db->get();
        $this->num = $query->num_rows();
        return $query->result();
    }

    function addUser($username, $userData) {
        if ($this->checkName($username)) {
            $this->message->set('Username already in Database, Please change it to another one.', 'error');
            return false;
        }
        $this->db->insert('user', $userData);
        $lastInsertID = $this->db->insert_id();
        if ($lastInsertID > 0) {
            //record message
            $messageData = array();
            $messageData['userdata'] = $userData;

            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'user';
            $this->_record['target_id'] = $lastInsertID;

            $this->_record['title'] = "Add User";
            $this->_record['name'] = $username;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            return true;
        } else {
            $this->message->set('There is something wrong with the database, Please check with System Administrator.', 'error');
            return false;
        }
    }

    function updateUser($userid, $userData) {
        if ($this->checkName($userData['username'], $userid)) {
            $this->message->set('Username already in Database, Please change it to another one.', 'error');
            return false;
        }

        $this->db->where('user_id', $userid);
        $this->db->update('user', $userData);


        //record message
        $messageData = array();
        $messageData['userdata'] = $userData;

        $this->_record['user_id'] = $this->session->userdata('userid');
        $this->_record['user_name'] = $this->session->userdata('username');
        $this->_record['target'] = 'user';
        $this->_record['target_id'] = $userid;

        $this->_record['title'] = "Edit User";
        $this->_record['name'] = $userData['username'];
        $this->_record['message'] = serialize($messageData);
        $this->_record['date'] = date("Y-m-d H:i:s");
        $this->record->add($this->_record);

        return true;
    }

    /**
     *
     * @param type $userid
     * @return type 
     */
    function deleteuser($userid) {
        $userdata = $this->getUserById($userid);
        
        $this->db->trans_start();
        
        $this->db->delete('user', array('user_id' => $userid));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            //record message
            $messageData = array();
            
            $messageData['userdata'] = $userdata;

            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'user';
            $this->_record['target_id'] = $userid;

            $this->_record['title'] = "Delete User";
            $this->_record['name'] = $userdata->username;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            
            return true;
        }
    }

    /**
     *
     * @param type $userid
     * @return type 
     */
    function getUserById($userid) {
        $this->db->where('user_id = ', $userid);
        $query = $this->db->get('user');

        return $query->row();
    }

    /**
     *
     * @param type $username
     * @return type 
     */
    function getUserByName($username) {
        $this->db->where('username = ', $username);
        $this->db->where('status !=', 'deleted');
        $query = $this->db->get('user');
        return $query->row();
    }

    private function checkName($name, $userid=0) {
        $this->db->select(" user_id ");
        $this->db->from("user");

        $this->db->where("username = ", $name);
        if ($userid)
            $this->db->where('user_id !=', $userid);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true : false;
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getLocationList($param = array()) {
        $this->db->order_by('location_name', 'asc');
        $query = $this->db->get('location');

        return $query->result();
    }

}