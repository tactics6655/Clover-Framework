export enum VisualizerStyle {
    CLASSIC = 'classic',
    WAVEFORM = 'waveform',
    CIRCULAR = 'circular',
    CIRCLE = 'circle',
    DONUT = 'donut',
    SLIDE_WAVEFORM = 'slide_waveform',
    ROTATE_CIRCLE = 'rorate_circle',
    RAINBOW = 'rainbow',
} 

export class Visualizer {

    private selector;
    private context: CanvasRenderingContext2D;
    private canvas: HTMLCanvasElement;
    private backgroundFillColor = '';
    private spectrumFillColor = '';
    private space = 2;

    constructor(selector) {
        this.selector = selector;
    }

    public setSpectrumFillColor(color): void {
        this.spectrumFillColor = color;
    }

    public setBackgroundFillColor(color) {
        this.backgroundFillColor = color;
    }

    public circles(cx, cy, rad, dashLength) {
        var n = rad / dashLength;
        var alpha = Math.PI * 2 / n;
        var points = [];
        var i = -1;

        while (i < n) {
            var theta = alpha * i;
            var theta2 = alpha * (i + 1);

            points.push({
                x: (Math.cos(theta) * rad) + cx,
                y: (Math.sin(theta) * rad) + cy,
                ex: (Math.cos(theta2) * rad) + cx,
                ey: (Math.sin(theta2) * rad) + cy
            });

            i++;
        }

        return points;
    }

    private rotation  = 0;
    private dataIndex = 0;
    private previousDataArray = null;
    private currentRadius = 0;
    private targetRadius = 0;

    public draw(frequencies, binaryCount, lineWidth: number = 1, type: VisualizerStyle) {
        const canvasContext = this.context;
        const canvas = this.canvas;
        const samples = (canvas.height);

        if (this.previousDataArray == null) {
            this.previousDataArray = new Uint8Array(binaryCount);
        }

        let radius = 0;
        let current = 0;
        let x = 0;
        let y = 0;
        let width = 0;
        let height = 0;

        if (VisualizerStyle.SLIDE_WAVEFORM != type && type != VisualizerStyle.ROTATE_CIRCLE) {
            canvasContext.save();
            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.fillStyle = this.backgroundFillColor;
            canvasContext.fillRect(0, 0, canvas.width, canvas.height);
            canvasContext.lineWidth = lineWidth;
        }

        const ctx = canvasContext;
        const bufferLength = binaryCount;
        const dataArray = frequencies;


        switch (type) {
            case VisualizerStyle.RAINBOW:
                const sliceWidth = canvas.width / bufferLength;
                ctx.fillStyle = this.backgroundFillColor;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                let x = 0;
                for (let i = 0; i < bufferLength; i++) {
                    const barHeight = (dataArray[i] / 255) * canvas.height;
                    const hue = i / bufferLength * 360;
                    ctx.fillStyle = 'hsl(' + hue + ', 100%, 60%)';
                    ctx.fillRect(x, canvas.height - barHeight, sliceWidth, barHeight);
                    x += sliceWidth;
                }
                canvasContext.stroke();

                break;
            case VisualizerStyle.ROTATE_CIRCLE:
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const maxRadius = Math.min(centerX, centerY) - 20;
          
                const lowFrequencyRange = 50;
                let lowFrequencySum = 0;
                let lowFrequencyAverage = 0;
                for (let i = 0; i < lowFrequencyRange; i++) {
                  lowFrequencySum += dataArray[i];
                }
                lowFrequencyAverage = lowFrequencySum / lowFrequencyRange;
          
                this.targetRadius = maxRadius;

                for (let prev in this.previousDataArray){
                    if (lowFrequencyAverage > 100 && lowFrequencyAverage > (prev as any) * 1.2) {
                        this.targetRadius = this.targetRadius * 1.1; 
                    } else if (this.targetRadius > maxRadius){
                        this.targetRadius = this.targetRadius * 0.9; 
                    }
                }
          
                const self = this;

                function animateRadius() {
                    self.currentRadius += (self.targetRadius - self.currentRadius) * 0.5;
          
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
          
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, self.currentRadius, 0, 2 * Math.PI);
                    ctx.fillStyle = self.spectrumFillColor;
                    ctx.fill();
          
                    const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, self.currentRadius);
                    gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
                    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
          
                    ctx.fillStyle = gradient;
                    for (let i = 0; i < bufferLength; i++) {
                      const noise = dataArray[i] / 255;
                      const r = ((self.currentRadius * 0.1) * (noise * 10)) * noise;
                      ctx.beginPath();
                      ctx.arc(centerX, centerY, r, 0, 2 * Math.PI);
                      ctx.fill();
                      continue;
                    }
                }

                animateRadius();
          
                this.previousDataArray.set(dataArray);

                break;
            case VisualizerStyle.SLIDE_WAVEFORM:
                const imageData = canvasContext.getImageData(1, 0, canvas.width - 1, canvas.height);
                canvasContext.fillStyle = this.backgroundFillColor;
                canvasContext.fillRect(0, 0, canvas.width, canvas.height);
                canvasContext.putImageData(imageData, 0, 0);

                canvasContext.fillStyle = this.spectrumFillColor;
                const barHeight = (frequencies[this.dataIndex] / 255) * canvas.height;
                canvasContext.fillRect((canvas.width - 1), canvas.height - barHeight, 1, barHeight);

