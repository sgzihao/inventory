<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class Model extends Admin_Controller {
    public $hardwarelist = null;
    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('model/model_m', 'model_mm');
    }

    public function index() {
        //
        $totalNumber = $this->model_mm->getModelTotalNum();
        $config['base_url'] = '';
        $config['total_rows'] = $totalNumber;

        $this->pagination->initialize($config);

        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;


        //$modelListArr = $this->model_mm->getModelList();

        $this->template
                ->title('Inventory Model List')
                ->set('title_info', 'Model List')
                ->set('model_data', $this->model_mm->getModelList(null, $startIndex))
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('totalNumber', $totalNumber)
                ->build('model/list.php');
    }

    public function newmodel() {

        $this->form_validation->set_rules('inventory_model_code', 'Model code', 'required');
        $this->form_validation->set_rules('inventory_model_name', 'Model Name', 'required');

        if ($this->form_validation->run() == true) {
            //model data
            $modelData = array(
                'inventory_model_code' => $_POST['inventory_model_code'],
                'inventory_model_name' => $_POST['inventory_model_name'],
                'created_by'           => $this->session->userdata('username'),
                'creation_date'        => date("Y-m-d H:i:s")
            );
            //hardware data info
            //var_dump($_POST['hardware']);
            //hardwarelistarr	0_104;1_101;
            $hardwareData = array();
            $hdata = explode(";", $_POST['hardwarelistarr']);
            foreach ($hdata as $key_h => $value_h) {
                //
                $tmpHardwareArr = explode('_', $value_h);
		//var_dump($tmpHardwareArr);
                if(isset ($tmpHardwareArr)) {
		    if(isset($tmpHardwareArr[1]) and $tmpHardwareArr[1] > 0)
	                    $hardwareData[$tmpHardwareArr[1]] = $tmpHardwareArr[0];
                }
            }
	    //exit;
            //var_dump($hardwareData);
            //software data info
            //var_dump($_POST['software']);
            $softwareData = array();
            
            foreach ($_POST['software'] as $key_s => $value_s) {
                $tmpSoftwareArr = explode('_', $value_s);
                $softwareData[$tmpSoftwareArr[1]] = $value_s;
            }
            //var_dump($softwareData);

            if ($this->model_mm->addNewModel($modelData, $hardwareData, $softwareData)) {
                redirect('model');
                exit;
            }
        }

        //hardware category_id 1
        $hardwareParentid = 1;
        $hardwareCategorylist = $this->categoryList("hardware", $hardwareParentid);

        //software category_id 2
        $softwareParentid = 2;
        $softwareCategorylist = $this->categoryList("software", $softwareParentid);

        $this->template
                ->title('New Inventory Model')
                ->set('title_info', 'New Inventory Model')
                ->set('hardwarelist', $hardwareCategorylist)
                ->set('softwarelist', $softwareCategorylist)
                ->set('sortablehardware', $this->hardwarelist)
                ->build('model/newmodelv.php');
    }

    public function editmodel() {
        $params = $this->uri->uri_to_assoc();

        $model_id = $params['id'];

        $model_info = $this->model_mm->getModelInfo($params);

        //model category relation
        $modelCategoryArr = array();
        $model_category_rel = $this->model_mm->getModelCategoryRel($params);
        
        foreach ($model_category_rel as $relRow) {
            $modelCategoryArr[$relRow->category_id] = $relRow->category_id;
        }

        $this->form_validation->set_rules('inventory_model_name', 'Model Name', 'required');

        if ($this->form_validation->run() == true) {
            //model data
            $modelData = array(
                'inventory_model_name' => $_POST['inventory_model_name'],
                'long_description' => $_POST['long_description'],
                'modified_by'           => $this->session->userdata('username'),
                'modified_date'        => date("Y-m-d H:i:s")
            );
            
            //start how to handle the category change
            //hardware
            $modelCategoryRelH = $this->model_mm->checkModelCategoryRel(1, $model_id);
            $modelCategoryRelArr = array ();
            $modelCategoryRemoveArr = array ();
            $modelAlterTableArr = array ();
            $modelAlterTableColumn = array();
            $modelRelIndex = array ();
            
            $hardwareData = array();
            $hdata = explode(";", $_POST['hardwarelistarr']);
            foreach ($hdata as $key_h => $value_h) {
                if(empty ($value_h))
                    continue;
                $tmpHardwareArr = explode('_', $value_h);
                if(isset ($tmpHardwareArr)) {
                    $hardwareData[$tmpHardwareArr[1]] = $tmpHardwareArr[0];
                }
            }
            //var_dump($hardwareData);exit;
            //remove relate from database which doesn't existes in the post array
            foreach ($modelCategoryRelH as $hardwareRow) {
                if (!array_key_exists($hardwareRow->category_id, $hardwareData)) {
                    $modelCategoryRemoveArr[] = "delete from inventory_model_category where inventory_model_id = {$model_id} and category_id = {$hardwareRow->category_id};";
                    $modelAlterTableArr[] = "cid_{$hardwareRow->category_id}";
                } else {
                    if($hardwareRow->category_id > 0)
                        $modelRelIndex[] = "UPDATE inventory_model_category SET `position` = '{$hardwareData[$hardwareRow->category_id]}' WHERE inventory_model_id = {$model_id} AND category_id ={$hardwareRow->category_id}";
                }
            }
            //insert into database
            foreach ($hardwareData as $keyhr => $vhr) {
                $modelCategoryRelArr[] = "INSERT ignore INTO `inventory_model_category` VALUES ({$model_id}, {$keyhr},{$vhr});";
                $modelAlterTableColumn[] = "cid_".$keyhr;
            }
            
            //software
            $modelCategoryRelS = $this->model_mm->checkModelCategoryRel(2, $model_id);
            $swCategoryRelArr = array ();
            $softwareData = array();
            foreach ($_POST['software'] as $key_s => $value_s) {
                $tmpSoftwareArr = explode('_', $value_s);
                $softwareData[$tmpSoftwareArr[1]] = $value_s;
            }
            
            //remove relate from database which doesn't existes in the post array
            foreach ($modelCategoryRelS as $softwareRow) {
                if (!in_array($softwareRow->category_id, $softwareData)) {
                    $modelCategoryRemoveArr[] = "delete from inventory_model_category 
                        where inventory_model_id = {$model_id} and category_id = {$softwareRow->category_id};";
                }
            }
            //software data info
            foreach ($softwareData as $keysr=>$valuesr) {
                $modelCategoryRelArr[] = "INSERT ignore INTO `inventory_model_category` VALUES ({$model_id}, {$keysr},'0');";
            }
            
            //end of handle the category change
            if ($this->model_mm->updateModel($model_id, $modelData,$modelCategoryRemoveArr,$modelCategoryRelArr,$modelAlterTableArr,$modelAlterTableColumn,$modelRelIndex)) {
                redirect('model');
                exit;
            }
        }

        $hardwareParentid = 1;
        $hardwareCategorylist = $this->categoryList("hardware", $hardwareParentid, $modelCategoryArr);

        //software category_id 2
        $softwareParentid = 2;
        $softwareCategorylist = $this->categoryList("software", $softwareParentid, $modelCategoryArr);

        $this->template
                ->title('Edit Inventory Model')
                ->set('title_info', 'Edit Inventory Model')
                ->set('modelInfo', $model_info)
                ->set('hardwarelist', $hardwareCategorylist)
                ->set('softwarelist', $softwareCategorylist)
                ->set('sortablehardware', $this->hardwarelist)
                ->set('selectedSortlist', $this->model_mm->getModelRelCategoryList($model_id,1))
                ->build('model/editmodelv.php');
    }

    public function editmodel2() {
        $params = $this->uri->uri_to_assoc();

        $model_id = $params['id'];

        $model_info = $this->model_mm->getModelInfo($params);

        //model category relation
        $modelCategoryArr = array();
        $model_category_rel = $this->model_mm->getModelCategoryRel($params);
        foreach ($model_category_rel as $relRow) {
            $modelCategoryArr[$relRow->category_id] = $relRow->category_id;
        }
        //end of category model relation 
        //var_dump($modelCategoryArr);

        $hardwareParentid = 1;
        $hardwareCategorylist = $this->categoryList("hardware", $hardwareParentid, $modelCategoryArr);

        //software category_id 2
        $softwareParentid = 2;
        $softwareCategorylist = $this->categoryList("software", $softwareParentid, $modelCategoryArr);

        $this->template
                ->title('Edit Inventory Model')
                ->set('title_info', 'Edit Inventory Model')
                ->set('modelInfo', $model_info)
                ->set('hardwarelist', $hardwareCategorylist)
                ->set('softwarelist', $softwareCategorylist)
                ->build('model/editmodel.php');
    }

    public function delmodel() {
        //
        $params = $this->uri->uri_to_assoc();
        $model_id = $params['id'];
        $rs = $this->model_mm->delmodel($model_id);
        //$this->load->index();
        redirect("model");
    }
    public function brand() {
        $this->template
                ->title('Global Sources Office')
                ->set('title_info', 'location list')
                ->build('model/brandlist.php');
    }

    private function modelCategoryRel($param = array()) {
        return $this->model_mm->getModelCategoryList($param);
    }

    private function categoryList($categoryName, $parentCategoryId, $rel=array()) {

        $categoryArr = $this->model_mm->getSubCategoryList($parentCategoryId);
        //var_dump($categoryArr);
        $categorylist = "<tr>";

        $i = 0;
        foreach ($categoryArr as $row) {
            if ($i > 0 && ($i % 4) == 0)
                $categorylist .= "</tr>";
            //check if it has already exits in the rel table
            $checkedvalue = array_key_exists($row->category_id, $rel) ? "checked='checked'" : '';
            $categorylist .= "<td width='25%'>
                <input type='checkbox' id='cid_{$row->category_id}' {$checkedvalue} value='cid_{$row->category_id}' name='{$categoryName}[]' />
                <label for='cid_{$row->category_id}'>" . $row->category_name . "</label>
            </td>";
            //<li class="ui-state-default">Can be dropped..</li>
            if($parentCategoryId == 1) {
                if(!array_key_exists($row->category_id, $rel))
                    $this->hardwarelist .= "<li class='ui-state-highlight' id='{$row->category_id}'>{$row->category_name}</li>";
            }
            $i++;
        }
        $categorylist .= "</tr>";
        return $categorylist;
    }

    public function createTable() {
        $this->load->dbforge();
        /**
          $fields = array(
          'blog_id' => array(
          'type' => 'INT',
          'constraint' => 5,
          'unsigned' => TRUE,
          'auto_increment' => TRUE
          ),
         * 
         */
        $fields = array(
            'preferences' => array('type' => 'TEXT')
        );
        $this->dbforge->add_column('blog', $fields);

        // gives ALTER TABLE table_name ADD preferences TEXT
    }
}
