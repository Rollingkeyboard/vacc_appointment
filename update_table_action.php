<?php
include_once("config.php");

if ($_POST['action'] == 'edit' && $_POST['id']) {
    $updateArray = array();
    if(isset($_POST['weekday'])) { $updateArray[] = "w_id='".$_POST['weekday']."'"; }
    if(isset($_POST['time_block'])) { $updateArray[] = "t_id='".$_POST['time_block']."'";}
//    if(isset($_POST['status'])) { $updateArray[] = "status='".$_POST['status']."'"; }
    if (empty($updateArray)) { die("no object modified or other errors");}
    $updateField = implode(',', $updateArray);

	if($updateField && isset($_POST['user_type']))
	{
	    if($_POST['user_type'] === '1')
	    {
            $sqlQuery = "UPDATE patient_preferred_time SET $updateField WHERE ppt_id ='" . $_POST['id'] . "';";
	    }
	    elseif ($_POST['user_type'] === '2')
        {
            $sqlQuery = "UPDATE provider_available_time SET $updateField WHERE pat_id ='" . $_POST['id'] . "';";
        }
        elseif ($_POST['user_type'] === '3')
        {
            /*
             *  Admin
             */
        }
        mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
        $data = array(
            "message"	=> "Record Updated",
            "status" => 1
        );
        echo json_encode($data);
	}
}
if ($_POST['action'] == 'delete' && $_POST['id']) {
    if (isset($_POST['user_type']) && $_POST['user_type'] === '1'){
        $sqlQuery = "DELETE FROM patient_preferred_time WHERE ppt_id ='" . $_POST['id'] . "';";
    }
    elseif (isset($_POST['user_type']) && $_POST['user_type'] === '2'){
        $sqlQuery = "DELETE FROM provider_available_time WHERE pat_id ='" . $_POST['id'] . "';";
    }
	mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
	$data = array(
		"message"   => "Record Deleted",
		"status" => 1
	);
	echo json_encode($data);	
}

if ($_POST['action'] == 'add' && $_POST['u_id']) {
    if(isset($_POST['u_id']) && isset($_POST['user_type'])) {
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
        if($_POST['user_type'] === '1') {
            $sqlQuery = "INSERT INTO patient_preferred_time (patient_id, w_id, t_id)
                    VALUES ($addField);";
        }
        elseif ($_POST['user_type'] === '2')
        {
            $sqlQuery = "INSERT INTO provider_available_time (provider_id, w_id, t_id)
                    VALUES ($addField);";
        }
        mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
        $data = array(
            "message"	=> "Record Added",
            "status" => 1
        );
        echo json_encode($data);
    }

}

