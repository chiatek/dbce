<?php
/*
MIT License

Copyright (c) 2019 Chiatek

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

class user extends query
{
    protected $firstname;
    protected $lastname;
    protected $website;
    protected $avatar;
    protected $links;
    protected $notifications;
    protected $message;
    private $forgot_password = false;

    public function set_message($message) {
        $this->message = $message;
    }

    // Query database to check for valid user given username and password.
    // If valid user set session variable to the username.
    public function set_valid_user($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $user = $this->query_assoc_row("userpass_sql");
        if ($user) {
            $_SESSION['valid_user'] = $user["username"];
        }
        else {
            throw new loginException("Invalid username and/or password. Please try again.");
        }
    }

    // Check if someone is logged and and notify them if not.
    public function check_valid_user() {
        if (isset($_SESSION['valid_user'])) {
            // They are logged in. Set session variable and user info.
            $this->username = $_SESSION['valid_user'];
            $this->set_user_info();
        }
        else if($this->forgot_password) {
            // They are not logged in and forgot password.
            $this->set_title("DBCE: Reset Password");
            $this->do_html_header();
            $this->display_forgot_passwd_form();
            exit;            
        }
        else {
            // They are not logged in.
            $this->set_title("DBCE: Login");
            $this->do_html_header();
            $this->display_login_form();
            exit;
        }
    }

    // Set user info from table portal.user
    public function set_user_info() {
        $user = $this->query_assoc_row("userinfo_sql");
        if ($user) {  
            $this->password = $user["password"];
            $this->firstname = $user["firstname"];
            $this->lastname = $user["lastname"];
            $this->website = $user["website"];
            $this->database = $user["dbase"];
        }
        else {
            throw new Exception("Unable to get user info (set_user_info).");
        }
    }

    // Set user avatar from table portal.user
    public function set_user_avatar() {
        $avatar = $this->query_object("useravatar_sql");
        if ($avatar) {
            $this->avatar = $avatar->image;
		}
		else {
			throw new Exception("User avatar not found (set_user_avatar).");
		}
    }

    // Set user links from table portal.links
    public function set_user_links() {
        $links = $this->query_assoc("userlinks_sql");
        if ($links) {
            $this->links = $links;
        }
    }

    // set user notifications from table portal.notifications
    public function set_user_notifications() {
        $notifications = $this->query_assoc("usernotifications_sql");
        if ($notifications) {
            $this->notifications = $notifications;
        }
    }

    // User has click forgot password. Set to true to display forgot_password_form
    public function display_forgot_password() {
        $this->forgot_password = true;
    }   
    
    // Set username and temp password and call reset password.
    public function forgot_password($username) {
        $this->username = $username;
        $this->password = sha1("temp_passwd");
            
        $this->reset_password();
    }

    // Function to change user password and update database given the current and new password.
    public function change_user_password($password, $new, $repeat) {
        if(strcmp(sha1($password), $this->password) == 0) {
            if(strcmp($new, $repeat) == 0) {
                $input = array();
                $input['password'] = sha1($new);
                $this->set_prepared_update("passwordtype_sql", $input, "users", "username", $this->username);
                $this->set_message("Your password has been changed successfully"); 
                return true;     
            }
            else {
                $this->set_message("Passwords do not match. Please try again.");
                return false;
            }
        }
        else {
            $this->set_message("Invalid password. Please try again."); 
            return false;
        }
    }

    // Function to reset the password using a random number and string.
    public function reset_password() {

        $new_password = $this->get_random_string();

        // add a number  between 0 and 999 to it
        // to make it a slightly better password
        $rand_number = rand(0, 999);
        $new_password .= $rand_number;

        // set user's password to this in database or return false
        $result = $this->change_user_password("temp_passwd", $new_password, $new_password);
        if($result) {
            $this->password = $new_password;  // changed successfully
            $this->notify_password();
        }
    }
  
    // Function to notify the new password via email if successful.
    public function notify_password() {

        $result = $this->query_assoc("userinfo_sql");

        if (!$result) {
            throw new loginException('Invalid username. Please try again.');
        } 
        else if ($result->num_rows == 0) {
            throw new loginException('Invalid username. Please try again.');
        } 
        else {
            $row = $result->fetch_object();
            $email = $row->email;
            $from = "From: support@chiatek.com \r\n";
            $mesg = "Your Portal password has been changed to ".$this->password."\r\n"
                ."Please change it next time you log in.\r\n";

            if (mail($email, 'Portal login information', $mesg, $from)) {
                print("Your password has been emailed you.");
            } 
            else {
                throw new Exception('Could not send email.');
            }
            throw new loginException("Your password has been emailed you.");
        }
    }

}

class loginException extends Exception {

    public function errorMessage() {
        return $this->getMessage();
    }
}

?>