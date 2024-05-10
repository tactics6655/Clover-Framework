test

<a id="video" style="width: 500px; height:500px;background-color:#eee">test</a>

<audio id="audio" controls></audio>

<script>
    let audioRecorder = new AudioRecorder();
    audioRecorder.requestPermission().then(function () {
        audioRecorder.setRecorder();
        audioRecorder.addEvents();
        audioRecorder.start();
    });

    window.addEventListener("load", function () {
        document.getElementById("video").addEventListener("click", async function () {
            audioRecorder.stop();

            let blob = audioRecorder.getBlob();
            let buffer = await blob.arrayBuffer();

            let url = URLService.createBlob(buffer);
            document.getElementById("audio").src = url;
        });
    });

</script>