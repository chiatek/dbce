<?php
require_once('../core/core.php');

session_start();

try {

	$page = new search();
	$page->set_title("DBCE: Search Results");
	$page->check_valid_user();

	// Set the side nav link for the current page.
	if(isset($_GET['link'])) {
		$page->set_link($_GET['link']);
	}

	// Set the page number for pagination.
	if(isset($_GET['page'])) {
		$page->set_page($_GET['page']);
	}

	// Set the search value inputed by the user.
	if(isset($_GET['search'])) {
		$page->set_query($_GET['search']);
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
			
				<?php $page->display_breadcrumb("Menu Item", "Search Results");  ?>
				
			</div>
			
			<div class="w3-container light-grey">
				<div class="window-padding">

					<?php $page->set_search(); ?>

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