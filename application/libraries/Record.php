<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Message:: a library for giving feedback to the user
 *
 * @author  Adam Jackett
 * @url http://www.darkhousemedia.com/
 * @version 2.1
 */
class Record {

    public $CI;

    public function __construct() {
        //parent::__construct();
        $this->CI = & get_instance();
        //$this->CI->load->library('database');
        $this->CI->load->model('record/record_m', 'model_record');
    }
    public function add($data) {
        $this->CI->model_record->insertNewRecordLog($data);
        return true;
    }

}

