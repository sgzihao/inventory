<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Report_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getInventoryCategoryRel($param=array()) {
        $this->db->select('rel.*,vhd.c_id_100,vhd.c_id_102');
        $this->db->from('category_rel as rel');
        $this->db->join('inventory_ds_hardware_list as vhd', 'rel.inventory_id = vhd.inventory_id');
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    function overallConditionScores($param = array()) {
        //CONCAT_WS(',','First name','Second name','Last Name');
        $this->db->select("rel.*,vhd.c_id_100,vhd.c_id_102,CONCAT_WS( '-', trim(`category_id`) ,trim(`category_version`) ) AS a ");
        $this->db->from('category_rel as rel');
        $this->db->join('inventory_ds_hardware_list as vhd', 'rel.inventory_id 	 = vhd.inventory_id', 'left');
        $this->db->order_by('`rel`.`category_id` ASC , rel.`category_version` ASC');

        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    /**
     *
     * @return Inventory list 
     */
    function getInventoryList($param = array(), $startIndex) {

        $this->db->select('*');
        $this->db->from('inventory');
        $this->db->join('inventory_ds_hardware_list as vhd', 'inventory.inventory_id = vhd.inventory_id');
        $this->db->join('inventory_ds_software_list as vsw', 'inventory.inventory_id = vsw.inventory_id');

        if ($startIndex > -1)
            $this->db->limit(30, $startIndex);
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    function getProductsList($locationid=0) {
        //CONCAT_WS(',','First name','Second name','Last Name');
        //SELECT `category_id`, `category_version`, count( category_version ) as num 
        //FROM (`category_rel`) WHERE `flag` = 'yes' GROUP BY `category_id`, `category_version`
        //$this->db->select("ip.category_id, ip.category_name, rel.category_version, count(1) as num");
        $this->db->select("inventory.location_id,inventory.inventory_id,rel.*, count(1) as num");
        $this->db->from("inventory");
        $this->db->join("category_rel as rel", "rel.inventory_id= inventory.inventory_id", 'left');

        if ($locationid > 0)
            $this->db->where("inventory.location_id = ", $locationid);

        $this->db->where("flag = ", "yes");
        $this->db->group_by("`location_id`,`category_id` , `category_version`");
        $this->db->order_by("inventory.location_id,rel.inventory_id asc");
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    function productsList() {
        //CONCAT_WS(',','First name','Second name','Last Name');
        //SELECT `category_id`, `category_version`, count( category_version ) as num 
        //FROM (`category_rel`) WHERE `flag` = 'yes' GROUP BY `category_id`, `category_version`
        $this->db->select("*");
        $this->db->from("category_ipv6");
        $this->db->order_by("category_name asc");
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
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

    /**
     *
     * @param type $param
     * @return type 
     */
    function getIPV6minversion($param=array()) {
        //SELECT * FROM `category_ipv6`
        $this->db->order_by('category_name', 'asc');
        $query = $this->db->get('category_ipv6');

        return $query->result();
    }

    function getLocationNameById($locationId=0) {
        $this->db->select("*");
        if ($locationId > 0)
            $this->db->where('location_id', $locationId);
        $this->db->order_by("location_id asc");
        $query = $this->db->get('location');
        if ($locationId > 0)
            return $query->row();
        else
            return $query->result();
    }

    function invOSRelList($locationid=0) {
        //create VIEW inventory_os_list as SELECT server.inventory_id as os,server.cid_111 as kernel, server.cid_112 
        //FROM `inventory_server_hardware_list` AS server union all select vm.inventory_id,vm.cid_111 as os,vm.cid_112 as kernel 
        //from inventory_vm_hardware_list as vm;
        //inventory_model_category
        // SELECT server.cid_111, server.cid_112, count(1) as num FROM `inventory_server_hardware_list` AS server 
        // LEFT JOIN inventory AS inv ON inv.inventory_id = server.inventory_id WHERE inv.location_id =2 
        // group by server.cid_111, server.cid_112;
        //select os.os,os.kernel, count(1) as num from inventory_os_list as os left  join inventory as inv on inv.inventory_id=os.inventory_id 
        //where inv.location_id=2 group by os.os,os.kernel;
        $this->db->select("os.os,os.kernel, count(1) as num,inv.location_id");
        $this->db->from("inventory_os_list as os");
        $this->db->join("inventory as inv", "inv.inventory_id=os.inventory_id", 'left');

        if ($locationid > 0)
            $this->db->where("inv.location_id = ", $locationid);

        $this->db->group_by("os,kernel");
        $this->db->order_by("inv.location_id asc");
        //$this->db->order_by("inventory.location_id,rel.inventory_id asc");
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * @desc  Get Appls list from database view (report_appls_list)
     * 
     * @param type $locationid
     * @param type $param
     * @return type 
     */
    function getApplsList($locationid, $param = array()) {
        //sql
        //select appls.application,count(1) as num,group_concat(appls.inventory_id),group_concat(appls.os) as os 
        //from report_appls_list as appls left join inventory as inv on inv.inventory_id = appls.inventory_id 
        //where inv.location_id = 2 group by appls.application;

        $this->db->select("appls.application,count(1) as num,group_concat(appls.inventory_id) as invidlist,group_concat(appls.os) as os,group_concat(appls.brand) as brand");
        $this->db->from("report_appls_list as appls");
        $this->db->join("inventory as inv", "inv.inventory_id = appls.inventory_id", "left");
        $this->db->where("inv.location_id", $locationid);
        $this->db->group_by("appls.application");
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * @desc  Get category relation by inventory
     * 
     * @param type $locationid
     * @param type $param
     * @return type 
     */
    function getSoftwareRelList($locationid, $param = array()) {
        //select inv.inventory_id as invid, rel.* 
        //from inventory as inv 
        //left join category_rel as rel on rel.inventory_id = inv.inventory_id 
        //where inv.location_id = 2

        $this->db->select("inv.inventory_id as invid, rel.* ,cat.`category_name`");
        $this->db->from("inventory as inv");
        $this->db->join("category_rel as rel", "rel.inventory_id = inv.inventory_id", "left");
        $this->db->join("category_field AS cat", "cat.category_id = rel.category_id", "left");
        $this->db->where("inv.location_id", $locationid);
        $this->db->where("rel.category_id > ", "0");
        //$this->db->group_by("appls.application");
        $query = $this->db->get();

        return $query->result();
    }

    //* @desc appls list
    /**
     *
     * @param type $locationid
     * @param type $param
     * @return type 
     */
    function getApplsByOverall($locationid=0, $param = array()) {
        $this->db->select("*");
        $this->db->from("report_appls_list");
        $query = $this->db->get();
        return $query->result();
    }

    //@desc overall condition list report
    /**
     *
     * @param type $locationid
     * @param type $param
     * @return type 
     */
    function getOverallConditionList($locationid=0, $param = array()) {
        $this->db->select("overall.*");
        $this->db->from("report_overall_condition_list as overall");

        if ($locationid)
            $this->db->where("location_id", $locationid);
        else {
            $this->db->join("inventory as inv", "inv.inventory_id=overall.inventory_id", "inner");
        }
        $this->db->order_by("category_name,category_version asc");
        $query = $this->db->get();
        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getInv2CatScoreSourceList($param = array()) {
        $this->db->select("*");
        $this->db->from("report_score_list");

        $query = $this->db->get();

        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getInv2brandSourcesList($param = array()) {
        $this->db->select("brand");
        $this->db->from("report_appls_list");

        $this->db->group_by("brand");

        $query = $this->db->get();

        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getInv2osSourcesList($param = array()) {
        $this->db->select("os");
        $this->db->from("report_appls_list");

        $this->db->group_by("os");

        $query = $this->db->get();

        return $query->result();
    }

    /**
     *
     * @param type $configname
     * @param type $configvalue
     * @return type 
     * 
     */
    function systemConfiguration($configname, $configvalue) {
        //update the last component score list
        $sql = "replace `system_setting` (`category`, `value`) VALUES ('{$configname}', '{$configvalue}')";

        $rs = $this->db->simple_query($sql);

        return ($rs) ? true : false;
    }

    /**
     *
     * @param type $sql
     * @return type 
     */
    function replaceComponentScore($table, $data) {
        
        $this->db->replace_into($table, $data);
        
        //var_dump($rs) ;
    }
    
    /**
     *
     * @param type $param
     * @return type 
     */
    function getComponentsScoreListTable($param = array()) {
        
        //SELECT * FROM `components_score`
        $this->db->select("*");
        $this->db->from("components_score");

        $query = $this->db->get();

        return $query->result();
    }
}