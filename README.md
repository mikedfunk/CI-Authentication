CI Authentication
============================

An authentication system for CodeIgniter.

Setup
----------------------------

1. Clone this into **application/third_party**
2. Add this to the ```$autoload['packages']``` array in **application/config/autoload.php**:  ```APPPATH.'third_party/error_arrays'```
3. Import **setup.sql** in PHPMyAdmin or something
4. Add a user row
5. Add a role row
6. Edit **config/authentication_config.php** with the proper stuff like redirect urls, etc.

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

Do Register
----------------------------

When form validation passes for registration, do this:

    $this->load->library('authentication');
    $this->authentication->do_register();
    
It will:

1. unset confirm_password
2. set a new salt and encrypt the password
3. set the role id
4. set a new confirm string
5. add the user
6. email the user with a confirm registration link (email view, template, from, subject, etc. are all configurable in authentication_config.php)
7. redirect to the configured registration success url

Do Confirm Register
----------------------------

You need to set a controller method for when a user clicks the confirmation link. In this method, just put this:

    $this->load->library('authentication');
    $this->authentication->do_confirm_register();

It will check if a confirmation string exists for the passed string. If so, it will remove that string and redirect to the confirm_register_success_url. Otherwise it will redirect to the confirm_register_fail_url.

To Do
----------------------------

1. Forgot password system
2. Ability to re-send confirmation email