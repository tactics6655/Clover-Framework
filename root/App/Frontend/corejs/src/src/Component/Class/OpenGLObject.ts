import { OpenGLAttributeObject } from './OpenGLAttributeObject';

export class OpenGLObject {

	private canvas;
	private gl: WebGL2RenderingContext;
	private attribute: OpenGLAttributeObject;

	constructor(canvasID: string) {
		try {
			this.canvas = document.getElementById(canvasID);
		} catch (e) { }

		this.gl = null;
		this.attribute = null;
	}

	getGLObject() {
		return this.gl;
	}

	setMatrixUniforms(shaderProgram, scriptID, flatten) {
		const uniformLocation = this.gl.getUniformLocation(shaderProgram, scriptID);
		const data = new Float32Array(flatten);
		this.gl.uniformMatrix4fv(uniformLocation, false, data);
	}

	/**
	 * Check that status of framebuffer
	 * 
	 * @param target 
	 * @returns 
	 */
	checkFrameBufferStatus(target: number): number {
		return this.gl.checkFramebufferStatus(target);
	}

	bindDrawFrameBuffer(framebuffer: WebGLFramebuffer) {
		this.bindFrameBuffer(this.gl.DRAW_FRAMEBUFFER, framebuffer);
	}

	bindReadFrameBuffer(framebuffer: WebGLFramebuffer) {
		this.bindFrameBuffer(this.gl.READ_FRAMEBUFFER, framebuffer);
	}

	bindCopyWriteFrameBuffer(framebuffer: WebGLFramebuffer) {
		this.bindFrameBuffer(this.gl.COPY_WRITE_BUFFER, framebuffer);
	}

	bindGeneralFrameBuffer(framebuffer: WebGLFramebuffer) {
		this.bindFrameBuffer(this.gl.FRAMEBUFFER, framebuffer);
	}

	bindFrameBuffer(target: number, framebuffer: WebGLFramebuffer) {
		this.gl.bindFramebuffer(target, framebuffer);
	}

	uniformMatrix4fv(location: WebGLUniformLocation, transpose: boolean, data: Float32List, srcOffset?: number, srcLength?: number) {
		if (!srcOffset && !srcLength) {
			this.gl.uniformMatrix4fv(location, transpose, data);

			return;
		}

		this.gl.uniformMatrix4fv(location, transpose, data, srcOffset, srcLength);
	}

	vertextAttib3f(index: number, x: number, y: number, z: number): void {
		this.gl.vertexAttrib3f(index, x, y, z);
	}

	createVertexArray(): WebGLVertexArrayObject {
		return this.gl.createVertexArray();
	}

	uniform1fv(location: WebGLUniformLocation, data: Float32List, srcOffset?: number, srcLength?: number) {
		if (!srcOffset && !srcLength) {
			this.gl.uniform1fv(location, data);

			return;
		}

		this.gl.uniform1fv(location, data, srcOffset, srcLength);
	}

	uniformMatrix3fv(location: WebGLUniformLocation, transpose: boolean, data: Float32List, srcOffset?: number, srcLength?: number) {
		this.gl.uniformMatrix3fv(location, transpose, data, srcOffset, srcLength);
	}

	isFragmentShader(type: string) {
		return type === "x-shader/x-fragment";
	}

	isVertexShader(type: string) {
		return type === "x-shader/x-vertex";
	}

	isAttachedShader(type: string) {
		return type === "x-shader/x-attached";
	}

	setFloatVertexAttribPointer(index: number, size: number, normalized: boolean, stride: number, offset: number) {
		this.gl.vertexAttribPointer(index, size, this.gl.FLOAT, normalized, stride, offset);
	}

	setUnsignedByteVertexAttribPointer(index: number, size: number, normalized: boolean, stride: number, offset: number) {
		this.gl.vertexAttribPointer(index, size, this.gl.UNSIGNED_BYTE, normalized, stride, offset);
	}

	setVertexAttribPointer(index: number, size: number, type: number, normalized: boolean, stride: number, offset: number) {
		this.gl.vertexAttribPointer(index, size, type, normalized, stride, offset);
	}

	getFragmentShader(source: string) {
		return this.getShader(source, "x-shader/x-fragment");
	}

	getAttachedShader(source: string) {
		return this.getShader(source, "x-shader/x-attached");
	}

	getVertexShader(source: string) {
		return this.getShader(source, "x-shader/x-vertex");
	}

	get cubeMapTextureRightTarget() {
		return this.gl.TEXTURE_CUBE_MAP_POSITIVE_X;
	}

	get cubeMapTextureLeftTarget() {
		return this.gl.TEXTURE_CUBE_MAP_NEGATIVE_X;
	}

	get cubeMapTextureTopTarget() {
		return this.gl.TEXTURE_CUBE_MAP_POSITIVE_Y;
	}

	get cubeMapTextureBottomTarget() {
		return this.gl.TEXTURE_CUBE_MAP_NEGATIVE_Y;
	}

