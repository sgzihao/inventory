<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Category_m extends CI_Model {

    protected $_record = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getCategoryList($param = array(), $startIndex=0) {
        $this->db->where('category_parent_id != ', '0');
        if (!empty($param['category_name']))
            $this->db->like('category_name', $param['category_name']);
        if (isset($param['topcategory']) && $param['topcategory'] > 0)
            $this->db->where('category_parent_id = ', $param['topcategory']);
        $this->db->order_by('category_name', 'asc');
        $this->db->limit(30, $startIndex);
        $query = $this->db->get('category_field');

        return $query->result();
    }

    function getCategoryTotalNum($param = array()) {
        $this->db->where('category_parent_id != ', '0');
        if (!empty($param['category_name']))
            $this->db->where('category_name = ', $param['category_name']);
        if (isset($param['topcategory']) && $param['topcategory'] > 0)
            $this->db->where('category_parent_id = ', $param['topcategory']);

        $this->db->from('category_field');
        return $this->db->count_all_results();
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
        if ($this->checkCategoryName($_POST['categoryname'])) {
            $this->message->set('Category Name already in Database, Please change it to another one.', 'error');
            return false;
        }

        $this->category_name = $_POST['categoryname'];
        $this->category_parent_id = $_POST['topcategory'];
        //$this->category_type_id = $_POST['fieldtype'];
        $this->active_flag = isset($_POST['categoryenabled']) ? 1 : 0;
        $this->required_flag = isset($_POST['categoryrequired']) ? 1 : 0;
        $this->created_by = $this->session->userdata('username');
        $this->creation_date = date("Y-m-d H:i:s");

        $this->db->insert('category_field', $this);
        
        $lastInsertID = $this->db->insert_id();
        
        //put it into category_ipv6category_name 	ipv6_min_version 	parent_category 	flag
        $arrIpv6 = array ('category_name'=>$this->category_name,'category_id' => $lastInsertID);
        $this->replaceCategoryIpv6('category_ipv6',  $arrIpv6);
        
        //record message
        $messageData = array();
        $messageData['category'] = $this;
        //record message
        $this->_record['user_id'] = $this->session->userdata('userid');
        $this->_record['user_name'] = $this->session->userdata('username');
        $this->_record['target'] = 'cateogory';
        $this->_record['target_id'] = $lastInsertID;

        $this->_record['title'] = "Add Category";
        $this->_record['name'] = $_POST['categoryname'];
        $this->_record['message'] = serialize($messageData);
        $this->_record['date'] = date("Y-m-d H:i:s");
        $this->record->add($this->_record);
        return;
        //return 
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function updateCategory($param) {

        if (!isset($param['categoryid'])) {
            $this->message->set('There is something wrong with currently request.', 'error');
            return false;
        }

        if ($this->checkCategoryName($param['categoryname'], $param['categoryid'])) {
            $this->message->set('Category Name already in Database, Please change it to another one.', 'error');
            return false;
        }
        $data = array(
            'category_name' => $param['categoryname']
        );
        $data['modified_by'] = $this->session->userdata('username');
        $data['modified_date'] = date("Y-m-d H:i:s");

        $this->db->where('category_id', $param['categoryid']);
        //$this->db->update('category_field', $data);
        //
        
        try {
            $this->db->update('category_field', $data);
            //record message
            $arrIpv6 = array ('category_name'=>$param['categoryname'],'category_id' => $param['categoryid']);
            $this->replaceCategoryIpv6('category_ipv6',  $arrIpv6);
            
            $messageData = array();
            $messageData['category'] = $data;
            //record message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'cateogory';
            $this->_record['target_id'] = $param['categoryid'];

            $this->_record['title'] = "Edit Category";
            $this->_record['name'] = $_POST['categoryname'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            return true;
        } catch (Exception $exc) {
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        }
    }

    function addNewOption($param) {
        if (!isset($param['categoryid'])) {
            $this->message->set('There is something wrong with currently request.', 'error');
            return false;
        }

        if ($this->checkCategoryOptionvalue($param['categoryid'], $param['categoryoption'])) {
            $this->message->set('Option already in Database, Please change it to another one.', 'error');
            return false;
        }
        $data = array(
            'category_field_id' => $param['categoryid'],
            'category_field_value' => $param['categoryoption'],
            'created_by' => $this->session->userdata('username'),
            'creation_date' => date("Y-m-d H:i:s")
        );
        //$this->db->insert('mytable', $data); 
        $this->db->insert('category_f_value', $data);
        $lastInsertID = $this->db->insert_id();
        if ($lastInsertID) {
            //record message
            $messageData = array();
            $messageData['option'] = $data;
            //record message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'cateogory option';
            $this->_record['target_id'] = $lastInsertID;

            $this->_record['title'] = "Add Category Option";
            $this->_record['name'] = $_POST['categoryoption'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            
            return true;
        } else {
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        }
    }

    /**
     *
     * @param type $id
     * @param type $value
     * @return type 
     */
    function updateOptionValue($id, $value) {
        $data = array(
            'category_field_value' => $value,
            'modified_by' => $this->session->userdata('username')
        );
        $this->db->where('category_field_value_id', $id);
        //$this->db->update('category_field', $data);
        try {
            $this->db->update('category_f_value', $data);
            //record message
            $messageData = array();
            $messageData['option'] = $data;
            //record message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'cateogory option';
            $this->_record['target_id'] = $id;

            $this->_record['title'] = "Edit Category Option";
            $this->_record['name'] = $value;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    /**
     *
     * @param type $id
     * @param type $option
     * @return type 
     */
    function delOption($id, $option) {
        $rs = $this->getInvCatOptionRel($id, $option);
        if($rs)
            return $rs;
        try {
            //
            $this->db->delete('category_f_value', array('category_field_id' => $id,'category_field_value' => $option));
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }
    function getCategoryById($categoryId, $param = array()) {

        $this->db->where('category_id = ', $categoryId);
        $query = $this->db->get('category_field');

        return $query->row();
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

    function getCategoryVersionlist($categoryid, $param = array()) {
        $this->db->where('category_field_id  = ', $categoryid);
        $query = $this->db->get('category_f_value');
        return $query->result();
    }

    private function checkCategoryName($categoryName, $categoryID=null) {
        $this->db->select(" category_id ");
        $this->db->from("category_field");

        if (isset($categoryID))
            $this->db->where("category_id != ", $categoryID);

        $this->db->where("category_name =", $categoryName);

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true : false;
    }

    /**
     *
     * @param type $categoryID
     * @param type $optionName
     * @return type 
     */
    private function checkCategoryOptionvalue($categoryID, $optionName) {
        $this->db->select(" category_field_value_id ");
        $this->db->from("category_f_value");

        $this->db->where("category_field_id  = ", $categoryID);

        $this->db->where("category_field_value =", $optionName);

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true : false;
    }

    function getCategoryIpv6Info($categoryID, $param=array()) {
        $this->db->where('category_id = ', $categoryID);
        $query = $this->db->get('category_ipv6');

        return $query->row();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function updateIpv6($param) {
        $data = array(
            'ipv6_min_version' => $param['ipv6_min_version'],
            'parent_category' => $param['parent_category'],
            'flag' => $param['flag']
        );

        $this->db->where('category_id', $param['categoryid']);
        //$this->db->update('category_field', $data);
        try {
            $this->db->update('category_ipv6', $data);
            return true;
        } catch (Exception $exc) {
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        }
    }
    /**
     *
     * @param type $cid
     * @param type $option
     * @return type 
     */
    private function getInvCatOptionRel($cid, $option) {
        $this->db->select(" inv.inventory_id ");
        $this->db->from("inventory as inv");
        
        $this->db->join("category_rel as rel", "inv.inventory_id = rel.inventory_id", 'left');
        $this->db->where('rel.category_id = ', $cid);
        $this->db->where('rel.category_version = ', $option);
        $query = $this->db->get();
        //var_dump($query->row());
        return ($query->num_rows() > 0) ? $query->num_rows() : false;
    }
    
    /**
     *
     * @param type $sql
     * @return type 
     */
    private function replaceCategoryIpv6($table, $data) {
        
        $this->db->replace_into($table, $data);
        
        //var_dump($rs) ;
    }

}