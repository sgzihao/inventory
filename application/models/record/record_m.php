<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Record_m extends CI_Model {

    public $num = null;
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getLogTotalNum($param = array()) {
        $this->db->select('count(1) as num');
        $this->db->order_by('id', 'desc');
        $this->db->from('user_log');
        /**
        if(!empty ($param['username']))
            $this->db->like('username', $param['username']);
        if(!empty ($param['useremail']))
            $this->db->like('email', $param['useremail']);
         *
         */

        $query = $this->db->get();

        $result = $query->row();
        return $result->num;
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function getLogList($startIndex,$param = array()) {
        $this->db->select('*');
        $this->db->order_by('id', 'desc');
        $this->db->from('user_log');
        /**
        if(!empty ($param['username']))
            $this->db->like('username', $param['username']);
        if(!empty ($param['useremail']))
            $this->db->like('email', $param['useremail']);
         *
         */
        if ($startIndex > -1)
            $this->db->limit(30, $startIndex);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
    /**
     *
     * @param type $data
     * @return type 
     */
    function insertNewRecordLog($data) {
        $this->db->insert('user_log', $data);
        $lastInsertID = $this->db->insert_id();
        return ($lastInsertID > 0) ?true:false;
    }
    
    /**
     *
     * @param type $userid
     * @param type $userData
     * @return type 
     */
    function updateUser($userid,$userData) {
        if($this->checkName($userData['username'], $userid)) {
            $this->message->set('Username already in Database, Please change it to another one.', 'error');
            return false;
        }
        
        $this->db->where('user_id', $userid);
        $this->db->update('user', $userData);
        return true;
    }
    /**
     *
     * @param type $userid
     * @return type 
     */
    function deleteuser($userid) {
        $this->db->trans_start();
        
        $this->db->delete('user', array('user_id' => $userid)); 
        
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}