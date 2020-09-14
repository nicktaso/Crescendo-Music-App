<?php
	ob_start(); //otan fortonei mia selida stelnei ta dedomena ston server se paketa
	session_start();

	$timezone = date_default_timezone_set("Europe/London");

	$con = mysqli_connect("localhost", "root", "", "crescendo");

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}	
?>