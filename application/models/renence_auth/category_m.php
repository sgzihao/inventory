<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Category_m extends CI_Model {

    private $tablename = 'users';

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $param array
     * @param type $startIndex
     * @return type 
     */
    function getCategoryList($param = array(), $startIndex) {
        $this->db->where('category_parent_id != ', '0');
        $this->db->order_by('category_id', 'desc');
        $this->db->limit(30, $startIndex);
        $query = $this->db->get('category_field');

        return $query->result();
    }

    function getCategoryTotalNum($param = array()) {
        $this->db->where('category_parent_id != ', '0');
        $this->db->from('category_field');
        return $this->db->count_all_results();
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
            'p_name' => $param['pname'],
            'p_en_name' => $param['penname'],
            'p_street' => $param['pstreet'],
            'p_city' => $param['pcity'],
            'p_state' => $param['pstate'],
            'p_country' => $param['pcountry'],
            'p_postcode' => $param['ppostcode'],
            'p_contact' => $param['pcontact'],
            'p_phone' => $param['pphone'],
            'p_cellphone' => $param['pcellphone'],
            'p_email' => $param['pemail'],
            'p_domain' => $param['pdomain'],
            'p_ip' => $param['pip'],
            'p_modify_time' => time()
        );
        $this->db->where('p_id', $param['pid']);
        $this->db->update('cm_provider', $data);
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

    function addNewCategory() {

        $this->category_name = $_POST['categoryname'];
        $this->category_parent_id = $_POST['topcategory'];
        $this->category_type_id = $_POST['fieldtype'];
        $this->active_flag = isset($_POST['categoryenabled']) ? 1 : 0;
        $this->required_flag = isset($_POST['categoryrequired']) ? 1 : 0;

        $this->db->insert('category_field', $this);
    }

    /**
     * Get the top category list from table `category_field`
     */
    function getParentCategoryList($param = array()) {
        $parentid = 0;
        if (isset($param['parentid'])) {
            $parentid = $param['parentid'];
        }
        $sql = "select * from category_field where category_parent_id = {$parentid}  ";
        $query = $this->db->query($sql);
        return $query->result();
    }

}