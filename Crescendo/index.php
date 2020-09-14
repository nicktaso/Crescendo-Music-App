<?php 
include("includes/includedFiles.php"); 
?>

<h1 class= "pageHeadingBig">Choose Your Music</h1>

<div class= "gridViewContainer">

	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND()");

		while($row = mysqli_fetch_array($albumQuery)) {


			echo "<div class='gridViewItem'>
					<span onclick='openPage(\"album.php?id=" . $row['id'] . "\")' >
						<img src='" . $row['artworkPath'] . "'>

						<div class='gridViewInfo'>"
							. $row['title'] .
						"</div>
					</span>

				</div>";
	}	
	
	



    
	
	 	
	?>

</div>