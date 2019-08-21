<?php
require_once('../core/core.php');

session_start();

try {

	$page = new view();
	$page->set_title("DBCE: View Record");
	$page->check_valid_user();

	$notifications = false;

	// Set the side nav link for the current page.
	if(isset($_GET['link'])) {
		$page->set_link($_GET['link']);
	}

	// Update the database with the user selected order of ASC or DESC.
	if(isset($_GET['order'])) {
		$page->set_order($_GET['order']);
		$page->update_query("orderlinks_sql");
	}

	// Set the page number for pagination.
	if(isset($_GET['page'])) {
		$page->set_page($_GET['page']);
	}

	// Update the database and delete all selected rows from the result table.
	if(isset($_POST['delete']) && isset($_POST['link'])) {
		$page->set_link($_POST['link']);
		$page->delete_query($_POST['delete']);
		if (count($_POST['delete']) == 1) {
			$page->set_message("Record has been deleted successfully");
		}
		else {
			$page->set_message("Records have been deleted successfully");
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
			
				<?php $page->display_breadcrumb("Menu Item", "View Record");  ?>
				
			</div>
			
			<div class="w3-container light-grey">
				<div class="window-padding">
						
					<?php $page->set_table_data(); ?>
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
	<script src="assets/js/main.js" type="text/javascript"></script>

</body>
</html>

<?php
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>