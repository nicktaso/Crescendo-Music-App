4
<?php
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

while($row = mysqli_fetch_array($songQuery)) {
	array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray);  //convert our array to json
?>

<script>

$(document).ready(function() { //w8 until the document is ready to run javascript
	var newPlaylist = <?php echo $jsonArray; ?>;
	audioElement = new Audio();
	setTrack(newPlaylist[0], newPlaylist, false);
	updateVolumeProgressBar(audioElement.audio); //gia na einai full h bara tou hxou prin xekinisei to kommati

	$("#musicPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) { //removes all highlight (dld den ta kanei mple) when you drag or you click the cursor on the page 
		e.preventDefault();
	});


	$(".playbackBar .progressBar").mousedown(function() {
		mouseDown = true;
	});

	$(".playbackBar .progressBar").mousemove(function(e) {
		if(mouseDown == true) {
			//Set time of song, depending on position of mouse
			timeFromOffset(e, this);
		}
	});

	$(".playbackBar .progressBar").mouseup(function(e) {
		timeFromOffset(e, this);
	});


	$(".volumeBar .progressBar").mousedown(function() {
		mouseDown = true;
	});

	$(".volumeBar .progressBar").mousemove(function(e) {
		if(mouseDown == true) {


			var percentage = e.offsetX / $(this).width();

			if(percentage >= 0 && percentage <= 1) {
				audioElement.audio.volume = percentage;
			}
		}
	});

	$(".volumeBar .progressBar").mouseup(function(e) {
		var percentage = e.offsetX / $(this).width();

			if(percentage>= 0 && percentage <=1){
				audioElement.audio.volume = percentage;
			}
	});


	$(document).mouseup(function() {
		mouseDown = false;
	});


});

function timeFromOffset(mouse, progressBar) {     //opou kai na einai to pontiki kanei drag to playbar
	var percentage = mouse.offsetX / $(progressBar).width() * 100;
	var seconds = audioElement.audio.duration * (percentage / 100);
	audioElement.setTime(seconds);
}

function previousSong() {
	if(audioElement.audio.currentTime>=5 || currentIndex == 0 ){ //prin ta 5 second allazei tragoudi meta ta 5 sec tis diarkias tou tragoudiou xanapigenei stin arxi
		audioElement.setTime(0);
	}
	else {
		currentIndex = currentIndex-1;
		setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
	}
}

function nextSong() { //pernei ton random pinaka pou dimiourgisame stin arxi kai vriskei to index number proxorontas sto epomeno tragoudi touy tyxaiou pinaka 

	if(repeat == true){
		audioElement.setTime(0);
		playSong();
		return;
	}

	if(currentIndex == currentPlaylist.length -1){
		currentIndex = 0;
	}
	else{
		currentIndex++;
	}

	var trackToPlay = currentPlaylist[currentIndex];
	setTrack(trackToPlay, currentPlaylist, true);
}

function setRepeat() {
	repeat = !repeat;
	var imageName = repeat ? "repeat-active.png" : "repeat.png";
	$(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
}

function setMute() {
	audioElement.audio.muted = !audioElement.audio.muted; //idio me to an if audioElement.audio.muted=true set it false else audioElement.audio.muted false set it true
	var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
	$(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
}


function setTrack(trackId, newPlaylist, play) {

	if(newPlaylist != currentPlaylist) {
		currentPlaylist = newPlaylist;
	}
	currentIndex = currentPlaylist.indexOf(trackId);
	pauseSong();


	$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

		var track = JSON.parse(data);
		$(".songName span").text(track.title);

		$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
			var artist = JSON.parse(data);
			$(".artistName span").text(artist.name);
		});

		$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
			var album = JSON.parse(data);
			$(".albumLink img").attr("src", album.artworkPath);
		});

		audioElement.setTrack(track.path);

		if(play == true) {
			playSong();
		}

	});
}

function playSong() {
	$(".controlButton.play").hide();
	$(".controlButton.pause").show();
	audioElement.play();
}

function pauseSong() {
	$(".controlButton.play").show();
	$(".controlButton.pause").hide();
	audioElement.pause();
}

</script>

<div id="musicPlayingBarContainer">

	<div id="musicPlayingBar">

		<div id="musicPlayingLeft">
			<div class="content">
				<span class="albumLink">
					<img src="" class="albumArtwork">
				</span>

				<div class="songInfo">
			
					<span class="songName">
						<span></span>
					</span>

					<span class="artistName">
						<span></span>
					</span>
			
				</div>						
			</div>

		</div>

	<div id="musicPlayingCenter">

		<div class="content playerControls" >

			<div class="buttons">

				<button class="controlButton previous" title="Previous button" onclick="previousSong()">
					<img src="assets/images/icons/previous.png"> 
				</button>

				<button class="controlButton play" title="Play button" onclick="playSong()">
					<img src="assets/images/icons/play.png"> 
				</button>

				<button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
					<img src="assets/images/icons/pause.png"> 
				</button>

				<button class="controlButton next" title="Next button" onclick="nextSong()">
					<img src="assets/images/icons/next.png"> 
				</button>

				<button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
					<img src="assets/images/icons/repeat.png"> 
				</button>

			</div>

			<div class="playbackBar">

				<span class="progressTime current">0.00</span>

				<div class="progressBar">
					<div class="progressBarBackground">
						<div class="progress"></div>
					</div>
				</div>
			
				<span class="progressTime remaining">0.00</span>

			</div>
		
		</div>

	</div>

	<div id="musicPlayingRight">
		<div class="volumeBar">
			<button class="controlButton volume" title="Volume Button" onclick="setMute()">
				<img src="assets/images/icons/volume.png">
			</button>

			<div class="progressBar">
				<div class="progressBarBackground">
					<div class="progress"></div>
				</div>
			</div>
		</div>

	</div>

</div>
</div>