<?php
require_once('../core/core.php');

session_start();

try {

	$page = new insert();
	$page->check_valid_user();
	$view = false;

	// Set the side nav link for the current page.
	if(isset($_GET['link'])) {
		$page->set_link($_GET['link']);
	}

	// User has clicked the insert button from view.php.
	if(isset($_GET['ptbl']) && isset($_GET['ftbl']) && isset($_GET['pk']) && isset($_GET['fk'])) {
		// Set table 1 and table 2 name and primary key.
		$page->set_table1($_GET['ptbl'], $_GET['pk'], 0);
		$page->set_table2($_GET['ftbl'], $_GET['fk'], 0);
	}
	else {
		if (isset($_GET['ptbl']) && isset($_GET['pk'])) {
			// Set table 1 name and primary key. Table 2 is not set.
			$page->set_table1($_GET['ptbl'], $_GET['pk'], 0);
		}
	}

	// Get user input and insert record into database for the given table name and primary key.
	if (isset($_POST['tbl']) && isset($_POST['key']) && isset($_POST['link'])) {
		$view = true;
		$page->set_link($_POST['link']);
		$page->set_prepared_insert("datatype_sql", $_POST, $_POST['tbl'], $_POST['key']);
		$page->set_message("Record has been added successfully");	
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
			
				<?php $page->display_breadcrumb("Menu Item", "New Record");  ?>
				
			</div>		

			<div class="w3-container light-grey">
				<div class="window-padding">

					<?php
						if($view) {
							$page->set_table_data();
							$page->display_snackbar();
						}
						else {
							$page->set_insert_form(); 
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