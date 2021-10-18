<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends Admin_Controller {

    protected $_scorelist = null;
    private $_file_name = null;

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        $this->load->model('report/report_m', 'model_mm');
        //
        $this->_file_name = $this->session->userdata('category_score_list');
        //var_dump($this->_file_name);
    }

    public function index() {
        $this->ipv6list();
    }

    /**
     * 
     */
    public function upload() {

        $this->template
                ->title('Report Setting')
                ->set('title_info', 'Report Setting')
                ->set('error', '')
                ->build('report/upload.php');
    }

    public function do_upload() {
        $file_name = "component-" . time();

        $config['upload_path'] = 'upload/';
        $config['allowed_types'] = 'gif|jpg|png|txt|csv|xls';
        $config['max_size'] = '0';
        $config['file_name'] = $file_name;
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = $this->upload->display_errors();

            $this->template
                    ->title('upload file')
                    ->set('title_info', 'upload file')
                    ->set('error', $error)
                    ->build('report/upload.php');
        } else {
            $data = $this->upload->data();

            $this->model_mm->systemConfiguration('category_score_list', $file_name);

            //import data into database;
            $this->importComponentScore($file_name);

            //end import 
            //
            $this->template
                    ->title('upload file')
                    ->set('title_info', 'upload file')
                    ->set('upload_data', $data)
                    ->build('report/doupload.php');
        }
        return;
    }

    public function sources() {
        $invCategoryArr = $this->model_mm->getInv2CatScoreSourceList();
        //
        $title = array(
            'Components',
            'Market',
            'Physical Vulnerability Score (Weight-20%)',
            'Technical Vulnerability Score (Weight-25%)',
            'Technology Obsolescence Score (Weight-25%)',
            'Impact on Business Score (Weight-30%)',
            'Cost to bring it to next level',
            'Component/ Version# (Normalised)',
            'Reference sources'
        );

        //component score list
        $components = array();

        $compArrlist = $this->model_mm->getComponentsScoreListTable();
        foreach ($compArrlist as $compItem) {
            $components[$compItem->cp_name] = $compItem;
        }

        //software caegory source list
        $tmp = array();
        $data[] = $title;
        foreach ($invCategoryArr as $item) {
            //category_id 	category_version 	category_name
            //$tmp[] = $item->category_id;
            $key = trim($item->category_name) . '-' . trim($item->category_version);
            $tmp[] = $key;
            if (array_key_exists($key, $components)) {
                $tmp[] = $components[$key]->cp_market;
                $tmp[] = $components[$key]->physical_v_s;
                $tmp[] = $components[$key]->techical_v_s;
                $tmp[] = $components[$key]->technology_o_s;
                $tmp[] = $components[$key]->impacton_b_s;
                $tmp[] = $components[$key]->unit_enhancem_end_cost;
                $tmp[] = $components[$key]->normalised;
                $tmp[] = $components[$key]->reference;
            }
            $data[] = $tmp;
            unset($tmp);
        }

        //operating system source list
        $tmp = array();
        $osArr = $this->model_mm->getInv2osSourcesList();
        foreach ($osArr as $ositem) {
            if (empty($ositem->os))
                continue;

            $key = trim($ositem->os);
            $tmp[] = $key;
            if (array_key_exists($key, $components)) {
                $tmp[] = $components[$key]->cp_market;
                $tmp[] = $components[$key]->physical_v_s;
                $tmp[] = $components[$key]->techical_v_s;
                $tmp[] = $components[$key]->technology_o_s;
                $tmp[] = $components[$key]->impacton_b_s;
                $tmp[] = $components[$key]->unit_enhancem_end_cost;
                $tmp[] = $components[$key]->normalised;
                $tmp[] = $components[$key]->reference;
            }
            $data[] = $tmp;
            unset($tmp);
        }

        //brand source list
        $tmp = array();
        $brandArr = $this->model_mm->getInv2brandSourcesList();
        foreach ($brandArr as $bitem) {
            if (empty($bitem->brand))
                continue;
            //
            $key = trim($bitem->brand);
            $tmp[] = $key;
            if (array_key_exists($key, $components)) {
                $tmp[] = $components[$key]->cp_market;
                $tmp[] = $components[$key]->physical_v_s;
                $tmp[] = $components[$key]->techical_v_s;
                $tmp[] = $components[$key]->technology_o_s;
                $tmp[] = $components[$key]->impacton_b_s;
                $tmp[] = $components[$key]->unit_enhancem_end_cost;
                $tmp[] = $components[$key]->normalised;
                $tmp[] = $components[$key]->reference;
            }
            $data[] = $tmp;
            unset($tmp);
        }

        //
        ob_end_clean();

        $this->load->helper('csv');
        echo array_to_csv($data, 'sources-scores.csv');

        //end of the download inventory list.
        exit;
    }

    /**
     * @desc Report by Appls
     * 
     * 
     */
    public function a() {
        //SG only 
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : 2;

        $location = $this->model_mm->getLocationNameById($locationid);
        //var_dump($iplist);
        //category list  array('category_id'=> array())
        $this->fopencsvlist($this->_file_name);

        $categoryArray = array();

        $relArr = $this->model_mm->getSoftwareRelList($locationid);
        foreach ($relArr as $relitem) {
            $categoryArray[$relitem->invid][] = $relitem;
        }

        $this->template
                ->title('Appls Report List')
                ->set('title_info', "{$location->location_name} ---- Report by Appls")
                ->set('locationinfo', $this->locationList($locationid))
                ->set('location', $location)
                ->set('applist', $this->model_mm->getApplsList($locationid))
                ->set('rellist', $categoryArray)
                ->set('scorelist', $this->_scorelist)
                ->build('report/appls.php');
    }

    /*     * sss
     * @desc Report by Overall Condition
     * 
     * 
     */

    public function b() {
        //SG only 
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : 2;

        $location = $this->model_mm->getLocationNameById($locationid);

        //application list
        $appArr = array();
        $applist = $this->model_mm->getApplsByOverall($locationid);
        foreach ($applist as $appItem) {
            $appArr[$appItem->inventory_id] = $appItem;
        }
        //var_dump($appArr);

        $result = array();
        //get result from overall condition list 
        $overallArr = $this->model_mm->getOverallConditionList($locationid);
        foreach ($overallArr as $item) {
            $tmpKey = trim($item->category_name) . '-' . $item->category_version;
            //get application from $applist
            //var_dump();
            $applicatioName = isset($appArr[$item->inventory_id]->application) ? $appArr[$item->inventory_id]->application : 'xx';

            $item->flag = 1;

            if (isset($result[$tmpKey][$applicatioName]))
                $result[$tmpKey][$applicatioName]['qty'] += 1;
            else
                $result[$tmpKey][$applicatioName]['qty'] = 1;

            $result[$tmpKey][$applicatioName][] = $item;
        }

        $this->fopencsvlist($this->_file_name);

        $this->template
                ->title('Report by Overall Condition')
                ->set('title_info', "{$location->location_name} ---- Report by Overall Condition")
                ->set('locationinfo', $this->locationList($locationid))
                ->set('location', $location)
                ->set('scorelist', $this->_scorelist)
                ->set('overall', $result)
                ->build('report/overall.php');
    }

    public function c() {
        //location list
        $locationList = array();
        $location = $this->model_mm->getLocationList();
        foreach ($location as $lrow) {
            $locationList[$lrow->location_id] = $lrow->location_name;
        }

        //application list
        $appArr = array();
        $applist = $this->model_mm->getApplsByOverall();
        foreach ($applist as $appItem) {
            $appArr[$appItem->inventory_id] = $appItem;
        }
        //var_dump($appArr);
        $this->fopencsvlist($this->_file_name);

        $result = array();
        //get result from overall condition list 
        $overallArr = $this->model_mm->getOverallConditionList();
        foreach ($overallArr as $item) {
            $tmpKey = trim($item->category_name) . '-' . $item->category_version;
            //get application from $applist
            //var_dump();
            $applicatioName = isset($appArr[$item->inventory_id]->application) ? $appArr[$item->inventory_id]->application : 'xx';

            $item->flag = 1;

            if (isset($result[$tmpKey][$applicatioName]))
                $result[$tmpKey][$applicatioName]['qty'] += 1;
            else
                $result[$tmpKey][$applicatioName]['qty'] = 1;
            $result[$tmpKey][$applicatioName]['location'] = $item->location_id;
            $result[$tmpKey][$applicatioName][] = $item;
        }

        $this->template
                ->title('Report by Components')
                ->set('title_info', "Report by Components")
                ->set('location', $locationList)
                ->set('scorelist', $this->_scorelist)
                ->set('overall', $result)
                ->build('report/components.php');
    }

    public function ipv6list() {
        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];

        $iplist = array();
        $result = $this->model_mm->getProductsList($locationid);

        foreach ($result as $row) {
            $iplist[$row->category_id][] = $row;
        }
        $location = $this->model_mm->getLocationNameById($locationid);
        //var_dump($iplist);
        $this->template
                ->title('IPV6 List')
                ->set('title_info', "{$location->location_name} IPV6 List Summary")
                ->set('locationinfo', $this->locationList($locationid))
                ->set("ipv6Data", $this->model_mm->productsList())
                ->set("osdata", $this->model_mm->invOSRelList($locationid))
                ->set('location', $location)
                ->set('categoryData', $iplist)
                ->build('report/list.php');
    }

    /**
     * 
     */
    public function ipv6Export() {
        ob_end_clean();
        header("Content-type: application/octet-stream");

        header("Content-Disposition: attachment; filename=ipv6-report.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $params = $this->uri->uri_to_assoc();

        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);

        $locationid = isset($params['locationid']) ? $params['locationid'] : $userLocation[0];

        $header = array('', '', '', 'SGData Center');

        $categoryData = array();
        $result = $this->model_mm->getProductsList($locationid);

        foreach ($result as $row) {
            $categoryData[$row->category_id][] = $row;
        }

        $location = $this->model_mm->getLocationNameById($locationid);

        $ipv6Data = $this->model_mm->productsList();

        $title = array("GS Products used",
            "Min Version with IPv6 Support",
            "Category",
            "Current Version",
            "IPV6 Comment",
            "%IPv6 Ready");

        echo "
        <table border = \"1\" cellspacing= \"15\">
                <tr>
                    <th colspan='3'></th>
                    <th colspan='2' bgcolor='#99938C'>{$location->location_name}</th>
                </tr>
                <tr>
                    <th style='width:150px;'>GS Products used</th>
                    <th style='width:220px;'>Min Version with IPv6 Support</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Comment</th>
                </tr>";

        foreach ($ipv6Data as $item) {
            $count = 1;
            $cagegoryName = $item->category_name;
            $tmp = "<tr><td></td><td></td</td></tr>";
            //var_dump($categoryData);
            if (isset($categoryData[$item->category_id])) {
                $str = '';
                //
                foreach ($categoryData[$item->category_id] as $row) {
                    $version = !empty($row->category_version) ? $row->category_version : 'xx';

                    $totalnum = ($row->num > 0) ? $row->num : '0';
                    //
                    $str .= "(" . $version . ")        " . $totalnum . "<br/>";
                    $count++;
                }
                echo "<tr>
                        <td>{$cagegoryName}</td>
                        <td>{$item->ipv6_min_version}</td>
                        <td>{$item->parent_category}</td>
                        <td>{$str}</td>
                        <td></td>
                    </tr>";
            } else {
                echo "<tr><td>{$cagegoryName}</td>
                        <td>{$item->ipv6_min_version}</td>
                        <td>{$item->parent_category}</td>
                        <td bgcolor='#99938C'></td>
                        <td bgcolor='#99938C'></td
                    </tr>";
            }
        }
        $osdata = $this->model_mm->invOSRelList($locationid);
        foreach ($osdata as $item) {
            $osname = empty($item->os) ? "os-xx" : $item->os;
            echo "<tr>
                        <td>{$osname}</td>
                        <td></td>
                        <td>OS & Monitoring</td>
                        <td>{$item->kernel}</td>
                        <td>{$item->num}</td>
                    </tr>";
        }
        echo "</table>";
        exit;
    }

    /**
     * 
     */
    public function ipv6ExportAllList() {
        //SELECT inventory.location_id,`inventory`.`inventory_id`, `rel`.*, count(1) as num 
        //FROM (`inventory`) LEFT JOIN `category_rel` as rel ON `rel`.`inventory_id`= `inventory`.`inventory_id` 
        //WHERE `flag` = 'yes' GROUP BY `location_id`,`category_id`, `category_version` ORDER BY inventory.location_id,`rel`.`inventory_id` asc;

        ob_end_clean();
        header("Content-type: application/octet-stream");

        header("Content-Disposition: attachment; filename=ipv6-report-all.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        //$header = array('', '', '', 'SGData Center');

        $categoryData = array();
        $result = $this->model_mm->getProductsList();

        foreach ($result as $row) {
            $categoryData[$row->location_id][$row->category_id][] = $row;
        }
        //var_dump($categoryData);
        //exit ();
        $location = $this->model_mm->getLocationNameById();

        $ipv6Data = $this->model_mm->productsList();


        $title = array("GS Products used",
            "Min Version with IPv6 Support",
            "Category",
            "Current Version",
            "IPV6 Comment",
            "%IPv6 Ready");

        echo "<table border = \"1\" cellspacing= \"15\">
                <tr>
                    <th colspan='3'></th>";
        foreach ($location as $lRow) {
            echo "<th colspan='2' bgcolor='#99938C'>{$lRow->location_name}</th>";
        }

        echo "</tr>";
        echo "<tr>
                    <th style='width:150px;'>GS Products used</th>
                    <th style='width:220px;'>Min Version with IPv6 Support</th>
                    <th>Category</th>";
        foreach ($location as $lRow) {
            echo "<th>Version</th>
                    <th>Comment</th>";
        }

        echo "</tr>";
        //exit;
        foreach ($ipv6Data as $item) {
            $count = 1;
            $cagegoryName = $item->category_name;

            $tmp = "<tr><td>N/A</td><td>N/A</td</td></tr>";

            echo "<tr><td>{$cagegoryName}</td><td>{$item->ipv6_min_version}</td><td>{$item->parent_category}</td>";
            //location list
            foreach ($location as $lRow) {
                //var_dump($categoryData);
                //$categoryData[$row->location_id][$row->category_id][] = $row;
                if (isset($categoryData[$lRow->location_id][$item->category_id])) {
                    $str = '';
                    //
                    foreach ($categoryData[$lRow->location_id][$item->category_id] as $row) {
                        $version = !empty($row->category_version) ? $row->category_version : 'xx';

                        $totalnum = ($row->num > 0) ? $row->num : '';
                        //
                        $str .= "(" . $version . ") " . $totalnum . "<br/>";
                        $count++;
                    }
                    //
                    echo "<td>{$str}</td><td></td>";
                } else {
                    echo "<td bgcolor='#99938C'></td><td bgcolor='#99938C'></td>";
                }
            }

            echo "</tr>";
            //break;
        }

        //os report ipv6
        $osArr = array();

        $osDataArr = $this->model_mm->invOSRelList();
        foreach ($osDataArr as $osItem) {
            $osArr[$osItem->location_id][] = $osItem;
        }
        //var_dump($osArr);
        // out os
        echo "<tr><td>OS</td><td>---</td><td>OS & Monitoring</td>";
        foreach ($location as $ilRow) {
            //
            if (isset($osArr[$ilRow->location_id])) {
                //
                $str = '';
                foreach ($osArr[$ilRow->location_id] as $osItemRow) {

                    $os = empty($osItemRow->os) ? "os-xx" : $osItemRow->os;
                    $kernel = empty($osItemRow->kernel) ? "xx" : $osItemRow->kernel;

                    $str .= "(" . $os . '-' . $kernel . ") " . $osItemRow->num . "<br/>";
                }
                echo "<td>{$str}</td><td></td>";
            } else {
                //$a = var_dump($osArr[$ilRow->location_id]);
                echo "<td bgcolor='#99938C'></td><td bgcolor='#99938C'></td>";
            }
        }
        echo "</tr>";

        echo "</table>";
        exit;
    }

    /**
     * 
     */
    public function ipv6oslist() {
        $userLocation = unserialize($this->session->userdata('locationlist'));
        sort($userLocation);
        $locationid = isset($_REQUEST['locationid']) ? $_REQUEST['locationid'] : $userLocation[0];
        //var_dump($locationid);
        $location = $this->model_mm->getLocationNameById($locationid);

        $this->template
                ->title('IPV6 OS List')
                ->set('title_info', "{$location->location_name} IPV6 OS List")
                ->set('locationinfo', $this->locationList($location))
                ->set('location', $location)
                ->set('osdata', $this->model_mm->invOSRelList($locationid))
                ->build('report/oslist.php');
    }

    /**
     * 
     */
    public function test() {
        $title = array(
            "Server Name",
            "Rack Location",
            "Application",
            "Brand & Model",
            "Equipment Serial Number",
            "Inventory Number",
            "CPU",
            "Memory",
            "HDDs",
            "OS",
            "Kernel Version",
            "Primary Software",
            "Vendor",
            "Invoice Number",
            "Invoice Date",
            "PCR Number",
            "PO Number",
            "Invoice Amount (S$)",
            "Invoice Amount (US$)"
        );

        $data = array();
        $data[] = $title;
        $tmp = array();
        $inventoryArr = $this->model_mm->getInventoryList(null, -1);
        foreach ($inventoryArr as $item) {
            $tmp[] = $item->c_id_100;
            $tmp[] = $item->c_id_101;
            $tmp[] = $item->c_id_102;
            $tmp[] = $item->c_id_103;
            $tmp[] = $item->c_id_104;
            $tmp[] = $item->c_id_105;
            $tmp[] = $item->c_id_106;
            $tmp[] = $item->c_id_107;
            $tmp[] = $item->c_id_108;
            $tmp[] = $item->c_id_109;
            $tmp[] = $item->c_id_110;
            $tmp[] = $item->c_id_111;
            $tmp[] = $item->c_id_112;
            $tmp[] = $item->c_id_113;
            $tmp[] = $item->c_id_114;
            $tmp[] = $item->c_id_115;
            $tmp[] = $item->c_id_116;
            $tmp[] = $item->c_id_117;
            $tmp[] = $item->c_id_118;

            $data[] = $tmp;
            unset($tmp);
        }

        ob_end_clean();

        $this->load->helper('csv');
        echo array_to_csv($data, 'inventory.csv');

        //end of the download inventory list.
        exit;
    }

    public function userlog() {
        $this->load->model('record/record_m', 'model_report');
        $param = array();

        $totalNumber = $this->model_report->getLogTotalNum();
        $config['base_url'] = site_url("report/userlog/page");
        $config['total_rows'] = $totalNumber;

        $this->pagination->initialize($config);

        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;


        //$modelListArr = $this->model_mm->getModelList();

        $this->template
                ->title('System Log List')
                ->set('title_info', 'System Log ')
                ->set('data', $this->model_report->getLogList($startIndex, null))
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('totalNumber', $totalNumber)
                ->build('report/loglist.php');
    }
    /**
     * 
     */
    public function ipv6Min() {
        $this->load->model('category/ipv6list_m', 'model_ipv6');
        $ipv6Arr = $this->model_ipv6->getIpv6VersionList();

        $this->form_validation->set_rules('categoryname', 'Category Name', 'required');

        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;
        //
        $parent = array();
        $parentcategory = $this->getParentCategoryList();
        foreach ($parentcategory as $row) {
            $parent[$row->category_id]['category_name'] = $row->category_name;
        }

        $this->template
                ->title('IPV6 Version Management')
                ->set('title_info', 'IPV6 Version Management')
                ->set('ipv6Data', $ipv6Arr)
                ->build('report/ipv6list.php');
    }
    /**
     * 
     */
    public function ipv6MinExport() {
        $this->load->model('category/ipv6list_m', 'model_ipv6');
        $ipv6Arr = $this->model_ipv6->getIpv6VersionList();
        $title = array("Category Name","IPV6 Min Version","Parent Category");
        $result = array ();
        $result[] = $title;
        foreach ($ipv6Arr as $key => $item) {
            $tmp = array ();
            $tmp[]= $item->category_name;
            $tmp[]= $item->ipv6_min_version;
            $tmp[]= $item->parent_category;
            $result[] = $tmp;
            unset($tmp);
        }
        ob_end_clean();

        $this->load->helper('csv');
        echo array_to_csv($result, 'ipv6-min-version.csv');

        //end of the download inventory list.
        exit;
    }
    /**
     * 
     */
    public function ipv6MinImport() {
        $file_name = "ipv6minversion-" . time();

        $config['upload_path'] = 'upload/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '0';
        $config['file_name'] = $file_name;
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            
            $error = $this->upload->display_errors();

            $this->template
                    ->title('upload file')
                    ->set('title_info', 'upload file')
                    ->set('error', $error)
                    ->build('report/importIPV6.php');
        } else {
            $data = $this->upload->data();
            //$this->model_mm->systemConfiguration('category_score_list', $file_name);
            //
            //import data into database;
            //$this->importIPv6IntoDb($file_name);
            //
            //end import 
            //
            $this->template
                    ->title('upload file')
                    ->set('title_info', 'upload file')
                    ->set('upload_data', $data)
                    ->build('report/importIPV6.php');
        }
        return;
    }
    
    private function ipv6MinVersion($param= array()) {
        //$result = array();
        $ipv6Arr = $this->model_mm->getIPV6minversion();
        $tmp = array();
        foreach ($ipv6Arr as $row) {
            $key = strtolower(trim($row->category_name));
            $tmp[$key][] = $row->ipv6_min_version;
            $tmp[$key][] = $row->parent_category;
            $tmp[$key][] = $row->flag;
        }
        return $tmp;
    }

    /**
     *
     * @param type $param
     * @return string 
     */
    private function locationList($locationId) {
        $officeArr = "<select name='locationid'>";

        $locationArr = $this->model_mm->getLocationList();
        //var_dump($locationArr);
        foreach ($locationArr as $row) {
            //get the location list from user acccount, currently just display all of them to test.
            $selected = "";
            if ($locationId == $row->location_id)
                $selected = "selected";
            $officeArr .= "<option value='{$row->location_id}' {$selected}>{$row->location_name}</option>";
        }
        $officeArr .= "</select>";
        return $officeArr;
    }

    /**
     * @desc get the score list
     * 
     * @param type $filename
     * @return type 
     */
    private function fopencsvlist($filename) {
        $handle = fopen("upload/{$filename}.csv", "r");
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $i++;
            if ($i == 1)
                continue;

            //var_dump($data); break;
            //$sources[trim($data[0])] = trim($data['1']);
            $key = trim($data[0]);
            $this->_scorelist[$key] = $data;
        }
        fclose($handle);
        return;
    }

    private function importComponentScore($filename) {
        $handle = fopen("upload/{$filename}.csv", "r");
        $i = 0;

        $result = array();

        $tmp = array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $i++;
            if ($i == 1)
                continue;
            //
            //$sql .= "replace `components_score`";
            $list = array('cp_name',
                'cp_market',
                'physical_v_s',
                'techical_v_s',
                'technology_o_s',
                'impacton_b_s',
                'unit_enhancem_end_cost',
                'normalised',
                'reference');

            $t = 0;
            foreach ($list as $listItemkey => $llitem) {
                $tmp[$llitem] = trim($data[$t]);
                $t++;
            }
            // 'created_by','creation_date', 'modified_by'
            $tmp['created_by'] = $this->session->userdata('username');
            $tmp['creation_date'] = date("Y-m-d H:i:s");
            $tmp['modified_by'] = $this->session->userdata('username');
            $result[] = $tmp;
            unset($tmp);
        }
        fclose($handle);

        foreach ($result as $item) {
            $this->model_mm->replaceComponentScore('components_score', $item);
        }

        //$this->model_mm->replaceComponentScore('components_score',$result);
        return;
    }

    /**
     * 
     */
    private function _checkComponentsScoreFilename() {
        return true;
    }

    /**
     *
     * @return type 
     */
    private function getParentCategoryList() {
        $this->load->model('category/category_m', 'model_category');
        return $this->model_category->getParentCategoryList();
    }
    
    /**
     *
     * @param type $filename
     * @return type 
     */
    private function importIPv6IntoDb($filename) {
        $handle = fopen("upload/{$filename}.csv", "r");
        $i = 0;

        $result = array();

        $tmp = array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $i++;
            if ($i == 1)
                continue;
            //
            //$sql .= "replace `components_score`";
            $t = 0;
            foreach ($list as $listItemkey => $llitem) {
                $tmp[$llitem] = trim($data[$t]);
                $t++;
            }
            // 'created_by','creation_date', 'modified_by'
            $result[] = $tmp;
            unset($tmp);
        }
        fclose($handle);

        foreach ($result as $item) {
            $this->model_mm->replaceComponentScore('components_score', $item);
        }

        //$this->model_mm->replaceComponentScore('components_score',$result);
        return;
    }
}

