<!--<audio id="player" controls src="/App/File/audio2.mp3"></audio>

<div style="width:200px;height: 200px;" id="spectrum"></div>

<a id="play">Play</a>

<script>
const mediaPlayer = new MediaPlayer();
mediaPlayer.setContext(document.getElementById("player"));
mediaPlayer.setEvents();
mediaPlayer.setVisualizerLineWidth(1);
mediaPlayer.setVisualizerStyle("circular");
mediaPlayer.setSpectrum("#spectrum");

const onClickEvent = function () {
    mediaPlayer.connectPanEffector();
    mediaPlayer.setParseFrequencyTimeout(1000 / 35);
    mediaPlayer.play();
};
document.getElementById("play").addEventListener("click", onClickEvent);
</script>-->

<?=$ip?>

<canvas id="canvas" style="width:500px;height:500px"></canvas>

<script>
const openGL = new OpenGLObject("canvas");
openGL.initialize();
openGL.setClearBufferBit();

const vertexShadow = `
attribute vec3 position;
uniform mat4 matrixShadowMatrix1;
uniform mat4 matrixShadowMatrix2;

void main() {
    gl_Position = matrixShadowMatrix1 * matrixShadowMatrix2 * vec4(position, 1.0);
}
`;

const fragmentShadow = `
precision mediump float;
uniform vec3 color;

void main() {
    gl_FragColor = vec4(color, 1.0);
}
`;

const compiledVertexShadow = openGL.getVertexShader(vertexShadow);
const compiledFragmentShadow = openGL.getFragmentShader(fragmentShadow);
const program = openGL.createProgram();
openGL.attachShader(program, compiledVertexShadow);
openGL.attachShader(program, compiledFragmentShadow);
openGL.linkProgram(program);
openGL.useProgram(program);

const vectorForRendering = new Float32Array(512);

const buffer = openGL.createBuffer();
openGL.bindArrayBuffer(buffer);
openGL.bufferArrayDataWithStaticDraw(vectorForRendering);

openGL.setClearColor(255.0, 0.0, 0.0, 1.0);
openGL.setClearBufferBit();

openGL.bufferArrayDataWithStaticDraw(vectorForRendering);
const position = openGL.getAttribLocation(program, "position");
openGL.setFloatVertexAttribPointer(position, 3, false, 0, 0);
openGL.enableVertexAttribArray(position);

const modelViewMatrix = new Float32Array(16);

const matrixShadowMatrix1 = openGL.getUniformLocation(program, "matrixShadowMatrix1");
const matrixShadowMatrix2 = openGL.getUniformLocation(program, "matrixShadowMatrix2");
openGL.uniformMatrix4fv(matrixShadowMatrix1, false, modelViewMatrix);
openGL.drawTriangleArrays(0, 5);

openGL.vertextAttib3f(1.0, 1.0, 1.0);
</script>