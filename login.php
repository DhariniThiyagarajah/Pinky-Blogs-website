<?php
require_once __DIR__ . '/includes/db.php';

if(isset($_POST['login'])){

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, username, password, role FROM user WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            header("Location: index.php");
            exit();

        }else{
            echo "Wrong password";
        }

    }else{
        echo "Email not found";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome back, doll ✧</title>
    <link rel="stylesheet" href="css/cookies.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap');

        :root {
            --bg-top: #fffafd;
            --bg-bottom: #ffedf7;
            --font-display: 'Baloo 2', sans-serif;
            --font-body: 'Quicksand', sans-serif;

            --theme-color: #d56d9f;
            --theme-glow-rgb: 213, 109, 159;
            --shade-color: #f6c6dc;
            --bulb-color: #fff0fa;
            --light-opacity: 0;
            --btn-text-color: #ffffff;
            --btn-bg: #b9578c;
            --ribbon-color: #ed8fbd;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: radial-gradient(circle at 20% 15%, rgba(255,255,255,.88) 0%, transparent 43%),
                        radial-gradient(circle at 85% 80%, #fff0fa 0%, transparent 40%),
                        linear-gradient(160deg, var(--bg-top) 0%, var(--bg-bottom) 100%);
            color: #3a3450;
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* ---- floating sparkles ---- */
        .sparkle-field {
            position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
        }
        .sparkle {
            position: absolute; font-size: 1.1rem; opacity: 0.55;
            color: #fff;
            text-shadow: 0 0 6px rgba(255,255,255,0.9);
            animation: drift linear infinite, twinkle 2.4s ease-in-out infinite;
        }
        @keyframes drift {
            from { transform: translateY(0); }
            to { transform: translateY(-110vh); }
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.25; }
            50% { opacity: 0.9; }
        }

        .container {
            display: flex; width: 100%; max-width: 1100px;
            height: 600px; padding: 2rem; gap: 2rem;
            position: relative; z-index: 1;
        }

        .lamp-section {
            flex: 1; display: flex; flex-direction: column; justify-content: center;
            align-items: center; position: relative;
        }

        .wordmark {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.9rem;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            background: linear-gradient(90deg, #b9578c, #ff8fd0, #d56d9f, #b9578c);
            background-size: 300% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: rainbow-flow 6s linear infinite;
            text-align: center;
            -webkit-text-stroke: 0.5px rgba(255,255,255,0.6);
        }
        @keyframes rainbow-flow {
            to { background-position: 300% center; }
        }
        .tagline {
            font-size: 0.8rem; color: #8a7fa0; margin-bottom: 1.5rem;
            letter-spacing: 1px; text-transform: uppercase;
        }

        .lamp-svg {
            width: 100%; max-width: 340px; height: auto;
            overflow: visible; filter: drop-shadow(0 25px 25px rgba(160, 140, 200, 0.35));
        }

        .shade-main { fill: var(--shade-color); transition: fill 0.6s ease; }
        .shade-inner { fill: var(--bulb-color); transition: fill 0.6s ease; }
        .light-cone { opacity: var(--light-opacity); transition: opacity 0.6s ease; }
        .face-sleep { transition: opacity 0.3s ease; }
        .face-awake { opacity: 0; transition: opacity 0.3s ease; }
        .cheek { opacity: var(--light-opacity); transition: opacity 0.6s ease; }

        .burst-particle {
            opacity: 0; transform-origin: center;
        }
        .burst-active .burst-particle {
            animation: burst-out 0.7s ease-out forwards;
        }
        @keyframes burst-out {
            0% { opacity: 1; transform: scale(0) translate(0,0); }
            100% { opacity: 0; transform: scale(1.4) translate(var(--dx), var(--dy)); }
        }

        .pull-bow-group {
            cursor: pointer; transform-origin: top;
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .pull-bow-group:hover .bow-shape { filter: brightness(1.08); }
        .bow-shape { fill: var(--ribbon-color); transition: fill 0.6s ease; }

        .lamp-ambient-glow {
            position: absolute; width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(var(--theme-glow-rgb), 0.28) 0%, transparent 70%);
            top: 55%; left: 50%; transform: translate(-50%, -50%);
            z-index: -1; transition: background 0.6s ease; pointer-events: none;
        }

        .hint {
            margin-top: 1.25rem; font-size: 0.8rem; color: #9a8fb0;
            display: flex; align-items: center; gap: 0.35rem;
        }

        .login-section { flex: 1; display: flex; justify-content: center; align-items: center; }

        .login-card {
            width: 100%; max-width: 400px;
            background: rgba(255, 252, 250, 0.9); backdrop-filter: blur(14px);
            padding: 2.75rem 2.5rem; border-radius: 28px;
            border: 2.5px dashed rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 1px rgba(var(--theme-glow-rgb), 0.25) inset,
                        0 25px 45px -15px rgba(150, 130, 200, 0.35),
                        0 0 30px rgba(var(--theme-glow-rgb), 0.2);
            transition: box-shadow 0.6s ease;
            position: relative;
        }

        .login-card::before {
            content: '✧';
            position: absolute; top: -18px; left: 28px;
            font-size: 1.6rem; color: #ffb6e6;
            text-shadow: 0 0 10px rgba(255,182,230,0.8);
        }
        .login-card::after {
            content: '˚₊‧꒰ა ♡ ໒꒱ ‧₊˚';
            position: absolute; bottom: -1.6rem; left: 0; right: 0;
            text-align: center; font-size: 0.75rem; color: #b9a8d0; letter-spacing: 1px;
        }

        .login-card h2 {
            font-family: var(--font-display);
            font-size: 1.9rem; font-weight: 700; text-align: center;
            margin-bottom: 0.35rem; color: #4a3f66;
        }
        .login-card .sub {
            text-align: center; font-size: 0.85rem; color: #8a7fa0;
            margin-bottom: 1.75rem;
        }

        .input-group { margin-bottom: 1.35rem; display: flex; flex-direction: column; }
        .input-group label {
            font-size: 0.78rem; color: #7a6f95; margin-bottom: 0.4rem;
            font-weight: 600; letter-spacing: 0.3px;
        }

        .input-group input {
            background: #fffafd; border: 1.5px solid #f3b4d2;
            padding: 0.95rem 1.15rem; border-radius: 16px; color: #4a3f66; outline: none;
            font-family: var(--font-body); font-weight: 500;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .input-group input::placeholder { color: #b8aecb; }
        .input-group input:-webkit-autofill,
        .input-group input:-webkit-autofill:hover,
        .input-group input:-webkit-autofill:focus,
        .input-group input:-webkit-autofill:active {
            -webkit-text-fill-color: #4a3f66;
            caret-color: #4a3f66;
            -webkit-box-shadow: 0 0 0 1000px #fffafd inset;
            box-shadow: 0 0 0 1000px #fffafd inset;
            transition: background-color 9999s ease-out 0s;
        }
        .input-group input:focus {
            border-color: rgba(var(--theme-glow-rgb), 0.9);
            box-shadow: 0 0 0 4px rgba(var(--theme-glow-rgb), 0.18);
        }

        .login-btn {
            width: 100%; padding: 1rem; border: none; border-radius: 16px;
            background: var(--btn-bg); color: var(--btn-text-color);
            font-family: var(--font-display);
            font-size: 1rem; font-weight: 700; letter-spacing: 0.3px; cursor: pointer;
            margin-top: 0.5rem; transition: all 0.4s ease;
            box-shadow: 0 8px 20px -8px rgba(var(--theme-glow-rgb), 0.6);
        }
        .login-btn:hover { filter: brightness(1.06); transform: translateY(-2px); }
        .login-btn:active { transform: translateY(0); }

        .password-field { position: relative; }
        .password-field input { width: 100%; padding-right: 3.2rem; }
        .password-toggle {
            position: absolute; top: 50%; right: 0.85rem; transform: translateY(-50%);
            width: 34px; height: 34px; display: grid; place-items: center;
            padding: 0; color: #9a8fb0; background: transparent; border: 0;
            border-radius: 50%; cursor: pointer;
        }
        .password-toggle:hover { color: #ff8fd0; background: #fff0fa; }
        .password-toggle svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; }

        .register-prompt {
            margin-top: 1.4rem; padding: 0.85rem 1rem; text-align: center;
            color: #4a3f66;
            background: rgba(255,255,255,.92);
            border: 2px solid var(--theme-color); border-radius: 999px;
            box-shadow: 0 7px 18px -10px rgba(var(--theme-glow-rgb), .8), inset 0 1px 0 rgba(255,255,255,.85);
            font-size: 0.82rem; font-weight: 600;
            transition: background .4s ease, border-color .4s ease, box-shadow .4s ease;
        }
        .register-prompt a { color: var(--theme-color); font-weight: 800; text-decoration: none; transition: color .4s ease; }
        .register-prompt a:hover { color: #4a3f66; text-decoration: underline; }

        @media (max-width: 768px) {
            .container { flex-direction: column; height: auto; }
            .lamp-svg { max-width: 240px; }
            .login-card { margin-top: 1rem; }
        }
    </style>
</head>
<body>

    <div class="sparkle-field" id="sparkleField"></div>

    <div class="container">
        <div class="lamp-section">
            <div class="wordmark">Pinky Blog</div>
            <div class="tagline">✧ get ready ✧</div>

            <div class="lamp-ambient-glow"></div>
            <svg class="lamp-svg" viewBox="0 0 300 460" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="lightConeGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ffe9fb" stop-opacity="0.85" />
                        <stop offset="100%" stop-color="#ffb6e6" stop-opacity="0" />
                    </linearGradient>
                    <clipPath id="mouthClip">
                        <path d="M 125 155 Q 150 190 175 155 Z" />
                    </clipPath>
                </defs>

                <polygon points="90,180 210,180 320,450 -20,450" fill="url(#lightConeGrad)" class="light-cone" />

                <!-- sparkle burst particles, revealed on turn-on -->
                <g id="burstGroup">
                    <text class="burst-particle" x="150" y="170" style="--dx:-60px; --dy:-40px; font-size:18px;" fill="#ffb6e6">✧</text>
                    <text class="burst-particle" x="150" y="170" style="--dx:60px; --dy:-30px; font-size:14px;" fill="#d56d9f">✦</text>
                    <text class="burst-particle" x="150" y="170" style="--dx:-40px; --dy:20px; font-size:12px;" fill="#ffe08f">✧</text>
                    <text class="burst-particle" x="150" y="170" style="--dx:70px; --dy:30px; font-size:16px;" fill="#ffb6e6">✦</text>
                    <text class="burst-particle" x="150" y="170" style="--dx:0px; --dy:-60px; font-size:13px;" fill="#d56d9f">✧</text>
                </g>

                <ellipse cx="150" cy="410" rx="62" ry="15" fill="#e3d0e8" opacity="0.5" />
                <ellipse cx="150" cy="405" rx="62" ry="15" fill="#f6ecf9" />
                <rect x="140" y="185" width="20" height="220" fill="#e3c9ef" />
                <rect x="142" y="185" width="8" height="220" fill="#f6dcf9" />
                <ellipse cx="150" cy="180" rx="92" ry="20" class="shade-inner" />

                <g class="pull-bow-group" onclick="toggleLamp()">
                    <line x1="105" y1="185" x2="105" y2="270" stroke="#d9c3e0" stroke-width="3" />
                    <g class="bow-shape" transform="translate(105,290)">
                        <ellipse cx="-12" cy="0" rx="14" ry="9" transform="rotate(-20)" />
                        <ellipse cx="12" cy="0" rx="14" ry="9" transform="rotate(20)" />
                        <circle cx="0" cy="0" r="6" fill="#fff0fa" />
                    </g>
                </g>

                <path d="M 95 62 Q 150 46 205 62 L 242 180 Q 150 200 58 180 Z" class="shade-main" />

                <!-- cute blush cheeks, only visible when lamp is on -->
                <ellipse class="cheek" cx="112" cy="145" rx="9" ry="5" fill="#ffb6e6" opacity="0.6" />
                <ellipse class="cheek" cx="188" cy="145" rx="9" ry="5" fill="#ffb6e6" opacity="0.6" />

                <g class="face-sleep">
                    <path d="M 115 130 Q 125 140 135 130" stroke="#4a3f66" stroke-width="4" fill="none" stroke-linecap="round" />
                    <path d="M 165 130 Q 175 140 185 130" stroke="#4a3f66" stroke-width="4" fill="none" stroke-linecap="round" />
                </g>

                <g class="face-awake">
                    <path d="M 115 130 Q 125 115 135 130" stroke="#4a3f66" stroke-width="4" fill="none" stroke-linecap="round" />
                    <path d="M 165 130 Q 175 115 185 130" stroke="#4a3f66" stroke-width="4" fill="none" stroke-linecap="round" />
                    <g>
                        <path d="M 125 155 Q 150 190 175 155 Z" fill="#4a3f66" />
                        <path d="M 140 165 Q 150 190 160 165 Z" fill="#ff9dc9" clip-path="url(#mouthClip)" />
                    </g>
                </g>
            </svg>

            <div class="hint">✧ tap the bow to turn on the lamp ✧</div>
        </div>

        <div class="login-section">
            <div class="login-card">
                <h2>welcome back ♡</h2>
                <p class="sub">log in to your doll account</p>
                <form action="login.php" method="post">
                    <div class="input-group">
                        <label>email</label>
                        <input type="email" name="email" placeholder="e.g. name@example.com" autocomplete="email" required>
                    </div>
                    <div class="input-group">
                        <label>password</label>
                        <div class="password-field">
                            <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.8"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" name="login" class="login-btn">log in ✧</button>
                </form>
                <p class="register-prompt">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script>
        const lampStates = [
            { lamp: "OFF", themeColor: "#d56d9f", themeGlowRGB: "213, 109, 159", shadeColor: "#f6c6dc", bulbColor: "#fff0fa", lightOpacity: "0", btnBg: "#b9578c", btnText: "#fff", ribbon: "#ed8fbd", faceAwakeOpacity: "0", faceSleepOpacity: "1" },
            { lamp: "ON", themeColor: "#ff8fd0", themeGlowRGB: "255, 143, 208", shadeColor: "#ffc3e8", bulbColor: "#fff8fd", lightOpacity: "0.55", btnBg: "#d56d9f", btnText: "#fff", ribbon: "#ffb6e6", faceAwakeOpacity: "1", faceSleepOpacity: "0" },
            { lamp: "OFF", themeColor: "#d56d9f", themeGlowRGB: "213, 109, 159", shadeColor: "#f6c6dc", bulbColor: "#fff0fa", lightOpacity: "0", btnBg: "#b9578c", btnText: "#fff", ribbon: "#ed8fbd", faceAwakeOpacity: "0", faceSleepOpacity: "1" },
            { lamp: "ON", themeColor: "#ff8fd0", themeGlowRGB: "255, 143, 208", shadeColor: "#ffc3e8", bulbColor: "#fff8fd", lightOpacity: "0.55", btnBg: "#d56d9f", btnText: "#fff", ribbon: "#ffb6e6", faceAwakeOpacity: "1", faceSleepOpacity: "0" }
        ];

        let currentStateIndex = 0;
        const pullBow = document.querySelector('.pull-bow-group');
        const rootStyles = document.documentElement.style;
        const faceAwake = document.querySelector('.face-awake');
        const faceSleep = document.querySelector('.face-sleep');
        const burstGroup = document.getElementById('burstGroup');
        const passwordInput = document.getElementById('loginPassword');
        const passwordToggle = document.getElementById('passwordToggle');

        passwordToggle.addEventListener('click', () => {
            const showing = passwordInput.type === 'text';
            passwordInput.type = showing ? 'password' : 'text';
            passwordToggle.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
            passwordToggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
        });

        function toggleLamp() {
            pullBow.style.transform = 'translateY(15px)';
            setTimeout(() => { pullBow.style.transform = 'translateY(0)'; }, 150);

            currentStateIndex = (currentStateIndex + 1) % lampStates.length;
            const config = lampStates[currentStateIndex];

            rootStyles.setProperty('--theme-color', config.themeColor);
            rootStyles.setProperty('--theme-glow-rgb', config.themeGlowRGB);
            rootStyles.setProperty('--shade-color', config.shadeColor);
            rootStyles.setProperty('--bulb-color', config.bulbColor);
            rootStyles.setProperty('--light-opacity', config.lightOpacity);
            rootStyles.setProperty('--btn-bg', config.btnBg);
            rootStyles.setProperty('--btn-text-color', config.btnText);
            rootStyles.setProperty('--ribbon-color', config.ribbon);

            faceAwake.style.opacity = config.faceAwakeOpacity;
            faceSleep.style.opacity = config.faceSleepOpacity;

            if (config.lamp === "ON") {
                burstGroup.classList.remove('burst-active');
                void burstGroup.offsetWidth; // restart animation
                burstGroup.classList.add('burst-active');
            }
        }

        // ambient floating sparkles in the background
        const field = document.getElementById('sparkleField');
        const glyphs = ['✧', '✦', '˚', '♡', '⋆'];
        const sparkleCount = 22;
        for (let i = 0; i < sparkleCount; i++) {
            const el = document.createElement('span');
            el.className = 'sparkle';
            el.textContent = glyphs[Math.floor(Math.random() * glyphs.length)];
            el.style.left = Math.random() * 100 + 'vw';
            el.style.top = (100 + Math.random() * 20) + 'vh';
            el.style.fontSize = (0.7 + Math.random() * 1.1) + 'rem';
            const duration = 10 + Math.random() * 14;
            el.style.animationDuration = duration + 's, ' + (2 + Math.random() * 2) + 's';
            el.style.animationDelay = (Math.random() * duration) + 's, ' + (Math.random() * 2) + 's';
            field.appendChild(el);
        }
    </script>
    <?php require __DIR__ . '/includes/cookie_banner.php'; ?>
    <script src="js/cookies.js"></script>
</body>
</html>
