<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
        //$this->ci->load->model('common/common_m.php');
    }

    
}
/* End of file common.php */
/* Location: ./application/libraries/common.php */
