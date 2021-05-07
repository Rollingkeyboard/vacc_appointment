<?php
session_start();
//if (!isset($_SESSION['user'])) {
//    if (isset($_COOKIE['user'])) {
//        $_SESSION['user'] = $_COOKIE['user'];
//    }else{
//        header('location:main.php');
//        exit();
//    }
//}
//if (isset($_SESSION['rem'])) {
//    setcookie('user',$_SESSION['user'],time()+3600);
//    unset($_SESSION['rem']);
//}

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
    private $captcha;
    private $longitude;
    private $latitude;
    private $db_con;
    function __construct()
    {
//        if (!isset($_POST['type'])) {
//            echo "<script>alert('This page does not exist!');history.go(-1);</script>";
//            exit();
//        }
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
                $ppt_sql_query = "SELECT ppt_id, patient_id, w_id, t_id, 'NA' AS status 
                    FROM patient_preferred_time
                    WHERE patient_id = '" . $_SESSION['user']. "';";
                $result = $this->db_con->query($ppt_sql_query);
                while ( $row = $result->fetch_assoc())  {
                    $return_data[]=$row;
                }
            }
            elseif ($this->user_type === '2')
            {
                $_pat_sql_query = "SELECT pat_id, provider_id, w_id, t_id
                    FROM provider_available_time 
                    WHERE provider_id ='" . $_SESSION['user'] . "';";
                $result = $this->db_con->query($_pat_sql_query);
                while ( $row = $result->fetch_assoc())  {
                    $return_data[]=$row;
                }
            }elseif ($this->user_type === '3')
            {
                /*
                 * some admin sql
                 */
                $_pat_sql_query = "SELECT pat_id, provider_id, w_id, t_id
                    FROM provider_available_time 
                    WHERE provider_id ='" . $_SESSION['user'] . "';";
                $result = $this->db_con->query($_pat_sql_query);
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

    public function check_captcha()
    {
        if ($this->captcha != $_SESSION['code']) {
            echo "<script>alert('Verification code is incorrect. Please enter again.');history.go(-1);</script>";
            exit();
        }
    }

    public function check_password(){
        if (trim($this->password) == '' || strlen($this->password) < 6 || strlen($this->password) > 20) {
            echo "<script>alert('Password format is incorrect. Please enter again.');history.go(-1);</script>";
            exit();
        }
        if ($this->password != $this->confirm_password) {
            echo "<script>alert('Confirmed password does not match. Please enter again.');history.go(-1);</script>";
            exit();
        }
        /**
         * If we need to encrypt password
         */
//		$this->password = md5($this->password);
    }
    public function check_email_format()
    {
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (!preg_match($pattern,$this->email)) {
            echo "<script>alert('Email format is incorrect. Please enter again.');history.go(-1);</script>";
            exit();
        }
    }

    public function check_name_format()
    {
        $length = strlen($this->username);
        if (trim($this->username) == '' || $length < 2 || $length > 20) {
            echo "<script>alert('Username format incorrect. Please enter again.');history.go(-1);</script>";
            exit();
        }
    }

    public function update_action()
    {
        $this->username = $_POST['username'];
        $this->password = $_POST['password'];
        $this->confirm_password = $_POST['confirm'];
        $this->dob = $_POST['dob'];
        $this->gender = $_POST['gender'];
        $this->phone = $_POST['phone'];
        $this->address = $_POST['address'];
        $this->max_distance = $_POST['max_distance'];
        $this->longitude = 110;
        $this->latitude = 42.4;
        $this->captcha = $_POST['code'];


        $this->check_captcha();
        $this->check_password();
        $this->check_name_format();
//      $this->check_email_format();

        /* Start transaction */
        $this->db_con->autocommit(false);
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            /*
             * update user table
             */
            $stmt = $this->db_con->prepare(
                "UPDATE user 
                        SET user_password=?
                        WHERE user_id = ?;");
            $stmt->bind_param('si', $this->password, $_SESSION['user']);
            $stmt->execute();
            /* update patient table*/
            $stmt = $this->db_con->prepare("UPDATE patient SET patient_name=?
                    , birth=?, patient_address=?, patient_phone=?,  max_distance=?
                    , patient_longitude=?, patient_latitude=?
                    WHERE patient_id = ?;");
            /* bind parameter*/
            $stmt->bind_param('ssssiddi', $this->username
                , $this->dob, $this->address, $this->phone
                , $this->max_distance, $this->longitude, $this->latitude, $_SESSION['user']);
            $stmt->execute();
            $this->db_con->autocommit(true);
            $stmt->store_result();
            /* If code reaches this point without errors then commit the data in the database */
        }catch (mysqli_sql_exception $exception) {
            $this->db_con->rollback();
            throw $exception;
        }
        if ($stmt->affected_rows !== 0) {
            $stmt->close();
            echo "<script>alert('Your preferred_time has been updated.');location.href = '../index.php';</script>";
            exit();
        }else{
            echo "<script>alert('Nothing updated');location.href = '../index.php';</script>";
            echo $this->db_con->error;
            exit();
        }
    }
}

$preferred_time_user = new AppointmentTime();
//$this->populate_data();
//$_SESSION['user'] = 36;
//if ($_POST['type'] == 'display'){
//    $preferred_time_user->populate_data();
//}
//if ($_POST['type'] == 'all'){
//    $preferred_time_user->update_action();
//}

if (isset($_POST['action']) && $_POST['action'] == 'add_new_time') {
    $preferred_time_user->get_session_id();
}

if (isset($_POST['user_id']) && $_POST['user_id'] =='get_user_id'){
    if (isset($_POST['user_type']) && $_POST['user_type'] =='get_user_type'){
        $preferred_time_user->get_user_type();
    }
}

if (isset($_POST['user_type']) && $_POST['user_type'] == 'patient') {

    $data = array(
        "message" => "Get session_id",
        "status" => 1,
        "data" => $_SESSION['user']
    );
    echo json_encode($data);
}
elseif (isset($_POST['user_type']) && $_POST['user_type'] == 'provider') {

    $data = array(
        "message" => "Get session_id",
        "status" => 1,
        "data" => $_SESSION['user']
    );
    echo json_encode($data);
}
elseif (isset($_POST['user_type']) && $_POST['user_type'] == 'admin') {
    $data = array(
        "message" => "Get session_id",
        "status" => 1,
        "data" => $_SESSION['user']
    );
    echo json_encode($data);
}


