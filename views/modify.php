<?php
require_once('../core/core.php');

session_start();

try {

	$page = new modify();
	$page->set_title("DBCE: Modify Record");
	$page->check_valid_user();

	// Set the side nav link for the current page.
	if(isset($_GET['link'])) {
		$page->set_link($_GET['link']);
	}

	// User has clicked a table row link from view.php for a specific database result.
	if(isset($_GET['ptbl']) && isset($_GET['ftbl']) && isset($_GET['pk']) && isset($_GET['fk']) && isset($_GET['pid']) && isset($_GET['fid'])) {
		// Set table 1 and table 2 name, primary key, and value.
		$page->set_table1($_GET['ptbl'], $_GET['pk'], $_GET['pid']);
		$page->set_table2($_GET['ftbl'], $_GET['fk'], $_GET['fid']);
	}
	else {
		if (isset($_GET['ptbl']) && isset($_GET['pk']) && isset($_GET['pid'])) {
			// Set table 1 name, primary key, and value. Table 2 is not set.
			$page->set_table1($_GET['ptbl'], $_GET['pk'], $_GET['pid']);
		}
	}

	// Get user input for table 1 only.
	if (isset($_POST['ptbl'])) {
		if($_POST['ptbl'] == "notifications") {
			// Update the database for table "notifications" only.
			$page->set_query($_POST['pid']);
			$page->update_query("notifydismiss_sql");
			header("Refresh:0; url=index.php");
		}
		else {
			// Update the database for the specified table and primary key.
			$page->set_prepared_update("datatype_sql", $_POST, $_POST['ptbl'], $_POST['pk'], $_POST['pid']);
			$page->set_link($_POST['link']);
			$page->set_table1($_POST['ptbl'], $_POST['pk'], $_POST['pid']);
			$page->set_message("Changes have been saved successfully");
		}
	}

	// Get user input for table 2 only. 
	if (isset($_POST['ftbl'])) {
		// Update the database for the specified table and primary key.
		$page->set_prepared_update("datatype_sql", $_POST, $_POST['ftbl'], $_POST['fk'], $_POST['fid']);
		$page->set_link($_POST['link']);
		$page->set_table2($_POST['ftbl'], $_POST['fk'], $_POST['fid']);
		$page->set_message("Changes have been saved successfully");
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
			
				<?php $page->display_breadcrumb("Menu Item", "Modify Record");  ?>
				
			</div>
			
			<div class="w3-container light-grey">
				<div class="window-padding">
				
					<?php $page->set_modify_form(); ?>
					<?php $page->display_snackbar(); ?>

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
	<script src="assets/js/summernote-lite.js" type="text/javascript"></script>
	<script src="assets/js/main.js" type="text/javascript"></script>

</body>
</html>

<?php
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>