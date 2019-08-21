<?php
require_once('../core/core.php');

session_start();

try {

	$page = new navlink();
	$page->set_title("DBCE: Add Menu Item");
	$page->check_valid_user();

	// If table name is set then update wizard step 2 (select columns) using AjAX.
	if(isset($_GET['table'])) {
		$page->set_column_options($_GET['table']);
		exit;
	}

	// If the linkname is set then the user successfully completed the wizard and clicked finish.
	// Get user input and insert into database using prepared statement.
	if (isset($_POST['linkname'])) {
		$page->prepared_stmt($_POST);
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

				<?php $page->display_breadcrumb("Settings", "Add Menu Item"); ?>
			
			</div>
			
			<div class="w3-container light-grey">
				<div class="window-padding">
					<div class="w3-panel w3-card w3-display-container w3-white window">

						<?php $page->display_wizard(); ?>

					</div>
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