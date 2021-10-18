<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory extends Admin_Controller {

    public $modelName = null;
    public $locationCurrency = array();
    public $locationId = null;
    private $_softwarelist = null;

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('inventory/Inventory_m', 'model_mm');
        $this->getLocationCurrencylist();
        //var_dump($this->session->unset_userdata('locationlist'));
        //$this->checkInventoryLocation(1);
    }

    public function index() {
	
        $params = $this->uri->uri_to_assoc();
		
		//var_dump($params);
		
        $startIndex = !empty($params['page']) ? $params['page'] : 0;
        if (!empty($params['locationid']))
            $_REQUEST['locationid'] = $params['locationid'];

        if (!empty($params['inventoryModelList']))
            $_REQUEST['inventoryModelList'] = $params['inventoryModelList'];
        
        //how to to the custom filter and search by the option.
        //customFiledFilter	cid_101
        //customFiledFilterValue	fafadfdsa
        if (!empty($params['cfv']))
            $_REQUEST['cfv'] = $params['cfv'];
        
        if (!empty($params['cf']))
            $_REQUEST['cf'] = $params['cf'];
			
		if (!empty($params['inv_status']))
			$_REQUEST['inv_status'] = $params['inv_status'];
			
		if (!empty($params['sortOrder']))
			$_REQUEST['sortOrder'] = $params['sortOrder'];

        //var_dump($params);
        $inventoryModelList = isset($_REQUEST['inventoryModelList']) ? $_REQUEST['inventoryModelList'] : 2;

        $modelTableName = $this->getModelTableById($inventoryModelList);

        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];
        //var_dump($_REQUEST['locationid']);
        $this->locationId = $locationid;
        //get the total count of the corp list.
        $inventoryArr = $this->model_mm->getInventoryList($modelTableName, $locationid, $_REQUEST, $startIndex);
        //var_dump($inventoryArr);
        $totalNumber = $this->model_mm->getInventoryTotalNumber($modelTableName, $locationid, $_REQUEST);
        //var_dump($totalNumber);
		
		$uri_segment = 8;
        $urlString = '';
        if (!empty($_REQUEST['locationid']))
            $urlString .= '/locationid/' . $_REQUEST['locationid'];
        else
            $urlString .= '/locationid/' . $userLocation[0];

        if (!empty($_REQUEST['inventoryModelList']))
            $urlString .= "/inventoryModelList/" . $_REQUEST['inventoryModelList'];
        else
            $urlString .= "/inventoryModelList/2";
        
        if (!empty($_REQUEST['cf'])) {
            $urlString .= '/cf/' . $_REQUEST['cf'];
			$uri_segment += 2;
		}
        
        if (!empty($_REQUEST['cfv'])) {
            $urlString .= '/cfv/' . $_REQUEST['cfv'];
			$uri_segment += 2;
		}
		
		if (!empty($_REQUEST['sortOrder'])) {
            $urlString .= '/sortOrder/' . $_REQUEST['sortOrder'];
			$uri_segment += 2;
		}
		
		if (!empty($_REQUEST['inv_status']))
            $urlString .= '/inv_status/' . $_REQUEST['inv_status'];
		else
			$urlString .= '/inv_status/active';
		
		$uri_segment += 2;
		//$config['base_url'] = site_url("inventory/index/page");
		
        $config['base_url'] = site_url($this->router->class . '/' . $this->router->method . $urlString . '/page');
		
        $config['total_rows'] = $totalNumber;
        $config['uri_segment'] = $uri_segment;
		
        $this->pagination->initialize($config);
        //model category relation list
        $modelRelCategory = $this->getModelRelCategory($inventoryModelList, 1);
		
		//set the active inventory as the default
		$inv_status = isset($_REQUEST['inv_status']) ? $_REQUEST['inv_status'] : 'active';
		
		//inventory status list
		$inventoryStatusList = $this->inventoryStatusList($inv_status);
		
		//sort order by asc or desc, default is asc
		$sort_status = isset($_REQUEST['sortOrder']) ? $_REQUEST['sortOrder'] : 'asc';
		$selectSortyByStatusList = $this->selectSortyBy($sort_status);
		
        //var_dump($inventoryArr);
        $this->template
                ->title('Inventory List')
                ->set('title_info', 'Inventory List')
                ->set('locationinfo', $this->locationList($locationid, $userLocation))
                ->set('inventoryModelInfo', $this->inventoryModelList(array('inventoryModelList' => $inventoryModelList)))
                ->set('inventoryData', $inventoryArr)
                ->set('modelRelCategory', $modelRelCategory)
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('totalNumber', $totalNumber)
                ->set('locationName', $this->model_mm->getLocationNameById($locationid))
                ->set('modelName', $this->modelName)
				->set('inventoryStatusSelectList', $inventoryStatusList)
				->set('sortOrderList', $selectSortyByStatusList)
                ->build('inventory/list.php');
    }

    public function newitem() {
        //var_dump($_REQUEST);
        //$this->form_validation->set_rules('inventoryModelList', 'Model Type', 'required');
        $inventoryModelList = isset($_REQUEST['inventoryModelList']) ? $_REQUEST['inventoryModelList'] : 1;
        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];
        
        if(!$this->checkUserLocation($locationid)){
            $information = "I'm sorry, you don't have permission to access it.";
            show_error($information, 505);
            exit;
        }
        
        $this->template
                ->title('New Inventory Item')
                ->set('title_info', 'New Inventory Item')
                ->set('locationinfo', $this->locationList($locationid, $userLocation))
                ->set('inventoryModelInfo', $this->inventoryModelList(array('inventoryModelList' => $inventoryModelList)))
                ->set('inventoryHardwareList', $this->modelCategoryList(1, 'inventoryHardwareList', $inventoryModelList, true))
                ->set('inventorySoftwareList', $this->modelCategoryList(2, 'inventorySoftwareList', $inventoryModelList))
                ->build('inventory/newitem.php');
    }

    /**
     * 
     */
    public function edititem() {
        $params = $this->uri->uri_to_assoc();
        $inventoryID = $params['id'];
        
        if(!$this->checkInventoryLocation($inventoryID)) {
            $information = "I'm sorry, you don't have permission to access it.";
            show_error($information, 505);
            exit;
        }
        
        $modelTableName = $this->getModelTableById($params['modelid']);
        //die($modelTableName);
        //hardware category information
        $inventoryInfo = $this->model_mm->getInventoryInfo($inventoryID, $modelTableName);
        //var_dump($inventoryInfo);
        //
        //software category relation information
        $inventoryCaRelInfo = $this->model_mm->getInventoryCatRelInfo($inventoryID);
        //var_dump($inventoryCaRelInfo);
        $inventoryCatRelInfoList = array();
        foreach ($inventoryInfo as $k => $v) {
            $inventoryCatRelInfoList[$k] = $v;
        }

        foreach ($inventoryCaRelInfo as $row) {
            if (isset($inventoryCatRelInfoList['cid_' . $row->category_id]))
                $inventoryCatRelInfoList['cid_' . $row->category_id] = $inventoryCatRelInfoList['cid_' . $row->category_id] . ',' . $row->category_version;
            else
                $inventoryCatRelInfoList['cid_' . $row->category_id] = $row->category_version;

            $this->_softwarelist[$row->category_id] = $row->category_version;
        }
        //var_dump($inventoryInfo);
        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $inventoryModelList = isset($_REQUEST['inventoryModelList']) ? $_REQUEST['inventoryModelList'] : $inventoryInfo->inventory_model_id;

        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $inventoryInfo->location_id;
        $this->template
                ->title('Edit Inventory Item')
                ->set('title_info', 'Edit Inventory Item')
                ->set('locationinfo', $this->locationList($locationid, $userLocation))
                ->set('inventoryModelInfo', $this->inventoryModelList(array('inventoryModelList' => $inventoryModelList)))
                ->set('inventoryHardwareList', $this->modelCategoryList(1, 'inventoryHardwareList', $inventoryModelList, true))
                ->set('inventorySoftwareList', $this->modelCategoryList(2, 'inventorySoftwareList', $inventoryModelList, false, $this->_softwarelist))
                ->set('inventoryInfoData', $inventoryCatRelInfoList)
                ->set('invInfo', $inventoryInfo)
                ->set('inventoryid', $inventoryID)
                ->build('inventory/edititem.php');
    }

    public function addNewItem() {
        /**
          hard[0][]	cid_101
          hard[0][]
          invmodel	2
          locationid	1
          soft[0][]	cid_1002
          soft[0][]	1.3.33,
         * 
         */
        $modelTable = $this->getModelTableById($_POST['invmodel']);
        if (!$modelTable)
            die(json_encode('There are something wrong with the database!'));
        $hardData = array();
        if (isset($_POST['hard'])) {
            foreach ($_POST['hard'] as $hitem) {
                $hardData[$hitem[0]] = trim($hitem[1]);
            }
        }

        $softData = array();
        if (isset($_POST['soft'])) {
            foreach ($_POST['soft'] as $sitem) {
                $p = explode("_", $sitem[0]);

                $slist = explode(',', $sitem[1]);
                foreach ($slist as $ssitem) {
                    if (empty($ssitem))
                        continue;
                    $softData[$p[1]][] = trim($ssitem);
                }
            }
        }
        //die(var_dump($softData));
        $rs = $this->model_mm->addNewItem($modelTable, $_POST['locationid'], $_POST['invmodel'], $hardData, $softData);
        die(json_encode($rs));
        return false;
    }

    public function editNewItem() {
        /**
          hard[0][]	cid_101
          hard[0][]
          invmodel	2
          locationid	1
          soft[0][]	cid_1002
          soft[0][]	1.3.33,
         * 
         */
        $inventoryId = isset($_POST['inventoryid']) ? $_POST['inventoryid'] : 0;
        if ($inventoryId < 1)
            die(json_encode('Please go back to the inventory list!'));
        
        if(!$this->checkInventoryLocation($inventoryId))
            die(json_encode("You don't have permission to modify it!"));
        
        $modelTable = $this->getModelTableById($_POST['invmodel']);
        if (!$modelTable)
            die(json_encode('There are something wrong with the database!'));

        $hardData = array();
        if (isset($_POST['hard'])) {
            foreach ($_POST['hard'] as $hitem) {
                $hardData[$hitem[0]] = trim($hitem[1]);
            }
        }

        $softData = array();
        if (isset($_POST['soft'])) {
            foreach ($_POST['soft'] as $sitem) {
                $p = explode("_", $sitem[0]);

                $slist = explode(',', $sitem[1]);
                foreach ($slist as $ssitem) {
                    if (empty($ssitem))
                        continue;
                    $softData[$p[1]][] = trim($ssitem);
                }
            }
        }
        //die(var_dump($softData));

        $rs = $this->model_mm->updateItem($inventoryId, $modelTable, $_POST['locationid'], $_POST['invmodel'], $hardData, $softData);
        die(json_encode($rs));
        return false;
    }

    /**
     *
     * @return type 
     */
    public function delitem() {

        $inventoryId = isset($_POST['id']) ? $_POST['id'] : 0;
        
        if(!$this->checkInventoryLocation($inventoryId)) {
            die ('false');
            return;
        }
        
        $modelTableName = $this->getModelTableById($_POST['modelid']);
        $rs = $this->model_mm->delInventory($inventoryId, $modelTableName);
        die(json_encode($rs));
        return ;
    }

    public function removeItemfromDB () {
	//die("true");
	//return;
	$inventoryId = isset($_POST['id']) ? $_POST['id'] : 0;

        if(!$this->checkInventoryLocation($inventoryId)) {
            die ('false');
            return;
        }
	
	$modelTableName = $this->getModelTableById($_POST['modelid']);
        $rs = $this->model_mm->delInventoryFromDB($inventoryId, $modelTableName);
        die(json_encode($rs));
        return ;

    }

    public function batchUpdateCategory() {
        $params = $this->uri->uri_to_assoc();
        $startIndex = !empty($params['page']) ? $params['page'] : 0;
        
        if (!empty($params['locationid']))
            $_REQUEST['locationid'] = $params['locationid'];

        if (!empty($params['inventoryModelList']))
            $_REQUEST['inventoryModelList'] = $params['inventoryModelList'];
        
        if (!empty($params['calSoftwareList']))
            $_REQUEST['calSoftwareList'] = $params['calSoftwareList'];
        
        //var_dump($params);
        $inventoryModelList = isset($_REQUEST['inventoryModelList']) ? $_REQUEST['inventoryModelList'] : 2;

        $modelTableName = $this->getModelTableById($inventoryModelList);

        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];
        
        $softwareName = isset($_REQUEST['calSoftwareList']) ? $_REQUEST['calSoftwareList'] : '';
        
        $version = isset($_REQUEST['version']) ? $_REQUEST['version'] : 'no-version-x';
        //var_dump($_REQUEST['locationid']);
        $this->locationId = $locationid;
        //get the total count of the corp list.($inventoryModelList, $locationid, $params['cal']) {
		$modelTableName = $this->getModelTableById($inventoryModelList);
		
        $inventoryArr = $this->model_mm->getInventoryListByCal($inventoryModelList, $locationid, $modelTableName, $softwareName,$version);
        //var_dump($inventoryArr);
        $totalNumber = $this->model_mm->getInventoryTotalNumberByCal($inventoryModelList, $locationid, $modelTableName, $softwareName,$version);
        
        $config['total_rows'] = $totalNumber;
        //$config['uri_segment'] = 6;
        $this->pagination->initialize($config);
        
        $categoryList = $this->batchCategorySelectedList(2, $inventoryModelList,$softwareName);
        
        $this->template
                ->title('Software Inventory List')
                ->set('title_info', 'Software Inventory List')
                ->set('locationinfo', $this->locationList($locationid, $userLocation))
                ->set('inventoryModelInfo', $this->inventoryModelList(array('inventoryModelList' => $inventoryModelList)))
				->set('modelRelCategory', $this->getModelRelCategory($inventoryModelList, 1))
                ->set('inventorySoftwareList', $categoryList)
                ->set('inventoryData', $inventoryArr)
                ->set('totalNumber', $totalNumber)
                ->set('locationName', $this->model_mm->getLocationNameById($locationid))
                ->set('modelName', $this->modelName)
                ->build('inventory/batchcategory.php');
    }
    
    /**
     *
     * @return type 
     */
    public function batchUpdateCalVersion() {
        //var cid = $("#categoryId").val();
        //var invid = $("#inventoryId").val();
        //var postData = {cid:cid, version:version, invid:invid};
        $rs = $this->model_mm->batchUpdateCalVersion($_POST['cid'], $_POST['invid'], $_POST['version']);
        //$rs = false;
        if($rs)
            die(json_encode('1'));
        else
            die(json_encode('There are something wrong, please check the MySQL database.'));
        return false;
    }

    /**
     * 
     */
    public function report() {
        $inventoryModelList = isset($_REQUEST['inventoryModelList']) ? $_REQUEST['inventoryModelList'] : 2;

        $modelTableName = $this->getModelTableById($inventoryModelList);

        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);

        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];
        $this->locationId = $locationid;
        
		//get the total count of the corp list.
        $inventoryArr = $this->model_mm->getInventoryList($modelTableName, $locationid, $_REQUEST, -1);

        //model Relation category
        $modelRelCategory = $this->getModelRelCategory($inventoryModelList, 1);

	
        //$downloadFileName = $this->modelName . '_' . $this->model_mm->getLocationNameById($locationid)->location_name."11.xls";
		$locationFullName = trim($this->model_mm->getLocationNameById($locationid)->location_name);
		$splitLocationName = split(" ", $locationFullName);
		$locationShortName = isset($splitLocationName[0])?trim($splitLocationName[0]):'inv';
		$downloadTime = date("Y_m_d");
        $downloadFileName = $locationShortName.'_'.$this->modelName."_".$downloadTime.".xls";
		//die($downloadFileName);
		//$downloadFileName = "inventory-list.xls";
        $title = array();
        foreach ($modelRelCategory as $mitem) {

            if ($mitem == "Invoice Amount") {
                if (array_key_exists($this->locationId, $this->locationCurrency))
                    $title[] = $mitem . " (" . $this->locationCurrency[$this->locationId] . ")";
                else
                    $title[] = $mitem;
            }
            else
                $title[] = $mitem;
        }

        $data = array();
        //$data[] = $title;
        $tmp = array();
        //$inventoryArr = $this->model_mm->getInventoryList(null, -1);
        foreach ($inventoryArr as $item) {
            foreach ($modelRelCategory as $mkey => $mitem) {
                $tmpKey = "cid_" . $mkey;
                
				//
				if ($mkey == 113)
					$tmp[] = $item->primary_services;
				else
					$tmp[] = $item->$tmpKey;
            }
            $data[] = $tmp;
            unset($tmp);
        }
		//var_dump($inventoryArr);return;
		//set header information
		ob_end_clean();
        header("Content-type: application/octet-stream");

        header("Content-Disposition: attachment; filename={$downloadFileName}");
        header("Pragma: no-cache");
        header("Expires: 0");
	
		//title information
		echo "
			<table border = \"1\" cellspacing= \"15\"><tr>";
		
		foreach ($title as $iTitle) {
			echo "<th>",$iTitle,"</th>";
		}
		echo "</tr>";
		foreach ($data as $iRow) {
			echo "<tr>";
			foreach ($iRow as $item) {
				echo "<td>",$item,"</td>";
			}
			echo "</tr>";
		}
			
		exit;
    }

    /**
     *
     * @return type 
     */
    public function cloneNewInventory() {
        
        $invId = $_POST['inventoryId'];
        $invName = $_POST['inventoryName'];
        $result = $this->model_mm->cloneNewInventory($invId,$invName);
        //$result = 0;
        die(json_encode($result));
        
        return;
    }

	public function restore() {
		
		$invId = $_POST['id'];
		$result = $this->model_mm->restoreInventory($invId);
		
		die(json_encode($result));
		
		return ;
		
	}
    /**
     * @desc how to get the category version list from the version
     *
     */
    public function getCategoryVersionList() {
        $params = $this->uri->uri_to_assoc();

        $categoryId = $params['id'];

        $data = $this->model_mm->categoryVersionlist($categoryId);

        $arr = array();
        foreach ($data as $row) {
            $arr[] = $row->category_field_value;
        }

        die(json_encode($arr));
        return false;
    }

    private function locationList($locationList, $userLocation) {
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

    private function inventoryModelList($param = array()) {
        //
        $inventoryModelList = $this->model_mm->getInventoryModelList();
        $modelList = "<select name='inventoryModelList' onchange='subForm(this);'>";

        //var_dump($locationArr);
        foreach ($inventoryModelList as $row) {
            //get the location list from user acccount, currently just display all of them to test.
            if (isset($param['inventoryModelList']))
                $selected = ($row->inventory_model_id == $param['inventoryModelList']) ? "selected" : "";
            else
                $selected = "";
            $modelList .= "<option value='{$row->inventory_model_id}' {$selected}>{$row->inventory_model_name}</option>";
        }
        $modelList .= "</select>";
        return $modelList;
    }

    /**
     *
     * @param type $modelID
     * @param type $name
     * @param type $allSelected
     * @return string 
     */
    private function modelCategoryList($modelID, $name, $inventoryModelID, $allSelected=false, $relationList= array()) {
        $invModelList = $this->model_mm->getModelCategoryRel($modelID, $inventoryModelID);
        $modelList = "<select name='{$name}' id='{$name}' multiple='multiple' style='width: 370px;'>";
        //var_dump($locationArr);
        $selected = '';
        if ($allSelected)
            $selected = "selected";

        foreach ($invModelList as $row) {
            //category relation list
            if ($allSelected == false && isset($relationList[$row->category_id]))
                $selected = "selected";
            elseif ($allSelected == false) {
                $selected = "";
            }
            $modelList .= "<option value='{$row->category_id}' {$selected}>{$row->category_name}</option>";
        }
        $modelList .= "</select>";
        return $modelList;
    }
    
    /**
     *
     * @param type $modelID
     * @param type $inventoryModelID
     * @param type $categoryId
     * @return string 
     */
    private function batchCategorySelectedList($modelID, $inventoryModelID,$categoryId=-1) {
        
        $invModelList = $this->model_mm->getModelCategoryRel($modelID, $inventoryModelID);
        $modelList = "<select name='calSoftwareList' id='calSoftwareList'>";
        $modelList .= "<option value=''>--Please Select Software--</option>";
        foreach ($invModelList as $row) {
            $selected = ($row->category_id == $categoryId)? "selected":'';
            $modelList .= "<option value='{$row->category_id}' {$selected}>{$row->category_name}</option>";
        }
        $modelList .= "</select>";
        
        return $modelList;
    }
    
    private function getModelTableById($modelId) {
        $modelArr = $this->model_mm->getModelTableById($modelId);
        if (!isset($modelArr->inventory_model_code))
            return false;
        else {
            $tableName = trim($modelArr->inventory_model_code);
            $table = "inventory_" . $tableName . "_hardware_list";
            //set the model Name
            $this->modelName = $modelArr->inventory_model_name;

            return $table;
        }
    }

    private function getModelRelCategory($modelID, $categoryParentId=0) {
        $modelRelCategoryArr = $this->model_mm->getModelRelCategoryList($modelID, $categoryParentId);
        $arr = array();
        foreach ($modelRelCategoryArr as $row) {
            $arr[$row->category_id] = $row->category_name;
        }
        return $arr;
    }

    private function getLocationCurrencylist() {
        $locationArr = $this->model_mm->getLocationList();
        foreach ($locationArr as $item) {
            $this->locationCurrency[$item->location_id] = $item->currency_code;
        }
        return;
    }

    /**
     *
     * @param type $inventoryId
     * @return type 
     */
    private function checkInventoryLocation($inventoryId) {

        $invInfo = $this->model_mm->getInventoryLocation($inventoryId);
        
        if(empty($invInfo))
            return false;
        
        $locationID = $invInfo->location_id;
        
        $userLocation = unserialize($this->session->userdata('locationlist'));
        
        if (in_array($locationID, $userLocation))
            return true;
        else
            return false;
    }
    
    /**
     *
     * @param type $locationid
     * @return type 
     */
    private function checkUserLocation($locationid) {

        $userLocation = unserialize($this->session->userdata('locationlist'));

        if (in_array($locationid, $userLocation))
            return true;
        else
            return false;
    }
	
	private function inventoryStatusList($status="active") {
		
		$arrStatus = array("--All--" => "all", "Active" => 'active', "Deleted" => 'deleted');
		
		$string = "<select name='inv_status' id='inv_status'>";
		//die($status);
		foreach ($arrStatus as $key => $value) {
			if($status == $value)
				$selected = "selected=''";
			else
				$selected = "";
			
			$string .= "<option ".$selected." value='{$value}'>{$key}</option>";
		}
		$string .= "</select>";
		
		return $string;
	}
	
	private function selectSortyBy($status="asc") {
		//<select name="sort_order">
		//<option value="asc">ascending</option>
		//<option value="desc">descending</option>
		//</select>
		$arrStatus = array("asc" => 'ascending', "desc" => 'descending');
		
		$string = "<select name='sortOrder' id='sortOrder'>";
		//die($status);
		foreach ($arrStatus as $key => $value) {
			if($status == $key)
				$selected = "selected=''";
			else
				$selected = "";
			
			$string .= "<option ".$selected." value='{$key}'>{$value}</option>";
		}
		$string .= "</select>";
		
		return $string;
	}
}
