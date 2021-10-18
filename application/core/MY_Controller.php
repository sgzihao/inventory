<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Code here is run before ALL controllers
class MY_Controller extends CI_Controller {

	// Deprecated: No longer used globally
	protected $data;
	public $module;
	public $controller;
	public $method;

	public function MY_Controller()
	{
		parent::__construct();
	}

	protected function is_ajax()
	{
            ;
	}
}

