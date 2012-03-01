
CI Authentication
============================

An authentication system for CodeIgniter.

Setup
----------------------------

1. Clone this into **application/third_party/authentication**
2. Update submodules: ```git submodule update --init --recursive```
3. Add this to the ```$autoload['packages']``` array in **application/config/autoload.php**:  ```APPPATH.'third_party/authentication', APPPATH.'third_party/authentication/third_party/alerts', ```
4. Copy ***config/authentication_config.php*** file to ***application/config/authentication.php*** **(NOTE: Must be renamed to match library name!)**
7. Edit ***application/config/authentication.php*** with the proper stuff like redirect urls, etc.
4. Import **setup.sql** in PHPMyAdmin or something

Restrict
----------------------------

At the top of a CI controller method or in ```__construct()``` do this:

    $this->load->library('authentication');
    $this->authentication->restrict_access();

It will redirect to the config_item for logged_out_url.

You can also add a parameter to restrict access to pages based on permissions for that role. For instance ```$this->authentication->restrict_access('can_edit_pages')```. It checks the session for this variable. If it's false it will redirect to the config item access_denied_url.

----------------------------

Login
============================

Do Login
----------------------------

When form validation passes, just do this:

    $this->load->library('authentication');
    $this->authentication->do_login();
    
It will hash and salt the password, log in the user (add username, user id, and encrypted password to the session), and redirect to the configured home page in the roles table.

Remember Me
----------------------------

At the top of a CI controller method that does form validation insert this:

    $this->load->library('authentication');
    $this->authentication->remember_me();
    
If the "remember me" checkbox is checked, it will save the username, password, and checked status of the remember me checkbox to cookies. You can use ```get_cookie('email_address')``` or whatever to load the form values into your login form by default.

Do Logout
----------------------------

For the logout controller method, do this:

    $this->load->library('authentication');
    $this->authentication->do_logout();
    
It will destroy the session and redirect to the configured logged out url.

----------------------------

Registration
============================

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
7. In case you need it, sets the title for the error page based on config value
8. redirect to the configured registration success url

Do Confirm Register
----------------------------

You need to set a controller method for when a user clicks the confirmation link. In this method, just put this:

    $this->load->library('authentication');
    $this->authentication->do_confirm_register();

It will check if a confirmation string exists for the passed string. If so, it will remove that string and redirect to the confirm_register_success_url. Otherwise it will redirect to the confirm_register_fail_url.

Do Resend Register
----------------------------

If you want to allow resending of the registration email, you need to add a controller method and link to it somewhere. That method needs to accept the confirm_string as a parameter or POST variable or something. In that method, just do this:

    $this->load->library('authentication');
    $this->authentication->do_resend_register($confirm_string);

It will check if a confirmation string exists for the passed string. If so, it will resend the confirmation email and redirect to register_success_url.

----------------------------

Forgot Password
============================

Do Request Reset Password
----------------------------

You need to set a controller method for when a user requests to reset their password. In this method, just put this:

    $this->load->library('authentication');
    $this->authentication->do_request_reset_password($email_address);

You will need to pass the ```$email_address``` variable. You could get this from a reset form or a link. This method will email a password link with an encrypted string based on the email address. The user must click this link to confirm they want to reset their password. This prevents passwords being reset without the user's consent. In case you need it the title for the alert page is set in flashdata based on the config value. Then the user is redirected to the request_reset_password_url.

Do Confirm Reset Password
----------------------------

You need to set a controller method for when a user clicks the reset password confirmation link. In this method, just put this:

    $this->load->library('authentication');
    $this->authentication->do_request_reset_password();

This method will retrieve the username and encrypted string via $_GET variables. It will make sure the encrypted username matches the encrypted string, make sure a user exists with that username, set a new random password, email it to the user, update the user in the database with the new password (salted and encrypted), and redirect to the configured page.