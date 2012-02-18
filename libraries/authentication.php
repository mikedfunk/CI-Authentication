<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * authentication
 * 
 * Tools for authentication in CodeIgniter.
 * 
 * @license		http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author		Mike Funk
 * @link		http://mikefunk.com
 * @email		mike@mikefunk.com
 * 
 * @file		authentication.php
 * @version		1.0
 * @date		02/17/2012
 * 
 * Copyright (c) 2012
 */

// --------------------------------------------------------------------------

/**
 * authentication class.
 */
class authentication
{
	// --------------------------------------------------------------------------
	
	/**
	 * _ci
	 *
	 * The codeigniter superobject
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_ci;
	
	// --------------------------------------------------------------------------
	
	/**
	 * __construct function.
	 *
	 * load common resources
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->_ci =& get_instance();
		log_message('debug', 'Authentication: library initialized.');
		$this->_ci->config->load('authentication_config');
		log_message('debug', 'Authentication: config loaded.');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * restrict_access function.
	 *
	 * if not logged in, redirects to configured url.
	 * 
	 * @access public
	 * @return void
	 */
	public function restrict_access()
	{
		// load resources
		$this->_ci->load->model('authentication_model', 'auth_model');
		$this->_ci->load->library('session');
		$this->_ci->load->helper('url');
		
		// check for password match, else redirect
		$chk = $this->_ci->auth_model->password_check(
			$this->_ci->session->userdata(config_item('username_field')), 
			$this->_ci->session->userdata(config_item('password_field'))
		);
		
		if (!$chk)
		{
			redirect(config_item('logged_out_url'));
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * remember_me function.
	 *
	 * sets cookies if remember me is checked, otherwise deletes cookies.
	 * 
	 * @access public
	 * @return void
	 */
	public function remember_me()
	{
		// set remember_me to 0 if not checked and there is post data
		if (!$this->_ci->input->post(config_item('remember_me_field')) && $this->_ci->input->post() !== false)
		{
			$_POST[config_item('remember_me_field')] = 0;
		}
		
		// set remember_me cookie
		$this->_ci->input->set_cookie(
			config_item('remember_me_field'), 
			$this->_ci->input->post(config_item('remember_me_field')), 
			(config_item('remember_me_timeout'))
		);
		
		// if remember_me is true, remember, remember the 5th of November
		if ($this->_ci->input->post(config_item('remember_me_field')))
		{
			// set username cookie
			$this->_ci->input->set_cookie(
				config_item('username_field'), 
				$this->_ci->input->post(config_item('username_field')), 
				(config_item('remember_me_timeout'))
			);
			
			// set password cookie
			$this->_ci->input->set_cookie(
				config_item('password_field'), 
				$this->_ci->input->post(config_item('password_field')), 
				(config_item('remember_me_timeout'))
			);
		}
		// otherwise remember_me is false. fuggedaboutit. delete cookie.
		else
		{
			$this->_ci->input->set_cookie(config_item('username_field'), '', time() -1);
			$this->_ci->input->set_cookie(config_item('password_field'), '', time() -1);
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * do_login function.
	 *
	 * hashes and salts password, logs in user to session, redirects to 
	 * configured url
	 * 
	 * @access public
	 * @return bool
	 */
	public function do_login()
	{
		$this->_ci->load->model('authentication_model', 'auth_model');
		$this->_ci->load->helper(array('encrypt_helper', 'string', 'url'));
		$this->_ci->load->library('session');
		
		// set session vars, redirect to admin home
		$q = $this->_ci->auth_model->get_user_by_username($this->_ci->input->post(config_item('username_field')));
		$user = $q->row_array();
		
		$q = $this->_ci->auth_model->get_user_by_username($this->_ci->input->post(config_item('username_field')), FALSE);
		$user_only = $q->row_array();
		
		// set a new salt, re-encrypt the password
		$salt = random_string('alnum', config_item('salt_length'));
		$user[config_item('password_field')] = encrypt_this($this->_ci->input->post(config_item('password_field')), $salt);
		
		// edit the user and set new userdata
		$check = $this->_ci->auth_model->edit_user($user_only);
		$this->_ci->session->set_userdata($user);
		
		// log errors
		if (!$check) {log_message('error', 'Authentication: error editing user during login.');}
		
		redirect($this->_ci->session->userdata(config_item('home_page_field')));
	}

	// --------------------------------------------------------------------------

	/**
	 * do_logout
	 *
	 * destroys session, redirects to configured url.
	 *
	 * Destroys the session
	 *
	 * @access public
	 * @return bool
	 */
	public function do_logout()
	{
		$this->_ci->load->library('session');
		$this->_ci->load->helper('url');
		
		$this->_ci->session->sess_destroy();
		redirect(config_item('logout_success_url'));
	}
	
	// --------------------------------------------------------------------------
}
/* End of file authentication.php */
/* Location: ./bookymark/application/third_party/authentication/libraries/authentication.php */