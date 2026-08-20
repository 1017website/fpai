<div class="fpai-audio-player" aria-label="Pemutar lagu FPAI">
    <audio class="fpai-audio" preload="metadata"></audio>
    <button class="fpai-audio-toggle" type="button" aria-label="Putar lagu">▶</button>
    <div class="fpai-audio-info">
        <span class="fpai-audio-status">Klik untuk memutar</span>
        <strong class="fpai-audio-title">Satukan Langkah</strong>
        <div class="fpai-audio-progress-wrap">
            <input class="fpai-audio-progress" type="range" min="0" max="100" value="0" step="0.1" aria-label="Posisi lagu">
            <small class="fpai-audio-time">00:00</small>
        </div>
    </div>
    <button class="fpai-audio-next" type="button" aria-label="Lagu berikutnya" title="Lagu berikutnya">››</button>
    <button class="fpai-audio-mute" type="button" aria-label="Matikan suara" title="Matikan suara">♪</button>
</div>
<script>
(()=>{
    const player=document.querySelector('.fpai-audio-player');if(!player)return;
    const audio=player.querySelector('.fpai-audio');
    const toggle=player.querySelector('.fpai-audio-toggle');
    const nextButton=player.querySelector('.fpai-audio-next');
    const muteButton=player.querySelector('.fpai-audio-mute');
    const title=player.querySelector('.fpai-audio-title');
    const status=player.querySelector('.fpai-audio-status');
    const progress=player.querySelector('.fpai-audio-progress');
    const time=player.querySelector('.fpai-audio-time');
    const tracks=[
        {title:'Satukan Langkah',label:'Mars FPAI',src:@json(asset('audio/fpai-mars.mp3'))},
        {title:'Rumah Pengayoman',label:'Hymne FPAI',src:@json(asset('audio/fpai-hymne.mp3'))},
    ];
    let current=0;
    const formatTime=seconds=>{if(!Number.isFinite(seconds))return'00:00';const minutes=Math.floor(seconds/60);const rest=Math.floor(seconds%60);return`${String(minutes).padStart(2,'0')}:${String(rest).padStart(2,'0')}`};
    const renderTrack=()=>{const track=tracks[current];audio.src=track.src;title.textContent=track.title;status.textContent=track.label;progress.value=0;time.textContent='00:00'};
    const renderPlayback=()=>{const playing=!audio.paused;toggle.textContent=playing?'❚❚':'▶';toggle.setAttribute('aria-label',playing?'Jeda lagu':'Putar lagu');player.classList.toggle('is-playing',playing);if(playing)status.textContent=tracks[current].label};
    const play=async()=>{try{await audio.play()}catch(error){status.textContent='Klik untuk memutar';renderPlayback()}};
    const next=()=>{current=(current+1)%tracks.length;renderTrack();play()};
    renderTrack();audio.volume=.55;
    toggle.addEventListener('click',()=>audio.paused?play():audio.pause());
    nextButton.addEventListener('click',next);
    muteButton.addEventListener('click',()=>{audio.muted=!audio.muted;muteButton.textContent=audio.muted?'×':'♪';muteButton.setAttribute('aria-label',audio.muted?'Nyalakan suara':'Matikan suara')});
    audio.addEventListener('play',renderPlayback);audio.addEventListener('pause',renderPlayback);audio.addEventListener('ended',next);
    audio.addEventListener('timeupdate',()=>{progress.value=audio.duration?(audio.currentTime/audio.duration)*100:0;time.textContent=`${formatTime(audio.currentTime)} / ${formatTime(audio.duration)}`});
    progress.addEventListener('input',()=>{if(audio.duration)audio.currentTime=(Number(progress.value)/100)*audio.duration});
    window.startFpaiAudio=play;
    window.addEventListener('load',()=>setTimeout(play,1200));
    document.addEventListener('click',event=>{if(!event.target.closest('.fpai-audio-player'))play()},{once:true,capture:true});
    document.addEventListener('keydown',event=>{if(!event.target.closest?.('.fpai-audio-player'))play()},{once:true,capture:true});
})();
</script>
