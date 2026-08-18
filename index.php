<?php
require_once __DIR__ . '/includes/bootstrap.php';
$homeUserIsLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pinky Blog</title>
<link rel="stylesheet" href="css/cookies.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Quicksand:wght@500;600;700&display=swap');

    /* ---- colour palette (from the login page) ---- */
    :root {
        --cr-sky: #cfeaff;
        --cr-pink: #ffd6ef;
        --cr-white-glow: #ffffff;
        --cr-pink-glow: #fff0fa;

        --cr-blue: #7ec8ff;
        --cr-hotpink: #ff8fd0;
        --cr-hotpink-light: #ffc3e8;
        --cr-gold: #ffe08f;
        --cr-lavender: #c8b6ff;
        --cr-cotton-pink: #ffb6e6;

        --cr-gray-blue: #b9c4d0;
        --cr-gray-blue-pale: #dfe4ea;
        --cr-gray-light: #e3e7ec;
        --cr-gray-text: #8b8f9a;

        --cr-plum: #3a3450;
        --cr-periwinkle: #4a3f66;
        --cr-lavender-muted: #8a7fa0;
        --cr-violet-gray: #7a6f95;
        --cr-hint: #9a8fb0;

        --cr-card-fill: rgba(255, 255, 255, 0.6);
        --cr-input-border: #ecd9f0;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
        height: 100%;
    }

    body {
    font-family: 'Quicksand', sans-serif;
    font-size: 18px;
    color: var(--cr-plum);
    position: relative;
    display: flex;
    overflow-x: hidden;
    overflow-y: auto;
    flex-direction: column;
    min-height: 100vh; /* keeps everything on one screen, no page scroll */

        /* =====================================================
           CHANGE THE BACKGROUND HERE
           - To use a plain color: set background-color below.
           - To use your own image: replace the gradient lines
             with  background-image: url('your-file.jpg');
             and add  background-size: cover;  background-position: center;
           ===================================================== */
        background-color: var(--cr-pink);
        background-image:
            radial-gradient(circle at 12% 8%, rgba(207, 234, 255, 0.75) 0%, transparent 40%),
            radial-gradient(circle at 88% 12%, rgba(200, 182, 255, 0.55) 0%, transparent 38%),
            radial-gradient(circle at 50% 100%, rgba(255, 224, 143, 0.35) 0%, transparent 45%),
            radial-gradient(circle, rgba(255, 255, 255, 0.9) 1.5px, transparent 1.5px),
            linear-gradient(180deg, #ffe3f5 0%, var(--cr-pink) 35%, #ffd0ea 100%);
        background-size: auto, auto, auto, 34px 34px, auto;
        background-repeat: no-repeat, no-repeat, no-repeat, repeat, no-repeat;
        background-attachment: fixed, fixed, fixed, fixed, fixed;
        /* ===================================================== */
    }

    /* =========================================================
       BACKGROUND ANIMATION
       Small hearts and blank photo shapes float from the
       bottom of the screen to the top, behind all content.
       ========================================================= */
    .float-field {
        position: fixed;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }

    .floater {
        position: absolute;
        bottom: -10%;
        animation-name: float-up;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        opacity: 0.5;
    }

    .heart {
        width: 12px;
        height: 12px;
        background: var(--cr-hotpink);
        transform: rotate(-45deg);
    }
    .heart::before,
    .heart::after {
        content: "";
        position: absolute;
        width: 12px;
        height: 12px;
        background: var(--cr-hotpink);
        border-radius: 50%;
    }
    .heart::before { left: -6px; top: 0; }
    .heart::after { top: -6px; left: 0; }

    .photo-chip {
        width: 18px;
        height: 18px;
        background: var(--cr-white-glow);
        border: 2px solid var(--cr-cotton-pink);
        border-radius: 4px;
    }

    @keyframes float-up {
        from { transform: translateY(0) rotate(0deg); }
        to { transform: translateY(-115vh) rotate(20deg); }
    }

    /* =========================================================
       TOP RIBBON BAR — thin + light
       ========================================================= */
    .top-bar {
        position: relative;
        z-index: 3;
        flex: 0 0 auto;
        background: var(--cr-hotpink-light);
        height: 3.2vh;
        min-height: 34px;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        box-shadow: 0 2px 8px rgba(255, 143, 208, 0.2);
    }
    .top-bar-charm {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        background: var(--cr-sky);
        border: 4px solid var(--cr-white-glow);
        transform: translateY(28%);
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(58, 52, 80, 0.15);
    }
    .top-bar-charm img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .site-title {
        flex: 0 0 auto;
        text-align: center;
        margin: 0.6rem 0 0.35rem;
        position: relative;
        z-index: 2;
    }
    .site-title h1{
        font-family:'Baloo 2', cursive;
        font-size:clamp(2.8rem, 3.2vw, 3.6rem);
        font-weight:900;
        letter-spacing:1px;

        color:#5b476d;

        text-shadow:
            3px 3px 0 #ffffff,
            0 6px 12px rgba(255,143,208,.35);

        animation:titleFloat 3s ease-in-out infinite;
    }
    @keyframes titleFloat{
        0%,100%{
            transform:translateY(0);
        }

        50%{
            transform:translateY(-5px);
        }
    }

    .site-title p{
        margin-top:6px;
        font-size:0.85rem;
        font-weight:700;
        color:#7a6f95;
        letter-spacing:0.5px;
    }

    /* =========================================================
       MAIN ROOM PANEL — fills the remaining screen height
       ========================================================= */
    .room {
    position: relative;
    z-index: 2;
    height: auto;
    /* Fill the screen naturally instead of scaling a fixed-width panel. */
    width: min(94vw, 1600px);
    margin: 0 auto 1rem;
    flex: 0 0 auto;

    background: var(--cr-card-fill);
    backdrop-filter: blur(2px);
    border: 2px solid var(--cr-hotpink);
    border-radius: 24px;
    padding: 1.5rem 2rem;
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    box-shadow: 0 12px 30px rgba(58, 52, 80, 0.12);
    overflow: hidden;
    }

    /* left column: logo mark, featured spot, idol showcase */
    .room-left {
        flex: 0 1 360px;
        max-width: 380px;
        min-height: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    /* small logo mark, just the artwork, sits right at the top of the box */
    .logo-screen {
        flex: 0 0 auto;
        width: 100%;
        background: var(--cr-hotpink);
        border-radius: 16px;
        padding: 8px;
    }
    .logo-screen-inner {
        background: var(--cr-white-glow);
        border-radius: 8px;
        min-height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-screen-inner img {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--cr-cotton-pink);
    }

    /* featured picture frame */
    .featured-frame {
    width: 100%;
    height: 260px;
    border: 5px solid #f6c6d8;
    border-radius: 30px;
    overflow: hidden;
    box-sizing: border-box;
    margin-bottom: 15px;
    }

    .featured-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .featured-frame-inner {
        flex: 1 1 0;
        min-height: 0;
        background: var(--cr-sky);
        border: 2px solid var(--cr-white-glow);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: var(--cr-periwinkle);
        text-align: center;
        border-radius: 4px;
    }
    .featured-caption {
        flex: 0 0 auto;
        margin-top: 0.5rem;
        background: var(--cr-hotpink);
        color: var(--cr-white-glow);
        font-weight: 600;
        font-size: 0.75rem;
        text-align: center;
        padding: 0.4rem 1rem;
        border-radius: 20px;
    }

    /* idol showcase — reserved spot for an anime idol image */
    .idol-slot {
        height:210px;
        flex:0 0 210px;
        width: 100%;
        background: var(--cr-card-fill);
        border: 2px dashed var(--cr-cotton-pink);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 6px;
    }
    .idol-slot img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .idol-slot .empty-note {
        font-size: 0.75rem;
        color: var(--cr-violet-gray);
        text-align: center;
        padding: 0.5rem;
    }

    /* right column: navigation frames */
    .room-right {
        flex: 1 1 700px;
        min-height: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 1.25rem;
    }

    .nav-frame {
        display: flex;
        flex-direction: column;
        min-height: 0;
        text-decoration: none;
        color: inherit;
        background: var(--cr-white-glow);
        border: 5px solid var(--cr-cotton-pink);
        border-radius: 16px;
        padding: 10px;
        text-align: center;
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 6px 14px rgba(58, 52, 80, 0.08);
    }
    .nav-frame:hover {
        transform: translateY(-3px);
        border-color: var(--cr-hotpink);
        box-shadow: 0 10px 20px rgba(255, 143, 208, 0.28);
    }
    .nav-frame-image {
        flex: 1 1 0;
        min-height: 0;
        width: 100%;
        overflow: hidden;
        background: var(--cr-white-glow);
        border: 2px solid var(--cr-input-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cr-hint);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .nav-frame-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .nav-frame-label {
        flex: 0 0 auto;
        font-family: 'Baloo 2', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        margin-top: 0.4rem;
        color: var(--cr-periwinkle);
    }

    /* Compact Music Player */

    .music-widget{
    flex: 0 0 90px;
    }

    .music-widget {

        width:100%;
        height:90px;

        background: linear-gradient(
            135deg,
            #ffdcf0,
            #ff8fd4,
            #fccee8
        );

        border-radius:16px;

        padding:8px;

        display:flex;

        align-items:center;

        gap:8px;

        overflow:hidden;

        border:2px solid rgba(255,255,255,0.8);

        box-shadow:
        0 5px 15px rgba(255,143,208,0.25);

    }


    .music-cover {

        width:48px;
        height:48px;

        flex-shrink:0;

        border-radius:50%;

        object-fit:cover;

        border:2px solid rgb(220, 70, 195);

    }



    .music-info {

        flex:1;

        min-width:0;

    }



    .song-title {

        font-family:'Baloo 2', cursive;

        font-size:12px;

        color:white;

        white-space:nowrap;

    }



    .artist {

        font-size:9px;

        color:white;

    }



    /* smaller progress bar */

    .progress-area {

        width:100%;

        margin:2px 0;

    }



    #progressBar {

        width:100%;

        height:3px;

        cursor:pointer;

    }



    .time {

        font-size:8px;

        display:flex;

        justify-content:space-between;

        color:white;

    }



    .music-controls {

        display:flex;

        gap:4px;

    }



    .music-controls button {

        width:18px;

        height:18px;

        padding:0;

        font-size:8px;

    }
    
    /* rotating album image while music plays */

    .music-cover.playing {
        animation: rotateCover 8s linear infinite;
    }


    @keyframes rotateCover {

        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }

    }

    /* =========================================================
       DECORATIVE STARS
       ========================================================= */
    .star {
        position: absolute;
        width: 26px;
        height: 26px;
        background: var(--cr-lavender);
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%,
                            50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        z-index: 1;
        opacity: 0.85;
    }
    .star.one { top: -10px; left: 6%; background: var(--cr-hotpink); }
    .star.two { bottom: -8px; right: 5%; }

    /* =========================================================
       FLOOR
       ========================================================= */
    .floor {
        position: relative;
        z-index: 2;
        flex: 0 0 auto;
        height: 2.2vh;
        min-height: 22px;
        background: var(--cr-pink);
        background-image: radial-gradient(var(--cr-gold) 2.5px, transparent 2.5px);
        background-size: 22px 22px;
        border-top: 2px solid var(--cr-hotpink);
    }

    /*This week button*/
    .top-pick-label {
    background: linear-gradient(135deg,#f1b2e4 0%,#bfe8ff 45%,#f1b2e4 100%);
    color:#4a3f66;
    border-radius:22px;
    padding:8px 18px;
    text-align:center;
    font-weight:700;
    display:block;
    width:fit-content;
    margin:-8px auto 12px; /* Move the button upward */
    box-shadow:0 5px 12px rgba(126,200,255,.25);
    }

    @media (max-width: 900px) {
        html, body { height: auto; }
        body { overflow: auto; }
        .room {
            width: calc(100% - 2rem);
            flex-direction: column;
            height: auto;
        }
        .room-left { max-width: 100%; }
    }

    @media (max-width: 720px) {
        .room {
            width: calc(100% - 1rem);
            padding: 1rem;
            gap: 1rem;
            border-radius: 18px;
        }
        .room-right { grid-template-columns: 1fr; grid-template-rows: none; }
        .site-title h1 { font-size: 2rem; }
    }

    .floating-account-action {
        position: fixed; z-index: 1000; top: 20px; right: 22px;
        display: flex; align-items: center; gap: 9px;
        min-height: 52px; padding: 7px 17px 7px 8px;
        color: #fff; background: linear-gradient(135deg, #d56d9f, #b9578c);
        border: 3px solid #fff; border-radius: 999px;
        box-shadow: 0 7px 0 rgba(143,68,109,.32), 0 12px 28px rgba(91,71,109,.22);
        font-size: .82rem; font-weight: 800; text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .floating-account-action:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 9px 0 rgba(143,68,109,.3), 0 15px 30px rgba(91,71,109,.24); }
    .floating-account-action img { width: 36px; height: 36px; object-fit: cover; border: 2px solid #fff; border-radius: 50%; background: #fff5fa; }
    @media (max-width: 680px) {
        .floating-account-action { top: 12px; right: 12px; min-height: 46px; padding: 5px 13px 5px 6px; font-size: .74rem; }
        .floating-account-action img { width: 32px; height: 32px; }
    }
</style>
</head>
<body>

<?php if ($homeUserIsLoggedIn): ?>
    <a class="floating-account-action" href="logout.php" aria-label="Log out of Pinky Blog">
        <img src="assests/images/logout-button.jpg" alt="" onerror="this.hidden=true">
        <span>Logout</span>
    </a>
<?php else: ?>
    <a class="floating-account-action" href="login.php" aria-label="Log in or create a Pinky Blog account">
        <img src="assests/images/register-button.jpg" alt="" onerror="this.hidden=true">
        <span>Login / Create account</span>
    </a>
<?php endif; ?>

    <!-- floating hearts and photo chips, generated by script below -->
    <div class="float-field" id="floatField"></div>

    <div class="top-bar">
        <div class="top-bar-charm">
            <img src="assests/images/logo.jpg" alt="Logo" onerror="this.parentElement.style.display='none'">
        </div>
    </div>

    <div class="site-title">
        <h1>⋆｡°✩ Pinky Blog ⋆｡°✩</h1>
    </div>

    <main class="room">

        <!-- left column: brand mark, featured pick, idol showcase -->
        <section class="room-left">

            <!-- Cute Music Player -->

            <div class="music-widget">

            <img src="assests/images/music-cover.jpg" class="music-cover">

            <div class="music-info">

                <div class="song-title">
                    Little Forest
                </div>

                <div class="artist">
                    Cozy Ghibli Melody
                </div>


                <!-- Progress Bar -->
                <div class="progress-area">

                    <input type="range" 
                    id="progressBar" 
                    value="0">

                    <div class="time">

                        <span id="currentTime">0:00</span>

                        <span id="duration">0:00</span>

                    </div>

                </div>


                <div class="music-controls">

                    <button onclick="previousSong()">⏮</button>

                    <button class="play-btn" 
                    onclick="toggleMusic()" 
                    id="playButton">
                        ▶
                    </button>

                    <button onclick="nextSong()">⏭</button>

                </div>


            </div>

        </div>

            <audio id="bgMusic">
                <source src="assests/music/cozy-song.mp3" type="audio/mp3">
            </audio>

            <div class="featured-card">

                <div class="featured-frame">
                    <img src="assests/images/featured-thumb.jpg" alt="Top Pick">
                </div>

                <div class="top-pick-label">
                    Top Pick This Week
                </div>

            <div class="idol-slot">
                <img src="assests/images/gif30.gif" alt="idol">
            </div>

        </section>

        <!-- right column: main navigation -->
        <section class="room-right" style="position: relative;">

            <div class="star one"></div>
            <div class="star two"></div>

            <a class="nav-frame" href="blogs.php">
                <div class="nav-frame-image">
                    <img src="assests/images/blogs-thumb.jpg" alt="blogs">
                </div>
                <div class="nav-frame-label">blogs</div>
            </a>

            <a class="nav-frame" href="game.html">
                <div class="nav-frame-image">
                    <img src="assests/images/game-thumb.jpg" alt="Game" onerror="this.parentElement.textContent='add game thumbnail'">
                </div>
                <div class="nav-frame-label">Game</div>
            </a>

            <a class="nav-frame" href="profile.php">
                <div class="nav-frame-image">
                    <img src="assests/images/profile-thumb.jpg" alt="profile" onerror="this.parentElement.textContent='add profile thumbnail'">
                </div>
                <div class="nav-frame-label">profile</div>
            </a>

            <a class="nav-frame" href="aboutme.php">
                <div class="nav-frame-image">
                    <img src="assests/images/about-thumb.jpg" alt="about me" onerror="this.parentElement.textContent='add about thumbnail'">
                </div>
                <div class="nav-frame-label">about me</div>
            </a>

        </section>

    </main>

    <div class="floor"></div>

    <script>
        // build the floating background field
        // alternates between css hearts and blank photo chips
        const field = document.getElementById('floatField');
        const totalFloaters = 16;

        for (let i = 0; i < totalFloaters; i++) {
            const el = document.createElement('div');
            const isHeart = i % 2 === 0;
            el.className = 'floater ' + (isHeart ? 'heart' : 'photo-chip');

            el.style.left = Math.random() * 100 + 'vw';

            const duration = 14 + Math.random() * 10;
            el.style.animationDuration = duration + 's';
            el.style.animationDelay = (Math.random() * duration) + 's';

            const scale = 0.6 + Math.random() * 0.6;
            el.style.transform = 'scale(' + scale + ')';

            field.appendChild(el);
        }
    </script>

    <script>

        let music = document.getElementById("bgMusic");

        let playButton = document.getElementById("playButton");

        let cover = document.querySelector(".music-cover");

        let progressBar = document.getElementById("progressBar");

        let currentTimeText = document.getElementById("currentTime");

        let durationText = document.getElementById("duration");



        // play pause

        function toggleMusic(){

            if(music.paused){

                music.play();

                playButton.innerHTML="⏸";

                cover.classList.add("playing");

            }

            else{

                music.pause();

                playButton.innerHTML="▶";

                cover.classList.remove("playing");

            }

        }



        // update progress while playing

        music.addEventListener("timeupdate",function(){


            progressBar.value = music.currentTime;


            currentTimeText.innerHTML =
            formatTime(music.currentTime);


        });



        // get total duration

        music.addEventListener("loadedmetadata",function(){


            progressBar.max = music.duration;


            durationText.innerHTML =
            formatTime(music.duration);


        });



        // move song by dragging bar

        progressBar.addEventListener("input",function(){


            music.currentTime = progressBar.value;


        });




        // convert seconds to minutes

        function formatTime(seconds){

            let min = Math.floor(seconds / 60);

            let sec = Math.floor(seconds % 60);


            if(sec < 10){

                sec="0"+sec;

            }


            return min + ":" + sec;

        }



        function previousSong(){

            music.currentTime=0;

        }



        function nextSong(){

            music.currentTime=0;

            music.play();

        }

</script>

<?php require __DIR__ . '/includes/cookie_banner.php'; ?>
<script src="js/cookies.js"></script>
</body>
</html>
