<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * @link http://www.php-chongqing.com 
 * @author bing.peng 
 *  
 */
class Acl {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
     *
     */
    public function auth() {
        
    }
}

/* End of file acl.php */
/* Location: ./application/libraries/acl.php */