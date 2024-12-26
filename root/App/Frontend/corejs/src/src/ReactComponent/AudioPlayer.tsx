import React, { useEffect, useRef, Component } from 'react';
import { MediaPlayer, VisualizerStyle } from "../Component/MediaPlayer";
import ReactDOM from "react-dom/client";

interface AudioPlayerAttributes {
    source: string;
}

export const AudioPlayer: React.FC<AudioPlayerAttributes> = (props) => {
    const audioRef = useRef<HTMLAudioElement>(null);
    const spectrumRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (audioRef.current && spectrumRef.current) {
            const mediaPlayer = new MediaPlayer();
            mediaPlayer.setContext(document.getElementById("audio"));
            mediaPlayer.setEvents();
            mediaPlayer.setVisualizerLineWidth(1);
            mediaPlayer.setVisualizerStyle(VisualizerStyle.WAVEFORM);
            mediaPlayer.setSpectrum("#spectrum");
        }
    });

    return (
        <div>
            <audio id="audio" controls src={props.source} ref={audioRef}></audio>
            <div style={{width:'200px', height: '200px'}} id="spectrum" ref={spectrumRef}></div>
        </div>
    );
}
