<?php
session_start();

?>
<?php
/**
 * appointment_time
 */
class AppointmentTime
{
    private $ppt_id;
    private $patient_id;
    private $w_id;
    private $t_id;
    private $pat_id;
    private $provider_id;
    private $appointment_id;
    private $status;
    private $user_type;
    private $address;
    private $max_distance;
    private $longitude;
    private $latitude;
    private $db_con;
    function __construct()
    {
        /**
         * debug dump data
         */
        //*******************************************************************************
//        $this->username = 'test02';
//		$this->email = 'test01@example.org';
//		$this->password = '123123';
//		$this->confirm_password = '123123';
//		$this->dob = '2001-01-01';
//        $this->ssn = '1231231233';
//        $this->gender = 1;
//        $this->phone = '1112223344';
//        $this->address = '149 9th St, San Francisco, CA, 94103';
//        $this->max_distance = 25;
//        $this->captcha = $_POST['code'];
//        $this->longitude = -122;
//        $this->latitude = 37;
        //*******************************************************************************
        include '../config.php';
        $this->db_con = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    }

    public function get_user_type(){
            $role_sql_query = "SELECT user_id, role_id
                        FROM user 
                        WHERE user_id ='" . $_SESSION['user'] . "';";
            $role_result = $this->db_con->query($role_sql_query);
            $role_row = $role_result->fetch_object();
            $this->user_type = $role_row->role_id;
            /*
             * either ppt or pat
             */
            $return_data = array();
            if ($this->user_type === '1')
            {
                $ppt_sql_query = "
                    SELECT ppt.ppt_id, ppt.patient_id, ppt.w_id, ppt.t_id,
                           IF(status IS NULL, 'NA', status) AS status
                    FROM appointment a JOIN provider_available_time pat on a.pat_id = pat.pat_id
                        RIGHT JOIN patient_preferred_time ppt
                            on a.patient_id = ppt.patient_id AND pat.w_id = ppt.w_id AND pat.t_id = ppt.t_id
                    WHERE ppt.patient_id = '" . $_SESSION['user']. "';";
                $result = $this->db_con->query($ppt_sql_query);
                while ( $row = $result->fetch_assoc())  {
                    $return_data[]=$row;
                }
            }
            elseif ($this->user_type === '2')
            {
                $_pat_sql_query = "
                    SELECT pat.pat_id, pat.provider_id, pat.w_id, pat.t_id,
                           IF(status IS NULL, 'NA', status) AS status
                    FROM provider_available_time pat
                        LEFT JOIN appointment a on pat.pat_id = a.pat_id
                    WHERE pat.provider_id ='" . $_SESSION['user'] . "';";
                $result = $this->db_con->query($_pat_sql_query);
                while ( $row = $result->fetch_assoc())  {
                    $return_data[]=$row;
                }
            }elseif ($this->user_type === '3')
            {
                /*
                 * some admin sql
                 */
                $admin_sql_query = "
                    WITH pat_status AS (
                        SELECT pat.pat_id, pat.provider_id, pat.w_id, pat.t_id,
                               IF(status IS NULL, 'NA', status) AS status
                        FROM provider_available_time pat
                                 LEFT JOIN appointment a on pat.pat_id = a.pat_id
                    )
                    SELECT * FROM pat_status
                    WHERE status NOT IN ('pending','vaccinated', 'accepted');
                    ";
                $result = $this->db_con->query($admin_sql_query);
                while ( $row = $result->fetch_assoc())  {
                    $return_data[]=$row;
                }
            }
            $data = array(
                "message"   => "Get user appointment_time",
                "status" => 1,
                "role_sql_result" => $role_row,
                "time_slot_sql_result" => $return_data
            );
            echo json_encode($data);
    }
    public function get_provider_appointment_time()
    {
        $sql_query = "SELECT pat_id, provider_id, w_id, t_id
                    FROM provider_available_time 
                    WHERE provider_id ='" . $_SESSION['user'] . "';";
        $result = $this->db_con->query($sql_query);
        $row = $result->fetch_object();
        $data = array(
            "message"   => "Get provider appointment_time",
            "status" => 1,
            "sql_result" => $row
        );
        echo json_encode($data);

    }

    public function get_patient_appointment_time()
    {
        $sql_query = "SELECT ppt_id, patient_id, w_id, t_id, 'NA' AS status 
                    FROM patient_preferred_time
                    WHERE patient_id = '" . $_SESSION['user']. "';";
        $result = $this->db_con->query($sql_query);
        $row = $result->fetch_object();
        $data = array(
            "message"   => "Get patient appointment_time",
            "status" => 1,
            "sql_result" => $row
        );
        echo json_encode($data);
    }

    public function get_session_id()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'add_new_time') {
            $data = array(
                "message" => "Get session_id",
                "status" => 1,
                "data" => $_SESSION['user']
            );
            echo json_encode($data);
        }
    }
}

$preferred_time_user = new AppointmentTime();

if (isset($_POST['action']) && $_POST['action'] == 'add_new_time') {
    $preferred_time_user->get_session_id();
}

if (isset($_POST['user_id']) && $_POST['user_id'] =='get_user_id'){
    if (isset($_POST['user_type']) && $_POST['user_type'] =='get_user_type'){
        $preferred_time_user->get_user_type();
    }
}

//if (isset($_POST['user_type']) && $_POST['user_type'] == 'patient') {
//
//    $data = array(
//        "message" => "Get session_id",
//        "status" => 1,
//        "data" => $_SESSION['user']
//    );
//    echo json_encode($data);
//}
//elseif (isset($_POST['user_type']) && $_POST['user_type'] == 'provider') {
//
//    $data = array(
//        "message" => "Get session_id",
//        "status" => 1,
//        "data" => $_SESSION['user']
//    );
//    echo json_encode($data);
//}
//elseif (isset($_POST['user_type']) && $_POST['user_type'] == 'admin') {
//    $data = array(
//        "message" => "Get session_id",
//        "status" => 1,
//        "data" => $_SESSION['user']
//    );
//    echo json_encode($data);
//}


