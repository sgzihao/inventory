<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Provider_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_profile($params = array()) {
        $query = $this->db->get_where('cm_provider', $params);

        return $query->row();
    }
    /**
     *
     * @param type $param
     * @return boolean
     */
    function update_provider($param = array()) {
        
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
     * @return type 
     */
    function get_last_ten_entries() {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry() {
        $this->title = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date = time();

        $this->db->insert('entries', $this);
    }

}