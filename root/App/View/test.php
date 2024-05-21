<audio id="player" controls src="/App/File/innocence.mp3"></audio>

<div style="width:500px;height: 250px;" id="spectrum"></div>

<a id="play">Play</a>

<script>
const mediaPlayer = new MediaPlayer();
mediaPlayer.setContext(document.getElementById("player"));
mediaPlayer.setEvents();
mediaPlayer.setVisualizerStyle("donut");
mediaPlayer.setSpectrum("#spectrum");

const onClickEvent = function () {
    mediaPlayer.connectPanEffector();
    mediaPlayer.setParseFrequencyTimeout(1000 / 35);
    mediaPlayer.play();
};
document.getElementById("play").addEventListener("click", onClickEvent);
</script>