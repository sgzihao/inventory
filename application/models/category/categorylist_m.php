<?php

defined('BASEPATH') OR exit('No direct script access allowed');
      
/**
 * 
 */
class Categorylist_m extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
   
	function getcategorylistinfo () {   
		$this->db->order_by('category_name','desc');
        $query = $this->db->get('category_field');

		$result = $query->result();
		return $result;
		//return $this->print_list($subid[0],$subid,$name);
	}

	function print_list($parents,$subids,$name){    
        $list = '<ul>';    
        foreach ($parents as $parent){    
             $list.= "<li>".$name[$parent];    
             $list.= "<a href=\"\">[new]</a>\n";    
             $list.= "<a href=\"\">[del]</a>\n";    
             $list.= "<a href=\"\">[find]</a></li>\n";    
             if(array_key_exists($parent,$subids)){    
				$sublist=$this->print_list($subids[$parent],$subids,$name);    
				$list.=$sublist;    
             }    
        }    
         $list.= "</ul>\n";    
         return $list;    
     }
}