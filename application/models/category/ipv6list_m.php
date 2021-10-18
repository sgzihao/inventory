<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Ipv6list_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getIpv6VersionList($param = array()) {
        $this->db->select("*");
        $this->db->from("category_ipv6");
        $this->db->group_by('category_name asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getProductsList($param=array()) {
        //CONCAT_WS(',','First name','Second name','Last Name');
        //select * from `inventory_category_rel` where flag='yes'
        //select * from category_field as cf left join 
        /* SELECT `category_id` , `category_version` , count( category_version )
          FROM `inventory_category_rel`
          WHERE `flag` = 'yes'
          GROUP BY `category_id` , `category_version`
         */
        $this->db->select("`category_id` , `category_version` , count( category_version ) as num");
        $this->db->from("inventory_category_rel");
        $this->db->where("flag = ", "yes");
        $this->db->group_by("`category_id` , `category_version`");
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }

}