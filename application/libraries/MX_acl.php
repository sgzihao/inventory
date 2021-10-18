<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MX_ACL - Access Control Library PHP5
 *
 * Notes:
 * $config['cache_path'] must be set
 *
 * Install this file as application/libraries/MX_ACL.php
 *
 * @copyright    Copyright (c) Wiredesignz & Maxximus 2009-11-03
 * @version     1.1
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class MX_ACL {

    private $_config, $_cache_path;

    public function __construct() {

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->config('mx_acl', TRUE);

        $this->_config = $this->config->item('mx_acl');
        $this->_cache_path = $this->config->item('cache_path');

        /* previous flashdata is available to views */
        $this->load->vars($this->_config['error_var'], $this->session->flashdata($this->_config['error_var']));

        /* run the access control check now */
        ($this->_config['check_uri']) AND $this->check_uri();
    }

    /**
     * Check the current uri and user privileges against the cached ACL array
     * Redirect if access is denied
     *
     * @return void
     */
    public function check_uri() {

        /* Load the cached access control list or show error */
        (is_file($cached_acl = $this->_cache_path . 'mx_acl' . EXT)) OR show_error($this->_config['error_msg']);

        $acl = include $cached_acl;

        /* Match current url to access list */
        if (is_array($acl) AND $acl = $this->match_uri($this->current_uri(), $acl)) {

            /* Check session group against access level group */
            $allow_access = (bool) (in_array($this->session->userdata($this->_config['session_var']), $acl['allowed']));

            /* Additional check to allow IP addresses in range */
            if (!$allow_access AND isset($acl['ipl']))
                $allow_access = $this->check_ip($acl['ipl']);

            if ($allow_access == FALSE) {

                /* Set a return url into the session */
                $this->session->set_userdata('return_url', $this->uri->uri_string());

                /* set the error message... */
                $error_msg = (isset($acl['error_msg'])) ? $acl['error_msg'] : $this->_config['error_msg'];

                /* set a flash message... */
                $this->session->set_flashdata($this->_config['error_var'], $error_msg);

                /* redirect to absolute url */
                die(header("Location: " . $acl['error_uri'], TRUE, 302));
            }
        }
    }

    /**
     * Return the access control profile for a given url
     *
     * @return string
     * @param string $current_uri
     * @param array  $acl
     */
    private function match_uri($current_uri, $acl) {
        if (array_key_exists($current_uri, $acl)) {
            return $acl[$current_uri];
        } else {
            if ($pos = strripos($current_uri, '/')) {
                return $this->match_uri(substr($current_uri, 0, $pos), $acl);
            }
        }
    }

    /**
     * Returns the current uri string from segments
     *
     * @return string
     */
    private function current_uri() {
        return implode('/', $this->uri->rsegments);
    }

    /**
     * Checks the remote IP address against the specified $ipl array
     *
     * @return bool
     * @param array $ipl
     * @param string $remote_ip[optional]
     */
    private function check_ip($ipl, $remote_ip = NULL) {

        /* Convert ip address into a double (for lousy OSes) */
        $remote_ip = floatval(ip2long(($this->session->userdata('ip_address'))));

        /* Loop through the ip list array */
        foreach ($ipl as $allowed_ip) {

            /* Replace '*' (for IP ranges) with a suitable range number */
            $min = str_replace("*", "0", $allowed_ip);
            $max = str_replace("*", "255", $allowed_ip);

            /* Check for a match */
            if (($remote_ip >= floatval(ip2long($min))) AND ($remote_ip <= floatval(ip2long($max)))) {
                return TRUE;
            }
        }
    }

    public function __get($var) {
        static $CI;
        (is_object($CI)) OR $CI = get_instance();
        return $CI->$var;
    }

}

/* End of file MX_ACL.php */
/* Location: ./application/libraries/MX_ACL.php */