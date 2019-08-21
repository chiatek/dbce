<?php
require_once('../core/core.php');

session_start();

try {

	$page = new settings();
	$page->set_title("DBCE: Settings");
	$page->check_valid_user();

	$page->use_dbms_database();
	$update_settings = false;
	
	// Set the side nav link for the current page.
	if(isset($_GET['link'])) {
		$page->set_link($_GET['link']);
	}

	// if user has clicked the change password button from user settings then display change password form.
	if(isset($_GET['chg_passwd'])) {
		$page->display_change_password();
	}

	// If table name, primary key, and value are set then set values for table 1. 
	// Enable update settings to diplay table from view.php.
	if (isset($_GET['ptbl']) && isset($_GET['pk']) && isset($_GET['pid'])) {
		$page->set_table1($_GET['ptbl'], $_GET['pk'], $_GET['pid']);
		$update_settings = true;
	}

	// If password is set then update the database with the new user password.
	if (isset($_POST['password']) && isset($_POST['new']) && isset($_POST['repeat'])) {
		$result = $page->change_user_password($_POST['password'], $_POST['new'], $_POST['repeat']);
		if(!$result) {
			$page->display_change_password();
		}
	}

	// If only the table name is set then update the links table for the given linkid.
	if (isset($_POST['ptbl'])) {
		$page->set_prepared_update("usertype_sql", $_POST, $_POST['ptbl'], $_POST['pk'], $_POST['pid']);
		$page->set_message("Nav links have been updated");
	}

	// If user is set then update the database for user settings.
	if(isset($_POST['user'])) {
		$page->set_prepared_update("clienttype_sql", $_POST, "users", "username", $_SESSION['valid_user']);
		$page->set_message("User settings have been updated");
	}

	// If avatarID is set then update the database for the avatar. 
	if(isset($_POST['avatarID'])) {
		$page->set_prepared_update("avatartype_sql", $_POST, "users", "username", $_SESSION['valid_user']);
		$page->set_message("Avatar has been changed");
	}

	// If delete is set then the user has clicked the delete button from the nav links tab.
	// Update the database and delete the selected nav links.
	if(isset($_POST['delete']) && isset($_POST['link'])) {
		$page->set_link($_POST['link']);
		$page->delete_query($_POST['delete']);
		if (count($_POST['delete']) == 1) {
			$page->set_message("Nav link has been deleted successfully");
		}
		else {
			$page->set_message("Nav links have been deleted successfully");
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<?php $page->do_html_header(); ?>

</head>

<body>

	<!-- Wrapper -->
	<div class="wrapper">

		<!-- Sidebar Wrapper -->
		<div class="sidebar-wrapper">
		
			<?php $page->display_sidebar(); ?>
			
		</div>
		<!-- End Sidebar Wrapper -->
		
		<!-- Page Wraper -->
		<div class="page-wrapper">
			
			<?php $page->display_navmenu(); ?>
		
			<!--Begin W3 Container -->
			<div class="w3-container">
			
				<?php $page->display_breadcrumb("Settings");  ?>
				
			</div>
			
			<div class="w3-container light-grey">
				<div class="window-padding">
				
					<?php 
						if($update_settings) {
							$page->set_modify_form();
						}
						else {
							$page->display_navigation_tab(); 
							$page->display_snackbar();
						}
						?>
							
				</div>
			</div>			
			<!-- End W3 Container -->

			<!-- Begin Footer -->	
			<div class="w3-container footer">
			
				<?php $page->do_html_footer(); ?>
				
			</div>
			<!-- End Footer -->
			
		</div>
		<!-- End Page Wrapper -->
	
	</div>
	<!-- End Wrapper -->
	
	<!-- Javascript -->
	<script src="assets/js/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="assets/js/main.js" type="text/javascript"></script>

</body>
</html>

<?php
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>