<?php
/** */
if (isset($_POST['AddCollection'])) {
	
	unset($_POST['AddCollection']);
	unset($_POST['ward']);

	$res= saveData($_POST, 'imprestdisbursement');
	if ($res) {
		$success = "Disbursed successful";
	}else{
		$err = "Error, Failed to save";
	}
	
}

/** Adding imprest by system admin */
if (isset($_POST['AdimSaveImprest'])) {
	unset($_POST['AdimSaveImprest']);
	
	$res= saveData($_POST, 'imprest');
	if ($res) {
		$success = "Saved";
	}else{
		$err = "Error, Failed to save";
	}
	
}

/** Update imprest by system admin */
if (isset($_POST['AdimEditImprest'])) {
	unset($_POST['AdimEditImprest']);
	$_POST['imprest_amount'] = str_replace(",", "", $_POST['imprest_amount']);
	
	
	$res= updateDetails($_POST, 'imprest', 'imprest_id', $_POST['imprest_id']);
	if ($res) {
		$success = "Imprest updated successfuly";
	}else{
		$err = "Error, Failed to update";
	}
	
}

/** Deactivate imprest by system admin */
if (isset($_POST['deactivate_imprest'])) {
	unset($_POST['deactivate_imprest']);
	$datas = [
		'imprest_status' => 0
	];
	
	$res= updateDetails($datas, 'imprest', 'imprest_id', $_POST['imprest_id']);
	if ($res) {
		$success = "Imprest deactivated successfuly";
	}else{
		$err = "Error, Failed to deactivate";
	}
	
}


?>
