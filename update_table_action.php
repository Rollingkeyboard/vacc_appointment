<?php
include_once("config.php");

if ($_POST['action'] == 'edit' && $_POST['id']) {
    $updateArray = array();
    if(isset($_POST['weekday'])) { $updateArray[] = "w_id='".$_POST['weekday']."'"; }
    if(isset($_POST['time_block'])) { $updateArray[] = "t_id='".$_POST['time_block']."'";}
    if (empty($updateArray)) { die("no object modified or other errors");}
    $updateField = implode(',', $updateArray);

	if($updateField && isset($_POST['user_type']))
	{
	    if($_POST['user_type'] === '1')
	    {
            $sqlQuery = "UPDATE patient_preferred_time SET $updateField WHERE ppt_id ='" . $_POST['id'] . "';";
            if(isset($_POST['status'])&& !empty($_POST['status'])){
                $update_appo_sql = "
                    UPDATE appointment AS a, (SELECT a.appointment_id
                         FROM appointment a JOIN provider_available_time pat on a.pat_id = pat.pat_id
                              JOIN patient_preferred_time ppt
                                   on a.patient_id = ppt.patient_id
                                   AND pat.w_id = ppt.w_id AND pat.t_id = ppt.t_id
                         WHERE ppt.ppt_id = '" . $_POST['id'] . "') AS tar
                    SET a.status = '" . $_POST['status'] . "'
                    WHERE a.appointment_id = tar.appointment_id;";
                mysqli_query($mysqli, $update_appo_sql) or die("database error:". mysqli_error($mysqli));
            }

	    }
	    elseif ($_POST['user_type'] === '2')
        {
            $sqlQuery = "UPDATE provider_available_time SET $updateField WHERE pat_id ='" . $_POST['id'] . "';";
            $update_appo_sql = "";
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
    if (isset($_POST['user_type']) ){
        if ($_POST['user_type'] === '1'){
            $sqlQuery = "DELETE FROM patient_preferred_time WHERE ppt_id ='" . $_POST['id'] . "';";
        }
        elseif ($_POST['user_type'] === '2'){
            $sqlQuery = "DELETE FROM provider_available_time WHERE pat_id ='" . $_POST['id'] . "';";
        }
        mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
        $data = array(
            "message"   => "Record Deleted",
            "status" => 1
        );
        echo json_encode($data);
    }
}

if ($_POST['action'] == 'add' && $_POST['u_id']) {
    if(isset($_POST['u_id']) && isset($_POST['user_type'])) {
        $addArray = array();
        $addArray[] = $_POST['u_id'];
        if(isset($_POST['weekday'])) { $addArray[] = $_POST['weekday']; }
        if(isset($_POST['time_block'])) { $addArray[] = $_POST['time_block'];}
        if (empty($addArray)) { die("no object modified or other errors");}
        $addField = implode(',', $addArray);
        if($_POST['user_type'] === '1') {
            $sqlQuery = "INSERT INTO patient_preferred_time (patient_id, w_id, t_id)
                        SELECT * FROM (SELECT $addField) AS tmp
                        WHERE NOT EXISTS (
                                SELECT patient_id, w_id, t_id FROM patient_preferred_time
                                WHERE patient_id = '" . $_POST['u_id'] . "' 
                                AND w_id = '" . $_POST['weekday'] . "' 
                                AND t_id = '" . $_POST['time_block'] . "'
                            ) LIMIT 1;";
//            $sqlQuery = "INSERT INTO patient_preferred_time (patient_id, w_id, t_id)
//                    VALUES ($addField);";
        }
        elseif ($_POST['user_type'] === '2')
        {
            $sqlQuery = "INSERT INTO provider_available_time (provider_id, w_id, t_id)
                    VALUES ($addField);";
        }
        $sql_result = mysqli_query($mysqli, $sqlQuery) or die("database error:". mysqli_error($mysqli));
        $data = array(
            "message"	=> "Record Added",
            "status" => 1
        );
        echo json_encode($data);
    }

}

