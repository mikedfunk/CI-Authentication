<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * authentication_helper
 * 
 * shortcuts for username and password from the session
 * 
 * @license		http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author		Mike Funk
 * @link		http://mikefunk.com
 * @email		mike@mikefunk.com
 * 
 * @file		authentication_helper.php
 * @version		1.0
 * @date		02/17/2012
 * 
 * Copyright (c) 2012
 */

// --------------------------------------------------------------------------

/**
 * auth_username function.
 *
 * username from session with configurable username field value
 * 
 * @access public
 * @return void
 */
function auth_username()
{
	$_ci =& get_instance();
	$_ci->load->library('session');
	$_ci->config->load('authentication_config');
	
	return $_ci->session->userdata(config_item('username_field'));
}

// --------------------------------------------------------------------------

/**
 * auth_password function.
 *
 * password from session with configurable password field value
 * 
 * @access public
 * @return void
 */
function auth_password()
{
	$_ci =& get_instance();
	$_ci->load->library('session');
	$_ci->config->load('authentication_config');
	
	return $_ci->session->userdata(config_item('password_field'));
}

// --------------------------------------------------------------------------

/* End of file authentication_helper.php */
/* Location: ./bookymark/application/third_party/authentication/helpers/authentication_helper.php */