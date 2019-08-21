<?php
require_once('../core/core.php');

session_start();

try {

	$page = new dashboard();
	$page->set_title("DBCE: Dashboard");
	$page->check_valid_user(); 

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
			
				<?php $page->display_welcome();   ?>
				
			</div>

			<div class="w3-container light-grey">

				<div class="w3-window-padding">

					<?php $page->display_windows(); ?>
					<?php $page->display_tables(); ?>

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
