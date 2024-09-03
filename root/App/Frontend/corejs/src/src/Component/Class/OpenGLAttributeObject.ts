export class OpenGLAttributeObject {

	private gl: WebGL2RenderingContext;

	constructor(openGL: WebGL2RenderingContext) {
		this.gl = openGL;
	}

	get COMPILE_STATUS() {
		return this.gl.COMPILE_STATUS;
	}

	get COLOR_BUFFER_BIT() {
		return this.gl.COLOR_BUFFER_BIT;
	}

	get DEPTH_BUFFER_BIT() {
		return this.gl.DEPTH_BUFFER_BIT;
	}

	get LEQUAL() {
		return this.gl.LEQUAL;
	}

	get DEPTH_TEST() {
		return this.gl.DEPTH_TEST;
	}

	get LINK_STATUS() {
		return this.gl.LINK_STATUS;
	}

	get STATIC_DRAW() {
		return this.gl.STATIC_DRAW;
	}

	get ARRAY_BUFFER() {
		return this.gl.ARRAY_BUFFER;
	}

}