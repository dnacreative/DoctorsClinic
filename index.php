<?php
	include 'functions.php';
	checkSomeoneIsLoggingIn();
	$dataentry = checkSomeoneSubmittingForm($_REQUEST);
	checkSomeoneDeletingSomething($_REQUEST);
	$loggedin = checkSomeoneAlreadyLoggedIn();
	$username = '';
	$account_type = '';
	$eid = '';
	if($loggedin) {
		$data = unserialize($_COOKIE['doctorsclinic-login']);
		$username = $data[0];
		$account_type = $data[1];
		$eid = $data[2];
	}

	if($account_type=='doctor') {
		checkPasswordChangeForDoctor($_REQUEST,$username);
		checkNameChangeForDoctor($_REQUEST,$username,$eid);
	}

	$mode = checkMode();
	$alert_message = false;

	if(checkAlert()) {
		$alert_message = giveAlert();
		removeAlertMessage();
	}

	checkIfUserWantToLogOut();

	if($dataentry && $loggedin) {
		enterData();
	}

	include 'html_includes/header.php';
?>

<?php




	if(!file_exists('database_config.php')) {
		//Database config file does not exists, we need to create one.;
		echo "Database configuration file not found<br>";
		echo "Created a file named <pre> database_config.php </pre> in the project's home folder";
		
	} else {
		include 'database_config.php';
		if(mysqli_connect($database_host, $database_user, $database_password, $database_name)) {
			if($loggedin) {
				showLogoutButton();
				if($account_type == 'admin') {
					if($mode=='normal') {
						showTables();
					} else if($mode=='view') {
						if(checkTable($_REQUEST['view'])) {
							display($_REQUEST['view']);
						}
					} else if($mode=='edit') {
						if(checkTable($_REQUEST['edit'])) {
							editTable($_REQUEST['edit']);
						}
					}
				} else if($account_type == 'doctor') {
					displayAccordingToDoctor($username,$eid);
				} else if($account_type == 'patient') {
					displayAccordingToPatient($eid);
				} else {

				}
			} else {
				showLogin();
			}
		} else {
			echo "Can't connect to database\n";
		}
	}

?>

<?php
	include 'html_include/footer.php';
?>
