<?php 
/**
* login
*/
class Login
{
	public $email;
	public $password;
	public $remember_me;
	public $code;
    /**
     * @var mysqli
     */
    private $db_conn;

    function __construct()
	{
		if (!isset($_POST['login'])) {
			echo "<script>alert('The page does not exist!');history.go(-1);</script>";
			exit();
		}
		include '../config.php';
        //verify db
        $this->db_conn = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME);

		$this->email = $_POST['email'];
		$this->password = $_POST['password'];
		$this->code = $_POST['code'];
		$this->remember_me = $_POST['remember_me'];
		/**********************************************************************************/
//        $this->user_type = 1;
//		$this->email = 'mmurray@example.org';
//		$this->password = '3fda918eab70e79f5d31';
//		$this->code = $_POST['code'];
//		$this->remember_me = 1;
        /**********************************************************************************/
	}

	public function check_email_format(){
		//verify email
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if (!preg_match($pattern,$this->email)) {
			echo "<script>alert('Email format is incorrect. Please enter again.');history.go(-1);</script>";
			exit();
		}
	}

	public function check_password()
	{
		//verify password
		if (!trim($this->password) == '') {
			$strlen = strlen($this->password);
			if ($strlen < 6 || $strlen > 20) {
				echo "<script>alert('Password\'s length is illegal. Please enter again.');history.go(-1);</script>";
				exit();
			}
			/**
             *  If we need to encrypt password
             */
//			else{
//				$this->password = md5($this->password);
//			}
		}else{
			echo "<script>alert('Password cannot leave blank. Please enter again.');history.go(-1);</script>";
			exit();
		}
	}

	public function check_captcha_code()
	{
		//captcha code
		if ($this->code != $_SESSION['code']) {
			echo "<script>alert('Captcha is not correct.please try again!');history.go(-1);</script>";
			exit();
		}
	}

	public function check_email()
	{

        $stmt= $this->db_conn->prepare("
                            SELECT user_id, role_id FROM user WHERE user_name = ? and user_password = ?;");
        $stmt->bind_param('ss', $this->email, $this->password);
        $stmt->execute();
        $stmt->store_result();
		if ($stmt->num_rows === 0) {
			echo "<script>alert('Username/Password is incorrect. Please try again.');history.go(-1);</script>";
            $stmt->close();
			exit();
		}else{
            /* bind result variables */
            $stmt->bind_result($uid, $u_type);
            /* fetch value */
            $stmt->fetch();
            $stmt->close();
			$_SESSION['user'] = $uid;
            /**
             * Using user_type to redirect to patient or provider profile page
             */
            $_SESSION['user_type'] = $uid;
			if ($this->remember_me == 1) {
			  $_SESSION['rem'] = '1';
			}

			echo "<script>alert('Login Successfully!');location.href = '../index.php'</script>";
			exit();
		}
	}

	public function login_action()
	{
//		$this->check_captcha_code();
		$this->check_email_format();
		$this->check_password();
		$this->check_email();
	}
}

$login = new Login();
$login->login_action();

