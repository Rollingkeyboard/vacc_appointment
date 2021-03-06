<?php 
/**
* register
*/
class Register
{
    private $user_type;
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
    private $provider_type;
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

	    /*
	     * user table
	     */
		$this->email = htmlentities($_POST['email']);
        $this->password = htmlentities($_POST['password']);
        $this->confirm_password = htmlentities($_POST['confirm']);
        $this->user_type = intval($_POST['user_type']);

        /*
         * patient table
         */
        if ($this->user_type === 1){
            $this->ssn = htmlentities($_POST['ssn']);
            $this->dob = $_POST['dob'];
            $this->gender = $_POST['gender'];
        }
        /*
         * provider table
         */
        elseif ($this->user_type === 2){
            $this->provider_type = $_POST['provider_type'];
        }
        $this->username = htmlentities($_POST['username']);
        $this->phone = htmlentities($_POST['phone']);
        $this->address = htmlentities($_POST['address']);
        $this->max_distance = htmlentities($_POST['max_distance']);

        $coordinate_arr = geocode($this->address, true);
        $this->longitude = $coordinate_arr[0];
        $this->latitude = $coordinate_arr[1];
        /*
         * web server feature
         */
        $this->captcha = htmlentities($_POST['code']);

		$this->check_captcha();
		$this->check_password();
        $this->check_name_format();
		$this->check_email_format();

        /* Start transaction */
        $this->db_con->autocommit(false);
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $stmt = $this->db_con->prepare(
                "INSERT INTO user(user_name, user_password, role_id) VALUES (?, ?, ?);");
            $stmt->bind_param('ssi', $this->email, $this->password, $this->user_type);
            $stmt->execute();
            $last_insert_id = $this->db_con->insert_id;


            /* insert into patient table*/

            if ($this->user_type === 1) {
                $stmt = $this->db_con->prepare("INSERT INTO patient(
                        patient_id, patient_name, ssn, birth, gender
                        , patient_address, patient_phone, patient_email
                        ,  max_distance, patient_longitude, patient_latitude)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                /* bind parameter*/
                $stmt->bind_param('isssssssidd', $last_insert_id, $this->username
                    , $this->ssn, $this->dob, $this->gender, $this->address, $this->phone, $this->email
                    , $this->max_distance, $this->longitude, $this->latitude);
            }
            elseif ($this->user_type === 2){
                $stmt = $this->db_con->prepare("INSERT INTO provider(
                        provider_id, provider_name, provider_phone, provider_address
                        , provider_email, provider_type, provider_longitude, provider_latitude)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
                /* bind parameter*/
                $stmt->bind_param('isssssdd', $last_insert_id, $this->username
                    , $this->phone, $this->address, $this->email, $this->provider_type
                    , $this->longitude, $this->latitude);
            }
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

