<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class User extends CI_Model {

    public $users = 'users';
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function checkUser($user) {
        $this->db->where('username', $user);

        return ($this->db->count_all()>0)?true:false;
    }
    
    function getUser($user) {
        $this->db->where('username', $user);
        return $query->row_array();
    }
    
    function insert($user,$password) {

        $this->db->insert('users',  array('username'=>$user, 'password'=>  $password));
        return $this->db->insert_id();
    }
    
    function delete($param) {
        //delete from user where usename=?
    }
}