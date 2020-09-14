<?php 
	class Account {
		
		private $con;
		private $errorArray;

		public function __construct($con) {   // kataskevastis
			$this->con = $con;	
			$this->errorArray = array();     //dimiourgia pinaka
		}

		public function login($un, $pw) {

			$pw = md5($pw);

			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

			if(mysqli_num_rows($query) == 1) {
				return true;
			}
			else {
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}

		}
		
		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
	        $this->validateFirstName($fn);
	        $this->validateLastName($ln);
	        $this->validateEmails($em, $em2);
	        $this->validatePasswords($pw, $pw2);

	        if(empty($this->errorArray) == true) {

	        	return $this->insertUserDetails($un, $fn, $ln, $em, $pw);

	        }
	        	
	        else{
	        	return false;
	        }
	        
		}
		public function getError($error) { 			//checkarei se aytin tin clasi an ston pinaka errorArray i metavliti $error uparxei
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function insertUserDetails($un, $fn, $ln, $em, $pw) {

			$encryptedPw = md5($pw);
			$date = date("Y-m-d");
			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date')");
			return $result;

		}

		

		private function validateUsername($un) {                                                        			 //elenxos orthotitas username

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");     //check if username exists
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->errorArray, Constants::$usernameTaken);
				return;
			}
			 
		}


		private function validateFirstName($fn) {
			if(strlen($fn) > 25 || strlen($fn) < 2) {                                                          		  //elenxos orthotitas onomatos
				array_push($this->errorArray, Constants::$firstNameCharacters);
				return;
			}
			
		}

		private function validateLastName($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {                                                              	 //elenxos orthotitas epithetou
				array_push($this->errorArray, Constants::$lastNameCharacters);
				return;
			}
			
		}

		private function validateEmails($em, $em2) {                                                              	//elenxos orthotitas email
			if($em != $em2) {
			    array_push($this->errorArray, Constants::$emailDoNotMatch);
			    return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {                                                      		//checks if $em is not in the correct format
			    array_push($this->errorArray, Constants::$emailInvalid);
			    return;


			}    

			    
			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");     //check if the email already exists
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, Constants::$emailTaken);
				return;
			}

		}

		private function validatePasswords($pw, $pw2) {

			if($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNotMatch);
				return;
			}

			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {                                                              	 
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}
		}
	}

?>