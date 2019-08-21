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

require_once('core/core_database.php');
require_once('core/core_sql.php');
require_once('core/core_query.php');
require_once('core/core_user.php');
require_once('core/core_html.php');

session_start();

try {

	$page = new html();

	// If user has clicked forgot password link display forgot password form.
	if(isset($_GET['resetpasswd'])) {
		$page->display_forgot_password();
	} 
	
	// If only the username is set then reset password.
	if (isset($_POST['username']) && !(isset($_POST['password']))) {
		$username = $_POST['username'];
		$page->forgot_password($username);
	} 
	
	// If both the username and password are set then verify and set they are a valid user.
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		$page->set_valid_user($username, $password);
	}
	
	$page->check_valid_user(); 

	header("location:dashboard.php");
	
}
catch(loginException $e) {
	$page->set_title("DBCE: Login Error");
	$page->do_html_header();
	$page->display_login_form();
	printf('<div class="w3-center">%s</div>', $e->errorMessage());
	exit;
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>