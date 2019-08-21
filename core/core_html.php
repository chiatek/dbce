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

class html extends user
{
    protected $linkid = 0;
    private $linkname;
    private $linkicon;
    private $title = "DBCE";
	protected $version = "1.0.1";

    public function set_link($linkid) { 
		$this->linkid = $linkid;
    }
    
    public function set_title($title) {
        $this->title = $title;
    }

    public function get_link() {
        return $this->linkid;
    }
	
	public function do_html_header() {
    
        printf('<title>%s</title>
        <meta charset="utf-8">
        <meta name="description" content="DBCE">
        <meta name="keywords" content="">
        <meta name="author" content="chiatek">	
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <!-- Favicon Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
        <link rel="icon" type="image/png" href="assets/images/favicon.png">
        <link rel="apple-touch-icon" href="assets/images/favicon.png">
        
        <!-- CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-lite.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/w3.css">
        <link rel="stylesheet" href="assets/css/style.css">', $this->title);	
    }

    public function do_html_footer() {
        print('<div><h5><strong>Copyright</strong> Chiatek &copy; 2018</h5></div>');
    }

    public function display_navmenu() {
        $this->set_user_notifications();

        print('<div class="w3-bar nav">
            <button class="w3-button w3-round-medium w3-large w3-left teal hover-teal sidebar-toggle-btn">
                <i class="fa fa-bars"></i>
            </button>
            <form class="nav-search left" action="search.php" method="GET">
                <input type="text" class="w3-bar-item w3-input" name="search" id="search" placeholder="Search for something...">
            </form> 

            <div class="nav-link w3-right">');
            if($this->notifications) {
                printf('<button class="w3-button" onclick="dropdown()" title="Notifications"><i class="fa fa-bell"></i><span class="w3-badge w3-right w3-small w3-green">%s</span></button>     
                <div id="dropdown" class="w3-dropdown-content w3-card-4 w3-bar-block">', $this->notifications->num_rows);
                while($row = $this->notifications->fetch_assoc()) {    
                    printf('<a href="modify.php?link=0&ptbl=notifications&pk=notificationID&pid=%s" class="w3-bar-item w3-button">%s</a>', $row["notificationID"], $row["title"]);
                }
            } 
            else {
                printf('<button class="w3-button" onclick="dropdown()" title="Notifications"><i class="fa fa-bell"></i><span class="w3-badge w3-right w3-small w3-green">%s</span></button>     
                <div id="dropdown" class="w3-dropdown-content w3-card-4 w3-bar-block">
                <a href="#" class="w3-bar-item w3-button">%s</a>', 0, "No new notifications");
            }          
            print('</div></div>
            <a href="logout.php" class="w3-right">
                <i class="fa fa-sign-out nav-icon"></i><span class="nav-txt"> Log out</span>
            </a>
            <a href="settings.php" class="w3-right">
                ​<i class="fa fa-gear nav-icon"></i><span class="nav-txt"> Settings</span>
            </a>
            </div>');
    } 
    
    public function display_sidebar() { 
        $this->set_user_avatar();
        $this->set_user_links(); 
    
        printf('<div class="sidebar-wrapper">
            <img src="%s" alt="profile image" class="profile-image">
            <h3 class="username sidebar-txt">%s %s</h3>
            <h4 class="domain sidebar-txt"><a href="http://%s" target="_blank" class="profile-link">%s</a></h4>
            <div class="sidebar-options">
                <a href="search.php" class="sidebar-btn"><i class="fa fa-search"></i></a>
                <a href="settings.php" class="sidebar-btn"><i class="fa fa-gear"></i></a>
                <a href="logout.php" class="sidebar-btn"><i class="fa fa-sign-out"></i></a>
            </div>', $this->avatar, $this->firstname, $this->lastname, $this->website, $this->website);

        print('<div class="sidebar-nav">');		
            if($this->linkid === 0) {
                print('<a href="dashboard.php" class="sidebar-btn active"><i class="fa fa-th-large sidebar-icon"></i><span class="sidebar-txt"> Dashboard</span></a>');
            }
            else {
                print('<a href="dashboard.php" class="sidebar-btn"><i class="fa fa-th-large sidebar-icon"></i><span class="sidebar-txt"> Dashboard</span></a>');
            }
            if($this->links) {
                while($row = $this->links->fetch_assoc()) {
                    if($this->linkid === $row["linkID"]) {
                        printf('<a href="view.php?link=%s" class="sidebar-btn active"><i class="%s sidebar-icon"></i><span class="sidebar-txt"> %s</span></a>', $row["linkID"], $row["description"], $row["linkname"]);	
                    }
                    else {
                        if($row["dashboard"] != "Y") {
                            printf('<a href="view.php?link=%s" class="sidebar-btn"><i class="%s sidebar-icon"></i><span class="sidebar-txt"> %s</span></a>', $row["linkID"], $row["description"], $row["linkname"]);		
                        }			
                    }							
                }
            } 
            else {
                print('<a href="navlink.php" class="sidebar-btn"><i class="fa fa-link sidebar-icon"></i><span class="sidebar-txt"> Add a Link</span></a>');
            }	
        print('</div></div>');	
    }
    
    public function display_breadcrumb($link1, $link2 = null, $link3 = null) {
	
        print('<br>');
        if($link1 && $link2 && $link3) {
            printf('<h2>%s</h2><h5>%s / %s / <strong>%s</strong></h5>', $link3, $link1, $link2, $link3);
        }
        else if($link1 && $link2) {
            printf('<h2>%s</h2><h5>%s / <strong>%s</strong></h5>', $link2, $link1, $link2);
        }
        else {
            printf('<h2>%s</h2>', $link1);
        }
        print('<br>');
    }
    
    public function display_welcome() {
        print('<div class="w3-container">');
        if($this->notifications) {
            $notifications = $this->notifications->num_rows;
        }
        else {
            $notifications = 0;
        }
        
        if($notifications == 1) {
            printf('<br>
            <h2>Welcome %s</h2>
            <h5>You have %s new notification</h5>
            <br>', ucfirst($this->firstname), $notifications);
        }
        else {
            printf('<br>
            <h2>Welcome %s</h2>
            <h5>You have %s new notifications</h5>
            <br>', ucfirst($this->firstname), $notifications);
        }
        print('</div>');
    }

    public function display_snackbar() {
        if (isset($this->message)) {
            printf('<script> window.onload = function() {
                snackbar();
            };
            </script><div id="snackbar">%s</div>', $this->message);
        }
    }

    public function display_login_form() {
    
        print('<form action="index.php" method="post">
            <div class="login">
                <img class="login-logo" src="assets/images/logo.png" alt="logo"><h3 class="login-title">Welcome to DBCE</h3><br>
                <div>
                    <input class="border login-input" name="username" id="username" type="text" placeholder="username">
                </div>
                <div>
                    <input class="border login-input" name="password" id="password" type="password" placeholder="password">
                </div>
                <p><button class="w3-button w3-round-medium teal hover-teal login-input" type="submit" name="login">Login</button></p>
                <p class="w3-center h5"><a href="index.php?resetpasswd=true">Forgot password?</a></p>
            </div>
        </form>');	
    }

    public function display_forgot_passwd_form() {
    
        print('<form action="index.php" method="post">
            <div class="login">
                <img class="login-logo" src="assets/images/logo.png" alt="logo"><h3 class="login-title">Welcome to DBCE</h3><br>
                <div>
                    <input class="w3-input border login-input" name="username" id="username" type="text" placeholder="Enter your username" required>
                </div>
                <p><button class="w3-button w3-round-medium teal hover-teal login-input" type="submit" name="resetpswd-btn">Reset Password</button></p>
            </div>
        </form>');	
    }

    public function display_change_passwd_form() {
        print('<div class="card-body"><br>
            <div class="w3-col m3">
                <h5 class="card-txt">Current Password</h5>
            </div>
            <div class="w3-col m9">
                <input class="w3-input border" name="password" id="password" type="password" maxlength="40" required>
            </div>
            <div class="clearfix"></div><br>
            <div class="w3-col m3">
                <h5 class="card-txt">New Password</h5>
            </div>
            <div class="w3-col m9">
                <input class="w3-input border" name="new" id="new" type="password" maxlength="40" required>
            </div>
            <div class="clearfix"></div><br>
            <div class="w3-col m3">
                <h5 class="card-txt">Repeat New Password</h5>
            </div>
            <div class="w3-col m9">
                <input class="w3-input border" name="repeat" id="repeat" type="password" maxlength="40" required>
            </div><div class="clearfix"></div><br>');

        print('</div><br>');
    }
    
    public function format_column($string, $maxchars) {
        $paragraph = mb_strimwidth($string, 0, $maxchars, "...");
		$paragraph = str_replace("\\r\\n", " ", $paragraph);
        $paragraph = htmlspecialchars($paragraph);

		return $paragraph;
    }

    public function format_search($string) {
        $paragraph = htmlspecialchars($string);
        $paragraph = $paragraph."<br><br>";

		return $paragraph;
    }
    
    public function get_summary($string) {
        $summary = mb_strimwidth($string, 0, 250, "...");
        $summary = $summary."<br><br>";
        return $summary;
    }

    public function get_random_string() {
        $length = 10;

        $random_string = substr(str_shuffle("!@#$%^&*()0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        return $random_string;
    }
}
?>