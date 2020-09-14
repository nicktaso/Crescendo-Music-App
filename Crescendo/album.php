<?php include("includes/includedFiles.php"); 

if(isset($_GET['id'])) {         //catch id throught URL
	$albumId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$album = new Album($con,$albumId);
$artist = $album->getArtist();
?>

<div class="entityInfo">

	<div class="leftSection">
		<img src="<?php echo $album->getArtworkPath(); ?>">
	</div>
	
	<div class="rightSection">
		<h2><?php echo $album->getTitle(); ?></h2>
		<span>By <?php echo $artist->getName(); ?></span>
	</div>

</div>

<div class="tracklistContainer">
	<ul class="tracklist">

		<?php
		$songIdArray = $album->getSongId(); //return an array of all songs id to the album
		$i= 1;
		foreach ($songIdArray as $songId) {
			
			$albumSong = new Song($con, $songId);

			$albumSong->getTitle();
			$albumArtist = $albumSong->getArtist();

			echo "<li class='tracklistRow'> 
				<div class ='trackCount'>
					<img class ='play' src='assets\images\icons\play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
					<span class='trackNumber'>$i</span>
				</div>

				<div class = 'trackInfo'>
				<span class = 'trackName'>" . $albumSong->getTitle() . "</span> 
				<span class = 'artistName'>" . $albumArtist->getName() . "</span>
				</div>

				<div class = 'trackOptions'>
				<input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
				<img class= 'optionsButton' src='assets\images\icons\more.png' onclick='showOptionsMenu(this)'>
				 </div>

				 <div class = 'trackDuration'>
				 <span class = 'duration'>" . $albumSong->getDuration() . "</span>
				 </div>

			</li>";
			$i++;
		}

		?>

		<script>
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; //krataei ola ta id ton tragoudion tou sigkerkimenou album pou epilexame na akousoume (metatropi tou pinaka se json format)
			tempPlaylist = JSON.parse(tempSongIds); //metatropi tou pinaka json se object
		</script>
		
	</ul>
	
</div>


<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>




