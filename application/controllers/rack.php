<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rack extends Admin_Controller {
	
	private $_info = null;

    public function __construct() {
        // Call the parent's constructor method
        parent::Admin_Controller();
		
        $this->load->model('rack/rack_m', 'rack_m');
    }

    public function index() {
		
        $this->template
                ->title('Rack Management')
                ->set('title_info', 'Rack Management')
				->set('error', $this->_info)
				->set('data',$this->rack_m->getUploadFileList())
                ->build('rack/list.php');
    }

	public function upload() {
        $config['upload_path'] = 'resources/rack/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = '10000';
		$config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = $this->upload->display_errors();

            $this->template
                ->title('upload file')
                ->set('title_info', 'upload file')
                ->set('error',$error)
				->set('data',$this->rack_m->getUploadFileList())
                ->build('rack/list.php');
        } else {
			$fileInfo = $this->upload->data();
			//var_dump($fileInfo);
			$fileData['filename']		= $fileInfo['file_name'];
			$fileData['user']      		= $this->session->userdata('userid');
			$fileData['username']      	= $this->session->userdata('username');
			$fileData['uploadtime']    	= date("Y-m-d H:i:s");
			if($this->rack_m->uploadFile($fileData)) {
				$this->_info = "upload successfully.";
				redirect("rack");
			} else {
				$this->template
				->set('data',$this->rack_m->getUploadFileList())
				->build('rack/list.php');
			}
        }
    }
	
	private function dbUpdate() 
	{
		;
	}
}
