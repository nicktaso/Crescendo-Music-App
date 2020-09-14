var currentPlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var userLoggedIn;



$(document).click(function(click) {
	var target = $(click.target);

	if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
		hideOptionsMenu();
	}
});


$(document).on("change", "select.playlist", function() {
	var select = $(this);
	var playlistId = select.val();
	var songId = select.prev(".songId").val();

	$.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId})
	.done(function(error) {

		hideOptionsMenu();
		select.val("");
	});
});

function logout(){
	$.post("includes/handlers/ajax/logout.php", function(){
		location.reload();
	});
}


function openPage(url) {

	if(url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0); //otan allazw selida tha kanei scroll aytomata stin korifi tis neas selidas
	history.pushState(null, null, url); // na allazei to url opote einai sto index i sto album mono gia ta matia tou xristi
 
}


function createPlaylist() {
	console.log(userLoggedIn);
	var alert = prompt("Enter the name of your Playlist");

	if(alert != null) {
		$.post("includes/handlers/ajax/createPlaylist.php",{name: alert, username: userLoggedIn }).done(function(){
			openPage("yourList.php");

		})
	}
}

function deletePlaylist(playlistId) {
	var prompt = confirm("You want to delete this playlist?");

	if(prompt == true) {

		$.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
		.done(function() {

			openPage("yourMusic.php");
		});


	}
}

function hideOptionsMenu() {
	var menu = $(".optionsMenu");
	if(menu.css("display") != "none"){
		menu.css("display", "none");
	}
}

function showOptionsMenu(button) {
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);

	var scrollTop = $(window).scrollTop(); //apostasi apo tin korifi tou window mexri tin apostasi tou document
	var elementOffset = $(button).offset().top; //distance from top of document
	var top = elementOffset - scrollTop;
	var left = $(button).position().left;
	menu.css({"top": top + "px", "left": left + "px", "display": "inline"});
}



function formatTime(seconds) {
	var time =Math.round(seconds);
	var minutes= Math.floor(time/60); //rounds down minutes
	var seconds = time - minutes * 60;
	var zero;

	if( seconds < 10){
		zero = "0";
	}
	else{
		zero = "";
	}

	return minutes + ":" + zero + seconds;
}

function updateTimeProgressBar(audio){
	$(".progressTime.current").text(formatTime(audio.currentTime)); //increasing time
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime)); //dicreasing time

	var progress = audio.currentTime / audio.duration * 100;
	$(".playbackBar .progress").css("width",progress + "%"); //orizei to width mesw tis dieresis

}

function updateVolumeProgressBar(audio){
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");

}

function Audio() {

		this.currentlyPlaying;
		this.audio = document.createElement('audio');  //html audio element

		this.audio.addEventListener("ended", function(){ //an to repeat button einai patimeno xana xekinaei to tragoudi apo tin arxi. An oxi tote paizei to epomeno kommati kanonika
			nextSong();
		});

		this.audio.addEventListener("canplay", function(){  //format the time remaining of the song
			//'this' refers to the object that the event was called on and 'canplay ' mean that when is able to play a song is going to do "$(".progressTime.remaining").text(this.duration);"
			var duration = formatTime(this.duration);
			$(".progressTime.remaining").text(duration);

		});

		this.audio.addEventListener("timeupdate", function(){ //progress bar of the song
			if(this.duration){
				updateTimeProgressBar(this);
			}

		});

		this.audio.addEventListener("volumechange", function (){
			updateVolumeProgressBar(this);
		});

		this.setTrack = function(src) { 
			this.audio.src = src;    //source of the audio file
		}

		this.play = function() {
			this.audio.play();
		}

		this.pause = function() {
			this.audio.pause();
		}

		this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
		}
}