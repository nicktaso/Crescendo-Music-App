<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {  //an i selida fortonei san AJAX tha trexei to if
	include("includes/config.php");
	include("includes/classes/User.php");
	include("includes/classes/Artist.php"); 
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
	include("includes/classes/Playlist.php");



	if(isset($_GET['userLoggedIn'])) {
	$userLoggedIn = new User($con, $_GET['userLoggedIn']);
	}
}
else {                                         //an oxi tote tha fortosei to index arxeio
	include("includes/header.php");
	include("includes/footer.php");

	$url = $_SERVER['REQUEST_URI']; //anigei tin sinartisi tou openPage k  prosarmozei to perixomeno tou url sto mainContainer oste na fortonei iselida kanonika xwris kena kai na fortonei mono to mainContent to playBar kai to navBar tha menoun stathera xwris na allazoun pali
	echo "<script>openPage('$url')</script>";
	exit();

}
?>