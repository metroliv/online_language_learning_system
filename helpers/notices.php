<?php
/** Save and send Notices */
if (isset($_POST['sendNotice'])) {
	//dd($_POST);

	unset($_POST['sendNotice']);

	$res = saveData($_POST, 'notices');
	if ($res) {
		$success = "Send successful";
	} else {
		$err = "Error, Failed to send";
	}
}

/** SAVE RECEIPTS */
if (isset($_POST['receipts'])) {
	//dd($_POST);	

	unset($_POST['receipts']);

	$receipt = selectOne('notice_receipts', $_POST);
	if (empty($receipt)) {
		saveData($_POST, 'notice_receipts');	

		/* Load Specific Dashboard Based On User Access Level */
		if ($_SESSION['user_access_level'] == 'System Administrator') {
			/* Admin */
			header("Location: admin_notice");
		} elseif ($_SESSION['user_access_level'] == 'Municipal Receiver') {
			/* Municipal Receiver */
			header("Location: executive_notice");
		} elseif (
			$_SESSION['user_access_level'] == 'ECM' || 
			$_SESSION['user_access_level'] == 'CECM-Finance' || 
			$_SESSION['user_access_level'] == 'Deputy Director' || 
			$_SESSION['user_access_level'] == 'Chief Officer' || 
			$_SESSION['user_access_level'] == 'Governor' || 
			$_SESSION['user_access_level'] == 'Deputy Governor' || 
			$_SESSION['user_access_level'] == 'Director' || 
			$_SESSION['user_access_level'] == 'Director - IMV' || 
			$_SESSION['user_access_level'] == 'ECMS' || 
			$_SESSION['user_access_level'] == 'Executive'
		) {
			header("Location: executive_notice");
		}

		exit(); 	 	
	}	
}
