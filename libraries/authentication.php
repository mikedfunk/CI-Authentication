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
		
		// set remember_me checkbox cookie
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
	 * do_register function.
	 *
	 * adds user to db with updated/added values, emails the user, redirects.
	 * 
	 * @access public
	 * @return void
	 */
	public function do_register()
	{
		// load resources
		$this->_ci->load->model('authentication_model', 'auth_model');
		$this->_ci->load->helper(array('encrypt_helper', 'string', 'url'));
		
		// set a new salt, encrypt the password, set a new confirm_string
		$user = $this->_ci->input->post();
		unset($user['confirm_password']);
		$salt = random_string('alnum', config_item('salt_length'));
		$user[config_item('role_id_field')] = config_item('user_role_id');
		$user[config_item('password_field')] = encrypt_this($this->_ci->input->post(config_item('password_field')), $salt);
		$user[config_item('confirm_string_field')] = $confirm_string = random_string('alnum', 20);
		
		// add the user
		$check = $this->_ci->auth_model->add_user($user);
		
		// email the user
		$this->_ci->load->library('email');
		$config['mailtype'] = 'html';
		$this->_ci->email->initialize($config);

		// from, to, url, content
		$this->_ci->email->from(config_item('register_email_from'), config_item('register_email_from_name'));
		$this->_ci->email->to($user[config_item('username_field')]); 
		$data['confirm_register_url'] = base_url() . config_item('confirm_register_url') . '/' . $confirm_string;
		$data['content'] = $msg = $this->_ci->load->view(config_item('email_register_view'), $data, TRUE);
		
		// wrap email in template if it exists
		if (config_item('email_template_view') != '')
		{
			$msg = $this->_ci->load->view('email_template_view', $data, TRUE);
		}
		
		// subject, msg, send
		$this->_ci->email->subject(config_item('register_email_subject'));
		$this->_ci->email->message($msg);
		$this->_ci->email->send();
		
		// redirect to register_success view
		redirect(config_item('register_success_url'));
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * do_confirm_register function.
	 *
	 * if confirm string matches, clears string from user and redirects to
	 * configured url. Else redirects to configured fail url.
	 * 
	 * @access public
	 * @param mixed $confirm_string
	 * @return void
	 */
	public function do_confirm_register($confirm_string)
	{
		// check for user with confirm_string
		$this->_ci->load->model('authentication_model', 'auth_model');
		$this->_ci->load->helper('url');
		$q = $this->_ci->auth_model->get_user_by_confirm_string($confirm_string);
		
		// on match
		if ($q->num_rows > 0)
		{	
			// remove confirm string from user
			$r = $q->row();
			$user = array(
				'id' => $r->id,
				config_item('confirm_string_field') => ''
			);
			$this->_ci->auth_model->edit_user($user);
			
			// redirect to confirm success page
			redirect(config_item('confirm_success_url'));
		}
		// on no match
		else
		{
			// redirect to confirm fail page
			redirect(config_item('confirm_fail_url'));
		}
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