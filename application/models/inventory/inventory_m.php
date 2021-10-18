<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Inventory_m extends CI_Model {

    public $_record = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function addNewItem($modelTable, $locationid, $invmodel, $hardData = array(), $softData = array()) {
        
        //uniqure inventory name 
        if($this->checkUniqueInventoryName($hardData['cid_101'], $invmodel, $locationid))
            return 0;
        
        //
        $this->db->trans_start();
        $invData = array();
        $invData['inventory_model_id'] = $invmodel;
        $invData['location_id'] = $locationid;
        $invData['inventory_name'] = $hardData['cid_101'];
        $invData['created_by'] = $this->session->userdata('username');
        $invData['creation_date'] = date("Y-m-d H:i:s");
        //
        $this->db->insert('inventory', $invData);
        $lastInsertInvID = $this->db->insert_id();

        $hardData['inventory_id'] = $lastInsertInvID;
        $this->db->insert($modelTable, $hardData);

        //if(count($softData) > 1)
        $softwaredata = array();
        if (!empty($softData)) {
            foreach ($softData as $sk => $sitem) {
                foreach ($sitem as $uitem) {
                    $softwaredata = array(
                        'inventory_id' => $lastInsertInvID,
                        'category_id' => $sk,
                        'category_version' => $uitem,
                        'flag' => 'yes'
                    );
                    $this->db->insert('category_rel', $softwaredata);
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        } else {
            $this->db->trans_commit();
            //record log message
            $messageData = array();
            $messageData['inventory'] = $invData;
            $messageData['software'] = $softwaredata;
            $messageData['hardData'] = $hardData;
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $lastInsertInvID;

            $this->_record['title'] = "Add Inventory";
            $this->_record['name'] = $hardData['cid_101'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            //update primary services
            $this->updatePrimaryServices($lastInsertInvID);
            return $lastInsertInvID;
        }
    }

    /**
     *
     * @return Inventory list 
     */
    function getInventoryList($tableName, $locationid, $param = array(), $startIndex=0) {
        //var_dump($param);
        $this->db->select('*');
        $this->db->from('inventory');
        $this->db->join("{$tableName} as vhd", 'inventory.inventory_id = vhd.inventory_id');
		
		//filted by inventory's status
		if (isset($param['inv_status'])) {
			if($param['inv_status'] != 'all')
				$this->db->where("inventory.status = ", $param['inv_status']);
        } else {
			$this->db->where("inventory.status = ", 'active');
		}
		
        if (isset($locationid)) {
            $this->db->where("inventory.location_id = ", $locationid);
        }
		
		$defaultSortByName = "inventory.inventory_name ";
        if (!empty($param['cfv'])) {
            //Maybe some issue in the future, some of the inventory model don't have primary service.or cid_113 deleted.
            if( trim($param['cf'])== "cid_113") {
                $this->db->like("inventory.primary_services", trim($param['cfv']));
			}
            else {
                $this->db->like("vhd.".trim($param['cf']), trim($param['cfv']));
				$defaultSortByName = "vhd.".trim($param['cf']);
			}
        }
		
		
		//order by select category asc or desc 
		$defaultSortOrderBy = "asc";
		if (isset($param['sortOrder']) && $param['sortOrder'] == "desc") {
			$defaultSortOrderBy = "desc";
		}
		
        $this->db->order_by($defaultSortByName ." " . $defaultSortOrderBy);
        if ($startIndex > -1)
            $this->db->limit(30, $startIndex);

        $query = $this->db->get();
        //$this->num = $query->num_rows();
        //$query = $this->db->query($sql);
        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getInventoryTotalNumber($tableName, $locationid, $param = array()) {
        $this->db->select('count(1) as num');
        $this->db->from('inventory');
        $this->db->join("{$tableName} as vhd", 'inventory.inventory_id = vhd.inventory_id');
		
		//filted by inventory's status
		if (isset($param['inv_status'])) {
			if($param['inv_status'] != 'all')
				$this->db->where("inventory.status = ", $param['inv_status']);
        } else {
			$this->db->where("inventory.status = ", 'active');
		}
		
        if (isset($locationid)) {
            $this->db->where("inventory.location_id = ", $locationid);
        }
        if (!empty($param['cfv'])) {
            $this->db->like("vhd.".trim($param['cf']), trim($param['cfv']));
        }

        $query = $this->db->get();

        $result = $query->row();
        return $result->num;
    }

    /**
     *
     * @return Inventory list 
     */
    function getInventoryListByCal($modelID, $locationid, $tableName, $softwareName='',$version='') {
        if($softwareName == "")
            return array();
		
        $this->db->select(" * ");
        $this->db->from('category_inv_view');
		
	$this->db->join("{$tableName} as vhd", 'category_inv_view.inventory_id = vhd.inventory_id');
        
	$this->db->where("category_inv_view.status = ", "active");
	
        if (isset($modelID)) {
            $this->db->where("inventory_model_id  = ", $modelID);
        }
        if (isset($locationid)) {
            $this->db->where("location_id = ", $locationid);
        }
        $this->db->where("category_id  = ", $softwareName);
        
	####
	if (!empty($_REQUEST['cf1value'])) {
            //Maybe some issue in the future.
            $this->db->like("vhd.".trim($_REQUEST['cf1']), trim($_REQUEST['cf1value']));
        }
	####
		
        if (!empty($version)) {
            $this->db->like("category_version", trim($version));
        }
		
        $query = $this->db->get();
        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getInventoryTotalNumberByCal($modelID, $locationid, $tableName,$category='',$version='') {
        //
        //SELECT rel . * , inv.inventory_name, inv.location_id, 
        //concat( trim( cal.category_name ) , '-', trim( rel.category_version ) ) AS category, 
        //trim( cal.category_name ) AS category_name
        //FROM category_rel AS rel
        //LEFT JOIN category_field AS cal ON cal.category_id = rel.category_id
        //LEFT JOIN inventory AS inv ON inv.inventory_id = rel.innv.inventventory_id
        if($category == "")
            return 0;
        
        $this->db->select('count(1) as num');
		
        $this->db->from('category_inv_view');
		
        $this->db->join("{$tableName} as vhd", 'category_inv_view.inventory_id = vhd.inventory_id');

	$this->db->where("category_inv_view.status = ", "active");	
	
        if (isset($modelID)) {
            $this->db->where("inventory_model_id  = ", $modelID);
        }
        if (isset($locationid)) {
            $this->db->where("location_id = ", $locationid);
        }
        $this->db->where("category_id  = ", $category);
        
		####
		if (!empty($_REQUEST['cf1value'])) {
            //Maybe some issue in the future.
            $this->db->like("vhd.".trim($_REQUEST['cf1']), trim($_REQUEST['cf1value']));
        }
		####
		
        if (!empty($version)) {
            $this->db->like("category_version", trim($version));
        }
        
        $query = $this->db->get();
        //$this->num = $query->num_rows();
        //$query = $this->db->query($sql);
        $result = $query->row();
        return $result->num;
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

    function getInventoryModelList($param = array()) {
        $this->db->order_by('inventory_model_name', 'asc');
        $query = $this->db->get('inventory_model');

        return $query->result();
    }

    /* @desc	Model Category list
     * @param type $param
     * @return type 
     */

    function getModelCategoryRel($modelID, $inventoryModelID) {
        $sql = "SELECT inv_cat . * , cat.category_name
		FROM `inventory_model_category` AS inv_cat
		LEFT JOIN `category_field` AS cat ON inv_cat.category_id = cat.category_id
		WHERE inv_cat.inventory_model_id = {$inventoryModelID} and cat.category_parent_id = {$modelID} 
                order by inv_cat.position,cat.category_id asc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function categoryVersionlist($categoryid, $param = array()) {
        $this->db->where('category_field_id  = ', $categoryid);
        $query = $this->db->get('category_f_value');
        return $query->result();
    }

    /**
     *
     * @param type $inventoryID
     * @param type $param
     * @return type 
     */
    function getInventoryInfo($inventoryID, $tableName, $param=array()) {
        $this->db->select('*');
        $this->db->from('inventory');
        $this->db->join("{$tableName} as vhd", "inventory.inventory_id = vhd.inventory_id", 'left');
        $this->db->where('inventory.inventory_id = ', $inventoryID);
        $query = $this->db->get();
        //var_dump($query->row());
        return $query->row();
    }

    /**
     *
     * @param type $inventoryID
     * @param type $param
     * @return type 
     */
    function getInventoryCatRelInfo($inventoryID, $param=array()) {
        $this->db->select('*');
        $this->db->from('category_rel');
        $this->db->where('inventory_id = ', $inventoryID);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     *
     * @param type $id
     * @return type 
     */
    function getModelTableById($id) {
        $this->db->select('*');
        $this->db->from('inventory_model');
        $this->db->where('inventory_model_id = ', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function getModelRelCategoryList($modelId, $categoryParentId) {
        //SELECT imc.*, cf.category_name
        //FROM `inventory_model_category` as imc
        //left join category_field as cf on cf.category_id = imc.category_id
        //where cf.category_parent_id = 1
        $this->db->select('imc.*, cf.category_name');
        $this->db->from('`inventory_model_category` as imc');
        $this->db->join("category_field as cf", "cf.category_id = imc.category_id");

        $this->db->where('imc.inventory_model_id = ', $modelId);
        $this->db->where('cf.category_parent_id = ', $categoryParentId);
        $this->db->order_by('imc.position asc');
        $this->db->order_by('cf.category_id asc');
        $query = $this->db->get();

        return $query->result();
    }

    function getLocationNameById($locationId) {
        $this->db->select("*");
        $this->db->where('location_id', $locationId);
        $query = $this->db->get('location');

        return $query->row();
    }
    
    function batchUpdateCalVersion($cid, $invid, $version) {
        
        $invidlist = explode("," ,$invid);
        foreach ($invidlist as $invItem) {
            if(empty ($invItem))
                continue;
            
            $data = array ();
            $data['category_version'] = trim($version);
            //die(var_dump($data));
            $this->db->where('inventory_id', trim($invItem));
            $this->db->where('category_id', trim($cid));
            $this->db->update('category_rel', array('category_version' => trim($version))); 
            
            //update primary services
            $this->updatePrimaryServices($invItem);
            //die(var_dump($this->db->trans_status()));
        }
        return true;
        
    }
    function updateItem($inventoryId, $modelTable, $locationid, $invmodel, $hardData, $softData) {
        //uniqure inventory name 
        if($this->checkUniqueInventoryName($hardData['cid_101'], $invmodel, $locationid,$inventoryId))
            return 0;
        
        //
        
        $this->db->trans_start();
        $invData = array();
        //$invData['inventory_id'] = $inventoryId;
        $invData['inventory_model_id'] = $invmodel;
        $invData['location_id'] = $locationid;
        $invData['modified_by'] = $this->session->userdata('username');
        $invData['modified_date'] = date("Y-m-d H:i:s");
        $invData['inventory_name'] = $hardData['cid_101'];
        $this->db->where('inventory_id', $inventoryId);
        $this->db->update('inventory', $invData);

        //
        $this->db->where('inventory_id', $inventoryId);
        $this->db->update($modelTable, $hardData);

        //delete category relation
        $this->db->delete('category_rel', array('inventory_id' => $inventoryId));
        //if(count($softData) > 1)
        if (!empty($softData)) {
            $softwaredata = array();

            foreach ($softData as $sk => $sitem) {
                foreach ($sitem as $uitem) {
                    $softwaredata = array(
                        'inventory_id' => $inventoryId,
                        'category_id' => $sk,
                        'category_version' => $uitem,
                        'flag' => 'yes'
                    );
                    $this->db->insert('category_rel', $softwaredata);
                }
            }
        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return -1;
        } else {
            $this->db->trans_commit();

            //record log message
            $messageData = array();
            $messageData['inventory'] = $invData;
            $messageData['software'] = isset($softwaredata) ? $softwaredata : '';
            $messageData['hardData'] = $hardData;
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $inventoryId;

            $this->_record['title'] = "Edit Inventory";
            $this->_record['name'] = $hardData['cid_101'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            //update primary services
            $this->updatePrimaryServices($inventoryId);
            return $inventoryId;
        }
    }

	/**
     *
     * @param type $inventoryId
     * @param type $modelTableName
     * @return boolean 
     */
    function delInventory($inventoryId, $modelTableName) {
		$this->db->select('*');
        $this->db->from('inventory');
        $this->db->where('inventory_id = ', $inventoryId);
        $query = $this->db->get();
		$rs = $query->row();
		$invName = $rs->inventory_name;
		
		if($rs->status !='active')
			return false;
			
		$this->db->trans_start();
        $this->db->where('inventory_id', $inventoryId);
        $this->db->update('inventory', array('status' => 'deleted'));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
			
			//record log message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $inventoryId;

            $this->_record['title'] = "Delete Inventory {$invName} ID {$inventoryId}";
            $this->_record['name'] = $invName;
            $this->_record['message'] = "Delete {$invName} ID {$inventoryId}";
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
			
            return true;
        }
		
    }

    function delInventoryFromDB($inventoryId, $modelTableName) {
        $this->db->select('*');
        $this->db->from('inventory');
        $this->db->where('inventory_id = ', $inventoryId);
        $query = $this->db->get();
        $rs = $query->row();
        $invName = $rs->inventory_name;

        if($rs->status !='deleted')
        	return false;

        $this->db->trans_start();
	$this->db->delete("inventory", array("inventory_id" => $inventoryId));
	
	$this->db->where('inventory_id', $inventoryId);
        $this->db->delete($modelTableName);

        //delete category relation
        $this->db->delete('category_rel', array('inventory_id' => $inventoryId));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            //record log message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $inventoryId;

            $this->_record['title'] = "Remove Inventory {$invName} ID {$inventoryId} from Database";
            $this->_record['name'] = $invName;
            $this->_record['message'] = "Remove {$invName} ID {$inventoryId} from Database";
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            return true;
        }

    }

	
    /**
     *
     * @param type $invId
     * @param type $data
     * @return type 
     */
    function updatePrimaryServices($invId) {
        //
        $primary = "";
        $this->db->select('rel.*,cat.category_name');
        $this->db->from('category_rel as rel');
        $this->db->join("category_field as cat", "cat.category_id = rel.category_id");
        $this->db->where('rel.inventory_id = ', $invId);
        $this->db->order_by('cat.category_name asc');
        $query = $this->db->get();
        $relArr = $query->result();

        foreach ($relArr as $item) {
            if (!empty($item->category_version)) {
                $primary .= $item->category_name . '-' . $item->category_version . ',';
            } else {
                $primary .= $item->category_name . '-xx,';
            }
        }

        $this->db->trans_start();
        $this->db->where('inventory_id', $invId);
        $this->db->update('inventory', array('primary_services' => $primary, 'status' => 'active'));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    /**
     * @desc    
     * 
     * @param type $invID
     * @return type 
     */
    function getInventoryLocation($invID) {
        $this->db->select("*");
        $this->db->where('inventory_id', $invID);
        $query = $this->db->get('inventory');
        if ($query->num_rows() > 0)
            return $query->row();
        else
            return false;
    }

    /**
     *
     * @param type $invID
     * @return type 
     */
    function cloneNewInventory($invId, $invName) {
        
        //get the currently one information 
        //
        $this->db->select("*");
        $this->db->where('inventory_id', $invId);
        $query = $this->db->get('inventory');
        if ($query->num_rows() > 0)
            $invInfo = $query->row();
        else
            return false;

        $modelArr = $this->getModelTableById($invInfo->inventory_model_id);

        if (!isset($modelArr->inventory_model_code))
            return false;
        
        $tableName = trim($modelArr->inventory_model_code);
        $modelTable = "inventory_" . $tableName . "_hardware_list";
        
        //uniqure inventory name 
        if($this->checkUniqueInventoryName($invName, $invInfo->inventory_model_id, $invInfo->location_id))
            return false;
        //clone start.
        $this->db->trans_start();

        $invData = array();
        $invData['inventory_model_id'] = $invInfo->inventory_model_id;
        $invData['location_id'] = $invInfo->location_id;
        $invData['inventory_name'] = $invName;
        $invData['created_by'] = $this->session->userdata('username');
        $invData['creation_date'] = date("Y-m-d H:i:s");
        //get the clone inventory id.
        $this->db->insert('inventory', $invData);

        $lastInsertInvID = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        //get the hardware information
        $query = $this->db->query("select * from {$modelTable} where inventory_id = '{$invId}'");

        $hItem = $query->row_array();
        $hItem['inventory_id'] = $lastInsertInvID;
        //Inventory Name --- every inventory must has this field.
        $hItem['cid_101'] = $invName;

        //return var_dump($hItem);
        //insert into model table
        $this->db->insert($modelTable, $hItem);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        //get the software information
        $this->db->select("*");
        $this->db->where('inventory_id', $invId);
        $query = $this->db->get('category_rel');
        $invRelResult = $query->result();
        
        foreach ($invRelResult as $relRow) {
            $softwaredata = array(
                'inventory_id' => $lastInsertInvID,
                'category_id' => $relRow->category_id,
                'category_version' => $relRow->category_version,
                'flag' => 'yes'
            );
            $this->db->insert('category_rel', $softwaredata);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            }
        }
        //
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            //record log message
            $messageData = array();
            $messageData['inventory'] = $invData;
            $messageData['software'] = $softwaredata;
            $messageData['hardData'] = $hItem;
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $lastInsertInvID;

            $this->_record['title'] = "Clone Inventory";
            $this->_record['name'] = $invName;
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);

            //update primary services
            $this->updatePrimaryServices($lastInsertInvID);
            return $lastInsertInvID;
        }
    }
	
    function restoreInventory($invID) {
		//
		$this->db->select('*');
        $this->db->from('inventory');
        $this->db->where('inventory_id = ', $invID);
        $query = $this->db->get();
		$rs = $query->row();
		$invName = $rs->inventory_name;
		$inventoryName = $invName;
		if($rs->status !='deleted')
			return false;
		
		$this->db->trans_start();
        $this->db->where('inventory_id', $invID);
        $this->db->update('inventory', array('status' => 'active','inventory_name' => $inventoryName ));

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
			
			//record log message
            $this->_record['user_id'] = $this->session->userdata('userid');
            $this->_record['user_name'] = $this->session->userdata('username');
            $this->_record['target'] = 'inventory';
            $this->_record['target_id'] = $invID;

            $this->_record['title'] = "Restored [{$inventoryName}] at ".date("Y-m-d H:i:s");
            $this->_record['name'] = $invName;
            $this->_record['message'] = "Restore {$invName} as {$inventoryName}";
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
			
            return true;
        }
		//
	}
	
    /**
     * @desc  make sure uniqure inventory name in table inventory
     * 
     * @param type $inventoryName
     * @param type $modelId
     * @param type $locationId
     * @return type 
     */
    function checkUniqueInventoryName($inventoryName, $modelId,$locationId = 0,$inventoryId= -1) {
        $this->db->select('*');
        $this->db->from('inventory');
        $this->db->where('inventory_name = ', $inventoryName);
        $this->db->where('inventory_model_id = ', $modelId);
        if($locationId > 0)
            $this->db->where('location_id = ', $locationId);
        if($inventoryId > 0)
            $this->db->where('inventory_id != ', $inventoryId);
        $query = $this->db->get();
        
        return ($query->num_rows()> 0)? true: false;
    }
}