                this.dataIndex = (this.dataIndex + 1) % binaryCount;
                canvasContext.stroke();

                current++;
                break;
            case VisualizerStyle.WAVEFORM:
                for (let frequency of frequencies) {
                    x = (current) * lineWidth;
                    y = samples - (frequency / 128) * (samples / 2);
                    width = canvasContext.lineWidth;
                    height = ((canvas.height / 2) - y) * 2;

                    canvasContext.fillStyle = this.spectrumFillColor;
                    canvasContext.fillRect(x * this.space, y, width, height);

                    current++;
                }
                break;
            case VisualizerStyle.CLASSIC:
                for (let frequency of frequencies) {
                    x = (current) * this.space;
                    y = samples - (frequency / 128) * (samples / 2);

                    if (current == 0) {
                        canvasContext.strokeStyle = this.spectrumFillColor;
                        canvasContext.beginPath();
                        canvasContext.moveTo(x, y);
                    } else {
                        canvasContext.lineTo(x, y);
                    }

                    current++;
                }

                break;
            case VisualizerStyle.CIRCLE:
                const circles = this.circles(50, 50, 50, 1);

                for (let circle of circles) {
                    const centerX = canvas.width / 3;
                    const centerY = canvas.height / 3;

                    canvasContext.beginPath();
                    let frequency = frequencies[current];
                    frequency = 50 * (frequency / 256);

                    canvasContext.strokeStyle = this.spectrumFillColor;
                    canvasContext.lineWidth = frequency;
                    canvasContext.lineCap = "butt";
                    canvasContext.moveTo(centerX + circle.x, centerY + circle.y);
                    canvasContext.lineTo(centerX + circle.ex, centerY + circle.ey);
                    canvasContext.stroke();
                    canvasContext.closePath();
                    
                    current++;
                }

                break;
            case VisualizerStyle.CIRCULAR:
                const bars = 100;

                for (let frequency of frequencies) {
                    canvasContext.beginPath();

                    radius = canvas.width / 5;
                    const centerX = canvas.width / 2;
                    const centerY = canvas.height / 2;
                    const rads = (Math.PI * 2) / bars;
                
                    const x = centerX + Math.cos(rads * current) * (radius + lineWidth);
                    const y = centerY + Math.sin(rads * current) * (radius + lineWidth);
                    const endX = centerX + Math.cos(rads * current) * (radius + (frequency * 0.2));
                    const endY = centerY + Math.sin(rads * current) * (radius + (frequency * 0.2));

                    const gradient = canvasContext.createLinearGradient(0, 0, 170, 0);
                    gradient.addColorStop(0, "cyan");
                    gradient.addColorStop(1, "green");
                    canvasContext.fillStyle = gradient;

                    canvasContext.strokeStyle = this.spectrumFillColor;
                    canvasContext.lineWidth = lineWidth;
                    canvasContext.lineCap = "round";
                    canvasContext.shadowBlur = 1;
                    canvasContext.moveTo(x, y);
                    canvasContext.lineTo(endX, endY);
                    canvasContext.stroke();
            
                    current++;
                }
                break;
            case VisualizerStyle.DONUT:
                const halfWidth = canvas.width / 2;
                const halfHeight = canvas.height / 2;
                radius = canvas.width / 5;
                const maximumBarSize = Math.floor(360 * Math.PI) / 7;
                const framePerFrequency = Math.floor(frequencies.length / maximumBarSize);
                const minumiumHeight = 10;

                for (let i = 0; i < maximumBarSize; i++) {
                    const frequency = frequencies[i * framePerFrequency];
                    const alfa = (current * 2 * Math.PI) / maximumBarSize;
                    const degree = (180) * Math.PI / 180;

                    x = 0;
                    y = radius - (frequency / 12);
                    width = 2;
                    height = frequency / 6 + minumiumHeight;

                    canvasContext.save();
                    canvasContext.translate(halfWidth + 7, halfHeight + 7);
                    canvasContext.rotate(alfa - degree);
                    const gradient = canvasContext.createLinearGradient(0, 0, 170, 0);
                    gradient.addColorStop(0, "cyan");
                    gradient.addColorStop(1, "green");
                    canvasContext.fillStyle = gradient;
                    canvasContext.fillRect(x, y, width, height);
                    canvasContext.restore();

                    current++;
                }
                break;
        }

        switch (type) {
            case VisualizerStyle.WAVEFORM:
                canvasContext.beginPath();
            case VisualizerStyle.CLASSIC:
            case VisualizerStyle.CIRCLE:
                canvasContext.stroke();
        }
    }

    public setCanvas(width = -1, height = -1) {
        let canvasBackground: Element = null;
        const targetElement: NodeListOf<Element> = document.querySelectorAll(this.selector);

        if (targetElement.length > 0) {
            canvasBackground = targetElement[0];
        }

        canvasBackground.innerHTML = '';
        const newCanvas = document.createElement("canvas");

        this.canvas = canvasBackground.appendChild(newCanvas);
        this.canvas.width = width == -1 ? canvasBackground.clientWidth : width;
        this.canvas.height = height == -1 ? canvasBackground.clientHeight : height;
        this.canvas.style.verticalAlign = "middle";

        this.context = this.canvas.getContext("2d");
    }

}