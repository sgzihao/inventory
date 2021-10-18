<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Admin_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $id
     * @return type 
     */
    function getInventoryMenuList($param = array()) {
        //select count(1) as invcount, inv.inventory_model_id, inv.location_id,model.inventory_model_name, ll.location_name  
        //from inventory as inv 
        //left join inventory_model as model on model.inventory_model_id = inv.inventory_model_id 
        //left join location as ll on ll.location_id =inv.location_id  
        //group by inventory_model_id, location_id 
        //order by ll.location_name;

        $this->db->select("count(1) as invcount, inv.inventory_model_id, inv.location_id,model.inventory_model_name, ll.location_name ");
        $this->db->from(' inventory as inv');
        $this->db->join("inventory_model as model", "model.inventory_model_id = inv.inventory_model_id ");
        $this->db->join("location as ll", "ll.location_id =inv.location_id ");
        $this->db->group_by('inventory_model_id,location_id');
        $this->db->order_by('ll.location_name');
        $query = $this->db->get();

        return $query->result();
    }

    function getLocationList($param = array()) {
        $this->db->order_by('location_name', 'asc');
        $query = $this->db->get('location');

        return $query->result();
    }

    function getInventoryModelList($param = array()) {
        $this->db->order_by('inventory_model_name', 'asc');
        $query = $this->db->get('inventory_model');

        return $query->result();
    }
    
    function locationList($locationList, $userLocation) {
        $officeArr = "<select name='locationid'>";
        $officeArr .= "<option>Please select Location</option>";
        $locationArr = $this->model_mm->getLocationList();
        //var_dump($locationArr);
        foreach ($locationArr as $row) {
            //get the location list from user acccount, currently just display all of them to test.
            if (!in_array($row->location_id, $userLocation))
                continue;
            $selected = ($row->location_id == $locationList) ? "selected" : "";

            $officeArr .= "<option value='{$row->location_id}' {$selected}>{$row->location_name}</option>";
        }
        $officeArr .= "</select>";
        return $officeArr;
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function getSystemSetting($param = array()) {
        
        $query = $this->db->get('`system_setting`');

        return $query->result();
    }
}