	get cubeMapTextureBackTarget() {
		return this.gl.TEXTURE_CUBE_MAP_POSITIVE_Z;
	}

	get cubeMapTextureFrontTarget() {
		return this.gl.TEXTURE_CUBE_MAP_NEGATIVE_Z;
	}

	texParameteri(target: number, pname: number, param: number) {
		this.gl.texParameteri(target, pname, param);
	}

	createTexture() {
		return this.gl.createTexture();
	}

	bindCubeMapTexture(texture: WebGLTexture) {
		this.bindTexture(this.gl.TEXTURE_CUBE_MAP, texture);
	}

	bindTexture(target: number, texture: WebGLTexture) {
		this.gl.bindTexture(target, texture);
	}

	texImage2D(target: number, level: number, internalformat: number, width: number, height: number, border: number, format: number, type: number, pixels: ArrayBufferView) {
		this.gl.texImage2D(target, level, internalformat, width, height, border, format, type, pixels);
	}

	getShader(source: string, type: string) {
		let shader: WebGLShader = null;

		if (this.isFragmentShader(type)) {
			shader = this.gl.createShader(this.gl.FRAGMENT_SHADER);
		} else if (this.isAttachedShader(type)) {
			shader = this.gl.createShader(this.gl.ATTACHED_SHADERS);
		} else if (this.isVertexShader(type)) {
			shader = this.gl.createShader(this.gl.VERTEX_SHADER);
		} else {
			return shader; // Unknown shader type
		}

		this.gl.shaderSource(shader, source);

		// Compile the shader program
		this.gl.compileShader(shader);

		// See if it compiled successfully
		if (!this.gl.getShaderParameter(shader, this.attribute.COMPILE_STATUS)) {
			alert("An error occurred compiling the shaders: " + this.gl.getShaderInfoLog(shader));
			return null;
		}

		return shader;
	}

	/**
	 * Get shadow for use on the program by x-shadow script
	 * 
	 * @param shaderId 
	 * @returns 
	 */
	getShaderFromElementById(shaderId: string) {
		let theSource = "";
		let shaderScript = document.getElementById(shaderId);
		let currentChild = shaderScript.firstChild;

		while (currentChild) {
			if (currentChild.nodeType == currentChild.TEXT_NODE) {
				theSource += currentChild.textContent;
			}

			currentChild = currentChild.nextSibling;
		}

		return this.getShader(theSource, shaderScript.type);
	}

	bufferArrayDataWithStaticDraw(size: number) {
		this.bufferData(this.gl.ARRAY_BUFFER, size, this.gl.STATIC_DRAW);
	}

	bufferData(target: number, size: number, usage: number) {
		this.gl.bufferData(target, size, usage);
	}

	drawTriangleElements(count: number, type: number, offset: number) {
		this.drawElements(this.gl.TRIANGLES, count, type, offset);
	}

	drawLineLoopElements(count: number, type: number, offset: number) {
		this.drawElements(this.gl.LINE_LOOP, count, type, offset);
	}

	drawTriangleFanElements(count: number, type: number, offset: number) {
		this.drawElements(this.gl.TRIANGLE_FAN, count, type, offset);
	}

	drawTriangleStripElements(count: number, type: number, offset: number) {
		this.drawElements(this.gl.TRIANGLE_STRIP, count, type, offset);
	}

	drawLineStripElements(count: number, type: number, offset: number) {
		this.drawElements(this.gl.LINE_STRIP, count, type, offset);
	}

	drawElements(mode: number, count: number, type: number, offset: number) {
		this.gl.drawElements(mode, count, type, offset);
	}

	bindVertexArray(array: WebGLVertexArrayObject) {
		this.gl.bindVertexArray(array);
	}

	bindBuffer(target: number, buffer: WebGLBuffer) {
		this.gl.bindBuffer(target, buffer);
	}

	bindArrayBuffer(buffer: WebGLBuffer) {
		this.gl.bindBuffer(this.gl.ARRAY_BUFFER, buffer);
	}

	createBuffer(): WebGLBuffer {
		return this.gl.createBuffer();
	}

	enableVertexAttribArray(AttribLocation): void {
		this.gl.enableVertexAttribArray(AttribLocation);
	}

	getAttribLocation(program: WebGLProgram, name: string): number {
		return this.gl.getAttribLocation(program, name);
	}

	getUniformLocation(program: WebGLProgram, name: string): WebGLUniformLocation {
		return this.gl.getUniformLocation(program, name);
	}

	useProgram(program: WebGLProgram) {
		this.gl.useProgram(program);
	}

	hasInitializedShaderProgramLink(program: WebGLProgram) {
		if (this.gl.getProgramParameter(program, this.attribute.LINK_STATUS)) {
			return true;
		}

		return false;
	}

	/**
	 * Link a shadow program
	 * 
	 * @param program 
	 * @returns 
	 */
	linkProgram(program: WebGLProgram) {
		this.gl.linkProgram(program);

		if (!this.hasInitializedShaderProgramLink(program)) {
			return new DOMException("Unable to initialize the shader program.");
		}
	}

