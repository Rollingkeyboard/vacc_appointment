<?php 
/**
* register
*/
class Register
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
	function __construct()
	{
		if (!isset($_POST['type'])) {
			echo "<script>alert('This page does not exist!');history.go(-1);</script>";
			exit();
		}
        /**
         * debug dump data
         */
        //*******************************************************************************
//        $this->username = 'test02';
//		$this->email = 'test02@example.org';
//		$this->password = '112233';
//		$this->confirm_password = '112233';
//		$this->dob = '2001-01-01';
//        $this->ssn = '1231231222';
//        $this->gender = 1;
//        $this->phone = '1112223334';
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
		    	echo "register check email No XHR";
		    }
		}
		else{
			echo "register check email NO AJAX";
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
		    	echo "register check ssn No XHR";
		    }
		}
        else{
			echo "register check ssn NO AJAX";
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

	public function register_action()
	{
		$this->email = $_POST['email'];
        $this->ssn = $_POST['ssn'];
		$this->username = $_POST['username'];
		$this->password = $_POST['password'];
		$this->confirm_password = $_POST['confirm'];
        $this->dob = $_POST['dob'];
        $this->gender = $_POST['gender'];
        $this->phone = $_POST['phone'];
        $this->address = $_POST['address'];
        $this->max_distance = $_POST['max_distance'];
        $this->captcha = $_POST['code'];

//        $this->username = 'test03';
//		$this->email = 'test03@example.org';
//		$this->password = '112233';
//		$this->confirm_password = '112233';
//		$this->dob = '2001-01-01';
//        $this->ssn = '1231231224';
//        $this->gender = 1;
//        $this->phone = '1112223334';
//        $this->address = '149 9th St, San Francisco, CA, 94103';
//        $this->max_distance = 25;


        $this->longitude = -122;
        $this->latitude = 37;

		$this->check_captcha();
		$this->check_password();
        $this->check_name_format();
		$this->check_email_format();

        /* Start transaction */
        $this->db_con->autocommit(false);
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $stmt = $this->db_con->prepare(
                "INSERT INTO user(user_name, user_password, role_id) VALUES (?,?, 1);");
            $stmt->bind_param('ss', $this->email, $this->password);
            $stmt->execute();
            $last_insert_id = $this->db_con->insert_id;
            /* insert into patient table*/
            $stmt = $this->db_con->prepare("INSERT INTO patient(patient_id, patient_name, ssn
                    , birth, patient_address, patient_phone
                    , patient_email,  max_distance, patient_longitude, patient_latitude)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
            /* bind parameter*/
            $stmt->bind_param('issssssidd', $last_insert_id, $this->username
                , $this->ssn, $this->dob, $this->address, $this->phone, $this->email
                , $this->max_distance, $this->longitude, $this->latitude);
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
			echo "<script>alert('Register Successfully. Please log in.');location.href = '../main.php';</script>";
			exit();
		}else{
			echo $this->db_con->error;
			exit();
		}
	}
}

$register_user = new Register();
//$register_user->register_action();

switch ($_POST['type']) {
	case 'ssn':
        $register_user->check_ssn();
		break;
	case 'email':
        $register_user->check_email();
		break;
	case 'all':
        $register_user->register_action();
		break;
	default:
		echo "To do nothing";
		break;
}

