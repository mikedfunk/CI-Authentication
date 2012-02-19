<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * authentication_config
 * 
 * All configurable values for the authentication package
 * 
 * @license		http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author		Mike Funk
 * @link		http://mikefunk.com
 * @email		mike@mikefunk.com
 * 
 * @file		authentication_config.php
 * @version		1.0
 * @date		02/17/2012
 * 
 * Copyright (c) 2012
 */

// --------------------------------------------------------------------------
/**
 * users_table
 *
 * the table to pull users from
 */
$config['users_table'] = 'users';

// --------------------------------------------------------------------------
/**
 * roles_table
 *
 * the table to pull roles from
 */
$config['roles_table'] = 'roles';

// --------------------------------------------------------------------------
/**
 * username_field
 *
 * the field in the db and session used for username
 */
$config['username_field'] = 'email_address';

// --------------------------------------------------------------------------
/**
 * password_field
 *
 * the field in the db and session used for password
 */
$config['password_field'] = 'password';

// --------------------------------------------------------------------------
/**
 * remember_me_field
 *
 * the field checked in post and used as a cookie name
 */
$config['remember_me_field'] = 'remember_me';

// --------------------------------------------------------------------------
/**
 * home_page_field
 *
 * the field in the db and session used for home_page (in the roles table)
 */
$config['home_page_field'] = 'home_page';

// --------------------------------------------------------------------------
/**
 * confirm_string_field
 *
 * the field in the db assigned to a user that has not yet confirmed 
 * their registration via email
 */
$config['confirm_string_field'] = 'confirm_string';

// --------------------------------------------------------------------------
/**
 * role_id_field
 *
 * the field used to join role_id (in the users table)
 */
$config['role_id_field'] = 'role_id';

// --------------------------------------------------------------------------
/**
 * user_role_id (int)
 *
 * the role id for users to be assigned
 */
$config['user_role_id'] = 1;

// --------------------------------------------------------------------------
/**
 * remember_me_timeout (int)
 *
 * the time, in seconds, that the remember_me cookie lasts
 */
$config['remember_me_timeout'] = 60 * 60 * 24 * 365;

// --------------------------------------------------------------------------
/**
 * salt_length (int)
 *
 * the length of the salt string to be added to / parsed from the password
 */
$config['salt_length'] = 64;

// --------------------------------------------------------------------------
/**
 * register_email_from
 *
 * the reply-to email address for registration emails
 */
$config['register_email_from'] = 'noreply@test.com';

// --------------------------------------------------------------------------
/**
 * register_email_from_name (optional)
 *
 * the reply-to email address name for registration emails
 */
$config['register_email_from_name'] = 'Bookymark';

// --------------------------------------------------------------------------
/**
 * register_email_subject
 *
 * the reply-to email address for registration emails
 */
$config['register_email_subject'] = 'Registration';

// --------------------------------------------------------------------------
/**
 * email_register_view
 *
 * the inner view used for sending registration emails
 */
$config['email_register_view'] = 'email_register_view';

// --------------------------------------------------------------------------
/**
 * email_template_view
 *
 * the outer view used for sending registration emails
 */
$config['email_template_view'] = 'email_template_view';

// --------------------------------------------------------------------------
/**
 * logged_out_url
 *
 * where to redirect when login_check fails
 */
$config['logged_out_url'] = 'home/login?notification=logged_out';

// --------------------------------------------------------------------------
/**
 * logout_success_url
 *
 * where to redirect on logout
 */
$config['logout_success_url'] = 'home/login?notification=logout_success';

// --------------------------------------------------------------------------
/**
 * register_success_url
 *
 * where to redirect on register success
 */
$config['register_success_url'] = 'home/register_success';

// --------------------------------------------------------------------------
/**
 * confirm_register_url
 *
 * the url of the controller method that checks the confirmation email link.
 * without the trailing slash.
 */
$config['confirm_register_url'] = 'home/confirm_register';

// --------------------------------------------------------------------------
/**
 * confirm_success_url
 *
 * where to redirect on confirm success
 */
$config['confirm_success_url'] = 'home/login?notification=confirm_success';

// --------------------------------------------------------------------------
/**
 * confirm_fail_url
 *
 * where to redirect on confirm fail
 */
$config['confirm_fail_url'] = 'home/login?notification=confirm_fail';

// --------------------------------------------------------------------------
/* End of file authentication_config.php */
/* Location: ./bookymark/application/third_party/authentication/config/authentication_config.php */