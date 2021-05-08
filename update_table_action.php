<?php
include_once("config.php");

if ($_POST['action'] == 'edit' && $_POST['id']) {
    $updateArray = array();
    if(isset($_POST['weekday'])) { $updateArray[] = "w_id='".$_POST['weekday']."'"; }
    if(isset($_POST['time_block'])) { $updateArray[] = "t_id='".$_POST['time_block']."'";}
//    if(isset($_POST['status'])) { $updateArray[] = "status='".$_POST['status']."'"; }
    if (empty($updateArray)) { die("no object modified or other errors");}
    $updateField = implode(',', $updateArray);

	if($updateField && $_POST['id']) {
		$sqlQuery = "UPDATE patient_preferred_time SET $updateField WHERE ppt_id ='" . $_POST['id'] . "';";
		mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
		$data = array(
			"message"	=> "Record Updated",	
			"status" => 1
		);
		echo json_encode($data);		
	}
}
if ($_POST['action'] == 'delete' && $_POST['id']) {
	$sqlQuery = "DELETE FROM patient_preferred_time WHERE ppt_id ='" . $_POST['id'] . "';";
	mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
	$data = array(
		"message"   => "Record Deleted",
		"status" => 1
	);
	echo json_encode($data);	
}

if ($_POST['action'] == 'add' && $_POST['u_id']) {
    if(isset($_POST['u_id'])) {
//        $getTimeSlot = "SELECT t_id FROM time_slot
//                    WHERE weekday = '" . $_SESSION['weekday'] . "'AND time_block='" . $_SESSION['time_block'] . "'";
//        $timeSlotResult = mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
//        $time_id = $timeSlotResult->fetch_object()[0]->t_id;
        $addArray = array();
        $addArray[] = $_POST['u_id'];
        if(isset($_POST['weekday'])) { $addArray[] = $_POST['weekday']; }
        if(isset($_POST['time_block'])) { $addArray[] = $_POST['time_block'];}
        if (empty($addArray)) { die("no object modified or other errors");}
        $addField = implode(',', $addArray);

        $sqlQuery = "INSERT INTO patient_preferred_time (patient_id, w_id, t_id)
                    VALUES ($addField);";
        mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
        $data = array(
            "message"	=> "Record Added",
            "status" => 1
        );
        echo json_encode($data);
    }

}

