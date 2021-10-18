<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends Admin_Controller {

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
        //$this->load->model('provider/provider_m', 'model_p');
        $this->load->model('category/category_m', 'model_category');
        $this->load->model('category/categorylist_m', 'model_category_info');
    }

    public function index() {

        $this->form_validation->set_rules('categoryname', 'Category Name', 'required');

        if ($this->form_validation->run() != FALSE) {
            $this->model_cp->insert_corp($_POST);
        }

        //get the total count of the corp list.
        $totalNumber = $this->model_category->getCategoryTotalNum($_REQUEST);

        $config['base_url'] = site_url("category/index");
        if (!empty($_REQUEST['category_name']))
            $config['base_url'] .= "/category_name/{$_REQUEST['category_name']}";
        if (isset($_REQUEST['topcategory']) && $_REQUEST['topcategory'] > 0)
            $config['base_url'] .= "/topcategory/{$_REQUEST['topcategory']}";
        $config['base_url'] .= "/page/";
        $config['total_rows'] = $totalNumber;

        $this->pagination->initialize($config);

        //Get the Current page number.
        $startIndex = ($this->uri->segment(4) > 0) ? ($this->uri->segment(4)) : 0;
        //
        $parent = array();
        $parentcategory = $this->getParentCategoryList();
        foreach ($parentcategory as $row) {
            $parent[$row->category_id]['category_name'] = $row->category_name;
        }

        //
        $this->template
                ->title('Category Management')
                ->set('title_info', 'Category List')
                ->set('category_data', $this->model_category->getCategoryList($_REQUEST, $startIndex))
                ->set('paginationlinks', $this->pagination->create_links())
                ->set('parenetcategory', $parent)
                ->set('parentcategorylist', $this->parentCategoryList())
                ->set('totalNumber', $totalNumber)
                ->build('category/list.php');
    }

    /**
     * Add New Categry 
     */
    public function newcategory() {

        $this->form_validation->set_rules('categoryname', 'Category Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            //show_error('test');
            $this->template
                    ->title('Category Management')
                    ->set('title_info', 'New Category')
                    ->set('topcategorylist', $this->parentCategoryList())
                    ->build('category/add.php');
        } else {
            $this->model_category->addNewCategory($_POST);
            redirect('category/');
        }
    }

    /**
     * @desc edit category by category id
     *
     */
    public function editcategory() {
        $params = $this->uri->uri_to_assoc();

        $categoryId = $params['id'];
        $categoryInfo = $this->model_category->getCategoryById($categoryId);

        $this->form_validation->set_rules('categoryname', 'Category Name', 'required');

        if ($this->form_validation->run() == true) {
            if ($this->model_category->updateCategory($_POST)) {
                //$this->message->set('updated successfully.', 'error');
                redirect('category/editcategory/id/' . $categoryId);
                exit;
            }
        }

        $this->template
                ->title('Edit Category')
                ->set('title_info', 'Edit Category Information')
                ->set('categoryInfo', $categoryInfo)
                ->set('categoryID', $categoryId)
		->set('parentID',$categoryInfo->category_parent_id)
                ->set('topcategorylist', $this->parentCategoryList($categoryInfo->category_parent_id))
                ->set('categoryVersionInfo', $this->model_category->getCategoryVersionlist($categoryId))
                ->build('category/edit.php');
    }

    /**
     * 
     */
    public function newoption() {

        $params = $this->uri->uri_to_assoc();

        $categoryId = $params['id'];
        $categoryInfo = $this->model_category->getCategoryById($categoryId);

        $this->form_validation->set_rules('categoryoption', 'New Option', 'required');

        if ($this->form_validation->run() == true) {
            if ($this->model_category->addNewOption($_POST)) {
                $this->message->set('New option added successfully.', 'error');
            } else {
                $this->message->set('There is something wrong, please try again.', 'error');
            }
        }

        $this->template
                ->title('Edit Category')
                ->set('title_info', 'Edit Category Information')
                ->set('categoryInfo', $categoryInfo)
                ->set('categoryID', $categoryId)
                ->set('topcategorylist', $this->parentCategoryList($categoryInfo->category_parent_id))
                ->set('categoryVersionInfo', $this->model_category->getCategoryVersionlist($categoryId))
                ->build('category/edit.php');
    }

    public function editoption() {

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $option = isset($_POST['option']) ? $_POST['option'] : '';
        /**if ($id < 1)
            die(json_encode('false'));
        if (empty($option))
            die(json_encode('false'));
        */
        $rs = $this->model_category->updateOptionValue(trim($id), trim($option));
        die(json_encode($rs));
        return false;
    }
    
    public function delOption() {
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $option = isset($_POST['option']) ? $_POST['option'] : '';
        /**var postData = {
                id:cid,
                option:option
        };
        /**if ($id < 1)
            die(json_encode('false'));
        if (empty($option))
            die(json_encode('false'));
        */
        $rs = $this->model_category->delOption(trim($id), trim($option));
        die(json_encode($rs));
        return false;
    }

    /**
     * 
     */
    public function ipv6MinVersion() {
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
                ->build('category/ipv6list.php');
    }

    public function editipv6() {
        $this->form_validation->set_rules('categoryname', 'Category Name', 'required');
        if ($this->form_validation->run() == true) {
            if ($this->model_category->updateIpv6($_POST)) {
                $this->message->set('updated successfully.', 'error');
                redirect('category/ipv6MinVersion/id/' . $categoryId);
                exit;
            }
        }

        $params = $this->uri->uri_to_assoc();

        $categoryId = $params['id'];
        $categoryIpv6Info = $this->model_category->getCategoryIpv6Info($categoryId);

        $this->template
                ->title('Edit IPV6 Min Version (support)')
                ->set('title_info', 'Edit IPV6 Min Version')
                ->set('categoryID', $categoryId)
                ->set('categoryInfo', $categoryIpv6Info)
                ->build('category/editipv6.php');
    }

    /**
     *
     * @return type 
     */
    private function getParentCategoryList() {
        return $this->model_category->getParentCategoryList();
    }

    /**
     *
     *
     */
    private function parentCategoryList($parentid = null) {
        $parentCategory = "<select id='topcategory' name='topcategory'><option value='-1'>----</option>";

        $topParentCategoryList = $this->getParentCategoryList();
        //var_dump($topParentCategoryList);
        foreach ($topParentCategoryList as $row) {
            $selected = ($parentid == $row->category_id) ? 'selected' : '';
            $parentCategory .= "<option value='" . $row->category_id . "' {$selected}>" . $row->category_name . "</option>";
        }
        $parentCategory .= "</select>";

        return $parentCategory;
    }

}
