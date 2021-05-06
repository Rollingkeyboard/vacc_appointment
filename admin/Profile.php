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
 * profile
 */
class Profile
{
    private $username;
    private $email;
    private $password;
    private $confirm_password;
    private $dob;
    private $ssn;
    private $gender;
    private $phone;
    private $address;
    private $max_distance;
    private $captcha;
    private $longitude;
    private $latitude;
    private $db_con;
    private $user_id;
    function __construct()
    {
//		if (!isset($_POST['type'])) {
//			echo "<script>alert('This page does not exist!');history.go(-1);</script>";
//			exit();
//		}
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
        include '../get_geocode.php';
        $this->db_con = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    }

    public function populate_data(){
        if ($_POST['type'] == 'display') {
            $sql_query = "SELECT patient_id,patient_email
                        ,patient_name,patient_id, patient_name, ssn, birth
                        , patient_address, patient_phone, patient_email, priority_level
                        , max_distance, patient_longitude, patient_latitude, u.user_password
                    FROM patient AS p JOIN user u on p.patient_id = u.user_id
                        WHERE patient_id ='" . $_SESSION['user'] . "';";

            $result = $this->db_con->query($sql_query);
            $row = $result->fetch_object();

            $data = array(
                "message"   => "Get user profile",
                "status" => 1,
                "sql_result" => $row
            );
            echo json_encode($data);
        }
    }
    public function check_email()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

                $this->email = $_POST['email'];
                $stmt= $this->db_con->prepare("SELECT user_id FROM user WHERE user_name = ?;");
                $stmt->bind_param('s', $this->email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows !== 0) {
                    // ajax callback result
                    echo '1';
                }
                else{
                    // ajax callback result
                    echo '0';
                }
                $stmt->close();
            }
            else{
                echo "profile check email No XHR";
            }
        }
        else{
            echo "profile check email NO AJAX";
        }
    }

    public function check_ssn()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
                $this->ssn = $_POST['ssn'];
                $stmt= $this->db_con->prepare("SELECT patient_id FROM patient WHERE ssn = ?;");
                $stmt->bind_param('s', $this->ssn);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows !== 0) {
                    // ajax callback exists duplicate result
                    echo '1';
                }
                else{
                    // ajax callback no duplicate result
                    echo '0';
                }
                $stmt->close();
            }
            else{
                echo "profile check ssn No XHR";
            }
        }
        else{
            echo "profile check ssn NO AJAX";
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
        $this->populate_data();
//        $this->username = 'test03';
//        $this->email = 'test02@example.org';
//        $this->password = '123123';
//        $this->confirm_password = '123123';
//        $this->dob = '2001-01-01';
//        $this->ssn = '1231231233';
//        $this->gender = 1;
//        $this->phone = '1112223344';
//        $this->address = '149 9th St, San Francisco, CA, 94103';
//        $this->max_distance = 25;
//        $_SESSION['user'] = 76;


        $this->username = $_POST['username'];
        $this->password = $_POST['password'];
        $this->confirm_password = $_POST['confirm'];
        $this->dob = strtotime($_POST['dob']);
        $this->gender = $_POST['gender'];
        $this->phone = $_POST['phone'];
        $this->address = $_POST['address'];
        $this->max_distance = $_POST['max_distance'];
        $this->longitude = 110;
        $this->latitude = 42.4;
        $this->captcha = $_POST['code'];


//		$this->check_captcha();
        $this->check_password();
        $this->check_name_format();
//      $this->check_email_format();

        /* Start transaction */
        $this->db_con->autocommit(false);
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $stmt = $this->db_con->prepare(
                "UPDATE user 
                        SET user_name=?, user_password=?
                        WHERE user_id = ?;");
            $stmt->bind_param('ssi', $this->email, $this->password, $_SESSION['user']);
            $stmt->execute();

            /* update patient table*/
            $stmt = $this->db_con->prepare("UPDATE patient SET patient_name=?
                    , birth=?, patient_address=?, patient_phone=?
                    , patient_email=?,  max_distance=?
                 , patient_longitude=?, patient_latitude=?
                    WHERE patient_id = ?;");
            /* bind parameter*/
            $stmt->bind_param('sssssiddi', $this->username
                , $this->dob, $this->address, $this->phone, $this->email
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
            echo "<script>alert('Your profile has been updated.');location.href = 'index.php';</script>";
            exit();
        }else{
            echo $this->db_con->error;
            exit();
        }
    }
}

$profile_user = new Profile();
//$this->populate_data();
//$_SESSION['user'] = 36;
if ($_POST['type'] == 'display'){
    $profile_user->populate_data();
}
//if ($_POST['type'] == 'all'){
//$profile_user->update_action();
//}


