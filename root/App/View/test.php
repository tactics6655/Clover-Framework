<audio id="player" controls src="/App/File/cow.mp3"></audio>

<div style="width:200px;height: 200px; background-color:black" id="spectrum"></div>

<a id="play">Play</a>

<script>
const mediaPlayer = new MediaPlayer();
mediaPlayer.setContext(document.getElementById("player"));
mediaPlayer.setEvents();
mediaPlayer.setSpectrum("#spectrum", -1, -1, 1, 10, 'rgba(255, 0, 0, 0)', `rgb(28, 182, 130)`);
document.getElementById("play").addEventListener("click", function () {
    mediaPlayer.connectPanEffector();
    mediaPlayer.setParseFrequencyTimeout(1000 / 35);
    mediaPlayer.play();
});
</script>