	attachShader(program: WebGLProgram, shader: WebGLShader) {
		this.gl.attachShader(program, shader);
	}

	createProgram(): WebGLProgram {
		return this.gl.createProgram();
	}

	initWebGL() {
		try {
			this.gl = this.canvas.getContext("webgl") || this.canvas.getContext("experimental-webgl") || this.canvas.getContext("webkit-3d") || this.canvas.getContext("moz-webgl");

			this.attribute = new OpenGLAttributeObject(this.gl);
		} catch (e) { }
	}

	setScissor(x: number, y: number, width: number, height: number) {
		this.gl.scissor(x, y, width, height);
	}

	/**
	 * Set a scissor box
	 */
	setColorMask(red: boolean, green: boolean, blue: boolean, alpha: boolean) {
		this.gl.colorMask(red, green, blue, alpha);
	}

	/**
	 * Set the viewport (Graphics actual rendering area)
	 *
	 * @param x      : GLint (32bit long)
	 * @param y      : GLint (32bit long)
	 * @param width  : Glsizei (long)
	 * @param height : Glsizei (long)
	 */

	setViewPortSize(x: number, y: number, width: number, height: number) {
		this.gl.viewport(x, y, width, height);
	}

	drawTriangleArrays(first: number, count: number) {
		this.drawArrays(this.gl.TRIANGLES, first, count);
	}

	drawArrays(mode: number, first: number, count: number) {
		this.gl.drawArrays(mode, first, count);
	}

	/**
	 * Actual width of the current drawing buffer
	 * Read-Only
	 */
	get getDrawingBufferWidth(): number {
		return this.gl.drawingBufferWidth;
	}

	/**
	 * Actual height of the current drawing buffer
	 * Read-Only
	 */
	get getDrawingBufferHeight(): number {
		return this.gl.drawingBufferHeight;
	}

	/**
	 * Clear color of openGL
	 *
	 * alpha (max 1.0) : set alpha
	 */
	setClearColor(red: number, green: number, blue: number, alpha: number) {
		this.gl.clearColor(red, green, blue, alpha);
	}

	setColorBufferBitClear() {
		this.setClear(this.gl.COLOR_BUFFER_BIT);
	}

	setClearBufferBit() {
		this.gl.clear(this.gl.COLOR_BUFFER_BIT | this.gl.DEPTH_BUFFER_BIT);
	}

	setClear(mask: number) {
		this.gl.clear(mask);
	}

	setFrontFaceToCW() {
		this.gl.frontFace(this.gl.CW);
	}

	setFrontFaceToCCW() {
		this.gl.frontFace(this.gl.CCW);
	}

	cullFrontAndBackFaces() {
		this.gl.cullFace(this.gl.FRONT_AND_BACK);
	}

	cullFrontFace() {
		this.gl.cullFace(this.gl.FRONT);
	}

	cullBackFace() {
		this.gl.cullFace(this.gl.BACK);
	}

	setFaceCullingEnable() {
		this.setEnable(this.gl.CULL_FACE);
	}

	setScissorTestEnable() {
		this.setEnable(this.gl.SCISSOR_TEST);
	}

	setDepthMask(flag: boolean) {
		this.gl.depthMask(flag);
	}

	setAlwaysDepthFunction() {
		this.setDepthTestFunction(this.gl.ALWAYS);
	}

	setNeverDepthTestFunction() {
		this.setDepthTestFunction(this.gl.NEVER);
	}

	setLessDepthTestFunction() {
		this.setDepthTestFunction(this.gl.LESS);
	}

	setEqualDepthTestFunction() {
		this.setDepthTestFunction(this.gl.EQUAL);
	}

	setLessEqualDepthTestFunction() {
		this.setDepthTestFunction(this.gl.LEQUAL);
	}

	setGreaterDepthTestFunction() {
		this.setDepthTestFunction(this.gl.GREATER);
	}

	setNotEqualDepthTestFunction() {
		this.setDepthTestFunction(this.gl.NOTEQUAL);
	}

	setGreaterOrEqualDepthTestFunction() {
		this.setDepthTestFunction(this.gl.GEQUAL);
	}

	setDepthTestFunction(func: number) {
		this.gl.depthFunc(func);
	}

	enableDepthMask() {
		this.setDepthMask(true);
	}

	disableDepthMask() {
		this.setDepthMask(false);
	}

	setDepthTestEnable() {
		this.setEnable(this.gl.DEPTH_TEST);
	}

	setEnable(cap: number) {
		this.gl.enable(cap);
	}

	setDepthFunc(func: number) {
		this.gl.depthFunc(func);
	}

	isInitialized() {
		if (this.gl == null) {
			return false;
		}

		return true;
	}

	initialize() {
		if (!this.isInitialized()) {
			this.initWebGL();
		}

		return true;
	}

}
