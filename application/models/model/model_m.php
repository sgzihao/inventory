<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Model_m extends CI_Model {
    public $_record = null;
    
    function __construct() {
    // Call the Model constructor
        parent::__construct();
    }

    function addNewModel($modelData, $hardwareData, $softwareData) {
        
        if ($this->checkModelName($modelData['inventory_model_code'], $modelData['inventory_model_name'])) {
            $this->message->set('Model code or name already in Database, Please change it to another one.', 'error');
            return false;
        }

        //running transaction manually
        $this->db->trans_begin();

        //insert data into inventory_model
        $this->db->insert('inventory_model', $modelData);
        $lastInsertModelID = $this->db->insert_id();
        
        //create table in database , name format as  inventory_{$modelcode}_hardware_list
        $newModelTableName = "inventory_".$modelData['inventory_model_code']."_hardware_list";
        if($this->db->table_exists($newModelTableName)){
            $this->db->trans_rollback();
            $this->message->set('There is something wrong with the database, Please check with System Administrator.', 'error');
            return false;
        }
        /**
        CREATE TABLE IF NOT EXISTS `inventory_ds_hardware_list` (
          `inventory_id` int(10) NOT NULL,
          `c_id_100` text,
          PRIMARY KEY (`inventory_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        */
        $createTableSql = "CREATE TABLE IF NOT EXISTS `{$newModelTableName}` (
          `inventory_id` int(10) NOT NULL,";
        //$hardwareData
        foreach ($hardwareData as $hkey => $hvalue) {
            if(!empty ($hkey))
                $createTableSql .= "`cid_{$hkey}` text, ";
        }
        
        $createTableSql .= "PRIMARY KEY (`inventory_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $this->db->query($createTableSql);
        //$this->dbforge->add_filed($fields);
        //$this->dbforge->create_table($newModelTableName);
        //$this->dbforge->add_column($fields);
        //insert into inventory model category, relation
        //$lastInsertModelID;
        
        $modelCategoryRelArr = array();
        //hardware model category information
        foreach ($hardwareData as $hk => $hv) {
            if(empty ($hk))
                continue;
            $modelCategoryRelArr[] = array (
                    'inventory_model_id' => $lastInsertModelID,
                    'category_id' => $hk,
                    'position' => $hv
                );
        }
        foreach ($softwareData as $sk => $sv) {
            if (empty ($sk))
                continue;
            $modelCategoryRelArr[] = array (
                    'inventory_model_id' => $lastInsertModelID,
                    'category_id' => $sk,
		    'position' => 0
                );
        }
        //var_dump($modelCategoryRelArr); exit;
        $this->db->insert_batch('inventory_model_category', $modelCategoryRelArr);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        } else {
            $this->db->trans_commit();
            
            //record message
            $messageData = array ();
            $messageData['model'] = $modelData;
            $messageData['createtablesql'] = $createTableSql;
            $messageData['category'] = $modelCategoryRelArr;
            
            $this->_record['user_id'] =  $this->session->userdata('userid');
            $this->_record['user_name'] =  $this->session->userdata('username');
            $this->_record['target'] =  'model';
            $this->_record['target_id'] = $lastInsertModelID;
            
            $this->_record['title'] =  "Add Model";
            $this->_record['name'] = $modelData['inventory_model_name'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            $this->record->add($this->_record);
            //
            return true;
        }
    }

    //updateModel($model_id, $modelData,$modelCategoryRemoveArr,$modelCategoryRelArr,$modelAlterTableArr)
    function updateModel($modelID, $modelData, $modelCategoryRemoveArr, $modelCategoryRelArr, $modelAlterTableArr,$modelAlterTableColumn,$modelRelIndex) {
        $this->load->dbforge();
        
        //running transaction manually
        $this->db->trans_begin();

        //update inventory_model
        $this->db->where('inventory_model_id', $modelID);
        $this->db->update('inventory_model', $modelData);
        
        //start to handle change of category
        
        foreach ($modelCategoryRemoveArr as $rk => $rv) {
            $this->db->query($rv);
        }
        foreach ($modelCategoryRelArr as $relkey => $relvalue) {
            $this->db->query($relvalue);
        }
        //how to get the model table name
        $model_info = $this->model_mm->getModelInfo(array('id'=>$modelID));
        $modelTableName = "inventory_".$model_info->inventory_model_code."_hardware_list";
        foreach ($modelAlterTableArr as $akey => $avalue) {
            $this->dbforge->drop_column($modelTableName,$avalue);
        }
        
        foreach ($modelAlterTableColumn as $addColumn => $addValue) {
            if(!$this->add_column_if_not_exist($modelTableName, $addValue))
                $this->dbforge->add_column($modelTableName, array($addValue => array ('type'=>'TEXT')));
        }
        foreach ($modelRelIndex as $indexItem) {
            $this->db->query($indexItem);
        }
            
        //add colum into this table
        //$fields = array(
        //                'preferences' => array('type' => 'TEXT')
        //);
        //$this->dbforge->add_column('table_name', $fields);
        //end
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        } else {
            $this->db->trans_commit();
            
            //record message
            $messageData = array ();
            $messageData['model'] = $modelData;
            $messageData['tablesql'] = $modelAlterTableArr;
            $messageData['category'] = $modelCategoryRelArr;
            
            $this->_record['user_id'] =  $this->session->userdata('userid');
            $this->_record['user_name'] =  $this->session->userdata('username');
            $this->_record['target'] =  'model';
            $this->_record['target_id'] = $modelID;
            
            $this->_record['title'] =  "Edit Model";
            $this->_record['name'] = $modelData['inventory_model_name'];
            $this->_record['message'] = serialize($messageData);
            $this->_record['date'] = date("Y-m-d H:i:s");
            
            $this->record->add($this->_record);
            
            return true;
        }
    }
    //delmodel($model_id)
    function delmodel($modelID) {
        $modelNum = $this->getInventoryTotalNum($modelID)->num;
        //die($modelNum);
        if ($modelNum) {
            $this->message->set('There is {$modelNum} assets with this model in the database, Please remove them firstly.', 'error');
            return false;
        }
        
        $model_info = $this->model_mm->getModelInfo(array('id'=>$modelID));
        $modelTableName = "inventory_".$model_info->inventory_model_code."_hardware_list";
        
        $this->load->dbforge();
        //running transaction manually
        $this->db->trans_begin();

        //delete inventory_model
        $this->db->where('inventory_model_id', $modelID);
        $this->db->delete('inventory_model');
        
        //delete inventory_model
        $this->db->where('inventory_model_id', $modelID);
        $this->db->delete('inventory');
        
        //start to handle change of category
        $this->db->where('inventory_model_id', $modelID);
        $this->db->delete('inventory_model_category');
        
        
        //how to get the model table name
        $this->dbforge->drop_table($modelTableName);
        //end
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->message->set('There is something wrong with the database, Please try again.', 'error');
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    /**
     *
     * @param type $param
     * @return type 
     */
    function getSubCategoryList($parentCategoryId, $param= array()) {
        $this->db->where('category_parent_id = ', $parentCategoryId);
        $this->db->order_by('category_id', 'asc');
        $query = $this->db->get('category_field');

        return $query->result();
    }

    /**
     * Get the top category list from table `category_field`
     */
    function getParentCategoryList($param = array()) {
        $parentid = 0;
        if (isset($param['parentid'])) {
            $parentid = $param['parentid'];
        }
        $sql = "select * from category_field where category_parent_id = {$parentid} ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    function getModelList($param = array(), $startIndex) {
        $this->db->order_by('inventory_model_id', 'desc');
        $this->db->limit(30, $startIndex);
        $query = $this->db->get('inventory_model');

        return $query->result();
    }

    function getModelInfo($param = array()) {
        $this->db->where('inventory_model_id = ', $param['id']);
        $query = $this->db->get('inventory_model');

        return $query->row();
    }

    /* @desc	Model Category list
     * @param type $param
     * @return type 
     */

    function getModelCategoryRel($param = array()) {
        $this->db->where('inventory_model_id = ', $param['id']);
        $this->db->order_by('category_id', 'desc');
        $query = $this->db->get('inventory_model_category');

        return $query->result();
    }

    function getModelTotalNum($param = array()) {
        $this->db->from('inventory_model');
        return $this->db->count_all_results();
    }

    /**
     *
     * @param type $modelCode
     * @param type $modelName
     * @return type 
     */
    private function checkModelName($modelCode, $modelName) {
        $this->db->select(" inventory_model_id ");
        $this->db->from("inventory_model");

        $this->db->where("inventory_model_code = ", $modelCode);

        $this->db->or_where("inventory_model_name =", $modelName);

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true : false;
    }
    
    function checkModelCategoryRel($parentID, $modelID, $param = array()) {
        $this->db->select("rel.category_id");
        $this->db->from('inventory_model_category as rel');
        $this->db->join('category_field as cf', 'rel.category_id = cf.category_id','left');
        $this->db->where('rel.inventory_model_id = ', $modelID);
        $this->db->where('cf.category_parent_id = ', $parentID);
        $this->db->order_by('`rel`.`category_id` ASC ');
        
        $query = $this->db->get();
        //$query = $this->db->query($sql);
        return $query->result();
    }
    /**
     *
     * @param type $db
     * @param type $column
     * @param type $column_attr 
     */
    private function add_column_if_not_exist($tableName, $column){
        $exists = false;
        
        $sql = "show columns from $tableName ";
        $query = $this->db->query($sql);
        $result =  $query->result();
        
        foreach ($result as $row) {
            if($row->Field == $column)
                $exists = true;
        }
        return $exists;
    }
    
    /**
     *
     * @param type $modelId
     * @param type $categoryParentId
     * @return type 
     */
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

    /**
     *
     * @param type $id
     * @return type 
     */
    private function getInventoryTotalNum($id) {
        $this->db->select('count(1) as num');
        $this->db->from('inventory');
        $this->db->where('inventory_model_id = ', $id);
        $query = $this->db->get();

        return $query->row();
    }
}
