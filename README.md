CI Authentication
============================

An authentication system for CodeIgniter.

Setup
----------------------------

1. Clone this into ```application/third_party```
2. Add this to the ```$autoload['packages']``` array in ```application/config/autoload.php```:  ```APPPATH.'third_party/error_arrays'```
3. Import ```setup.sql``` in PHPMyAdmin or something
4. Add a user row
5. Add a role row
6. Edit ```config/authentication_config.php``` with the proper stuff like redirect urls, etc.

Restrict
----------------------------

At the top of a CI controller method or in ```_remap()``` do this:

    $this->load->library('authentication');
    $this->authentication->restrict_access();

It will redirect to ```home/login?notification=logged_out``` by default. You can change this in the config.

Remember Me
----------------------------

At the top of a CI controller method that does form validation insert this:

    $this->load->library('authentication');
    $this->authentication->remember_me();
    
If the "remember me" checkbox is checked, it will save the username, password, and checked status of the remember me checkbox to cookies. You can use ```get_cookie('email_address')``` or whatever to load the form values into your login form by default.

Do Login
----------------------------

When form validation passes, just do this:

    $this->load->library('authentication');
    $this->authentication->do_login();
    
It will hash and salt the password, log in the user (add username, user id, and encrypted password to the session), and redirect to the configured home page in the roles table.

Do Logout
----------------------------

For the logout controller method, do this:

    $this->load->library('authentication');
    $this->authentication->do_logout();
    
It will destroy the session and redirect to the configured logged out url.

To Do
----------------------------

* Add method of signing up