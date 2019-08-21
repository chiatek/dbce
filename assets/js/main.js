
$(document).ready(function(){
	
	sidenav_toggle();
	disable_delete_btn();
	disable_submit();
	video_upload();
	image_upload();
	summernote();
	snackbar();
	
});

// ----------------------------------------------------------------
// Sidenav push with animation (off-canvas)
// ----------------------------------------------------------------

function sidenav_toggle() {
    $('.sidebar-toggle-btn').on('click', function () {
        $('.sidebar-wrapper').toggleClass('visible');
    });
}

// ----------------------------------------------------------------
// Disable delete button and confirm delete
// ----------------------------------------------------------------

function disable_delete_btn() {
	var checkboxes = $('.delete-chk');
	checkboxes.change(function() {
		$('#delete-btn').prop('disabled', checkboxes.filter(':checked').length < 1);
	});
	$('.delete-chk').change();
}

function confirm_delete() {
	var x = confirm("Are you sure you want to delete?");
	if (x)
		return true;
  	else
		return false;
}

function disable_submit() {
	$(function () {
		$('.save-btn').prop('disabled', true);

		//When a input is changed check all the inputs and if all are filled, enable the button
		$('input,textarea,select').change(function() {
			var isValid = true;
			$('input,textarea,select').filter('[required]:visible').each(function() {
			if ($(this).val() === '')
				isValid = false;
			});
			if(isValid) {
				$('.save-btn').prop('disabled', false);
			} 
			else {
				$('.save-btn').prop('disabled', true);
			};
		});
	});
}

// ----------------------------------------------------------------
// File upload
// ----------------------------------------------------------------

function video_upload() {
	$("#btn-video").click(function (e) {
		e.preventDefault();
		$("#upload-video").click();
	});
	
	$("#upload-video").change(function () {
		var file=$(this).val().replace(/C:\\fakepath\\/ig,'');
		
		$("#video-upload").val(file);
	});
}

function image_upload() {
	$("#btn-image").click(function (e) {
		e.preventDefault();
		$("#upload-image").click();
	});
	
	$("#upload-image").change(function () {
		var file=$(this).val().replace(/C:\\fakepath\\/ig,'');
		
		$("#image-upload").val(file);
	});
}

// ----------------------------------------------------------------
// Notification dropdown menu for top navigation
// ----------------------------------------------------------------

function dropdown() {
    var x = document.getElementById("dropdown");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}

// ----------------------------------------------------------------
//  Summernote WYSIWYG text editor
// ----------------------------------------------------------------

function summernote() {
	$('#summernote').summernote({
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']],
			['table', ['table']],
			['insert', ['link', 'picture', 'hr']],
			['view', ['fullscreen', 'codeview']]
		  ],  
		tabsize: 2,
		tooltip: false,
		height: 300
	})
	.on('summernote.change', function() {
		$('.save-btn').prop('disabled', false); 
	});
}

// ----------------------------------------------------------------
//  Snack Bar / Toast
// ----------------------------------------------------------------

function snackbar() {
	var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
}

// ----------------------------------------------------------------
// Settings Tabs
// ----------------------------------------------------------------

function open_setting(evt, setting_name) {
	var i, x, tablinks;
	x = document.getElementsByClassName("setting");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablink");
	for (i = 0; i < x.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" teal", "");
	}
	document.getElementById(setting_name).style.display = "block";
	evt.currentTarget.className += " teal";
}

// ----------------------------------------------------------------
// Wizard multiple step form
// ----------------------------------------------------------------

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

// Display the specified tab of the form
function showTab(n) {

	var x = document.getElementsByClassName("tab");
	x[n].style.display = "block";
	if (n == 0) {
	document.getElementById("prevBtn").style.display = "none";
	} 
	else {
	document.getElementById("prevBtn").style.display = "inline";
	}
	
	if (n == (x.length - 1)) {
	document.getElementById("nextBtn").innerHTML = "Finish";
	} 
	else {
	document.getElementById("nextBtn").innerHTML = "Next";
	}
	
	fixStepIndicator(n) //run a function that displays the correct step indicator
}

// Figure out which tab to display
function nextPrev(n) {
	
	var x = document.getElementsByClassName("tab");
	
	if (n == 1 && !validateForm()) return false; // Exit the function if any field in the current tab is invalid
	
	x[currentTab].style.display = "none"; // Hide the current tab
	currentTab = currentTab + n; // Increase or decrease the current tab by 1
	
	if (currentTab >= x.length) { // Check if you have reached the end of the form
		document.getElementById("regForm").submit(); // Sumbit the form
		return false;
	}
	
	showTab(currentTab);// Otherwise, display the correct tab:
}

// Validation of the form fields
function validateForm() {
	
	var x, y, i, valid = true;
	x = document.getElementsByClassName("tab");
	y = x[currentTab].getElementsByTagName("input");
	z = x[currentTab].getElementsByTagName("select");
	
	for (i = 0; i < y.length; i++) { // Check every input field in the current tab
		if (y[i].value == "") { // Check if a field is empty
		  y[i].className += " invalid"; // Add an "invalid" class to the field
		  valid = false; // Set the current valid status to false
		}
		if (y[i].type == "hidden") {
			valid = true;
		}
	}

	for (i = 0; i < z.length; i++) { // Check every input field in the current tab
		if (z[i].value == "null") { // Check if a field is empty
		  z[i].className += " invalid"; // Add an "invalid" class to the field
		  valid = false; // Set the current valid status to false
		}
		if (z[i].type == "hidden") {
			valid = true;
		}
	}

	if (valid) { // If the valid status is true, mark the step as finished and valid
		document.getElementsByClassName("indicator")[currentTab].className += " finish";
	}
	return valid; // Return the valid status
}

// Remove the "active" class of all steps
function fixStepIndicator(n) {
	
	var i, x, y;
	x = document.getElementsByClassName("indicator");
	y = document.getElementsByClassName("step");
	for (i = 0; i < x.length; i++) {
		x[i].className = x[i].className.replace(" active", "");
		y[i].className = y[i].className.replace(" active", "");
		y[i].className = y[i].className.replace(" hidden", "");
		y[i].className += " hidden";
	}
	
	x[n].className += " active"; // Add the "active" class on the current step
	y[n].className += " active";
	y[n].className = y[n].className.replace(" hidden", "");
}

// ----------------------------------------------------------------
// AJAX Database
// ----------------------------------------------------------------

function getdata(str) {
	if (str == "") {
		document.getElementById("ajax_output").innerHTML = "";
        return;
    } 
	else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
		else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ajax_output").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","navlink.php?table="+str,true);
        xmlhttp.send();
    }
}
