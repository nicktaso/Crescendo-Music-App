<?php

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);            //apagereuei na isaxthoun stoixia html kodika px(<button> </) kai ta diagrafei k etsi tha apothikeyti stin vasi dedomenon
	return $inputText;
}

function sanitizeFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);      //apagoreyete to keno k to allazei mono tou k etsi tha apothikeyti stin vasi dedomenon
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText); 
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));        //metatropi tou mono tou protou gramatos se kefaleo k etsi tha apothikeyti stin vasi dedomenon
	return $inputText;                                   
}


if(isset($_POST['registerButton'])) {
	// Register button pressed

	$username = sanitizeFormUsername($_POST['username']);
	$firstName = sanitizeFormString($_POST['firstName']);
	$lastName = sanitizeFormString($_POST['lastName']);
	$email = sanitizeFormString($_POST['email']);
	$email2 = sanitizeFormString($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);

	$wasSuccessful = $account->register($username, $firstName, $lastName, 
										$email, $email2, $password, $password2);
	if($wasSuccessful == true) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}

}

?> 