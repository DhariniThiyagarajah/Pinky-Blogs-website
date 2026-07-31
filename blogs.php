<?php
$pageTitle = 'Pinky Blogs';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$stmt = $conn->prepare(
    'SELECT b.id, b.title, b.content, b.created_at, b.thumbnail, u.username
     FROM blogPost b JOIN user u ON b.user_id = u.id
     ORDER BY b.created_at DESC'
);
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$recentAuthors = [];
foreach ($blogs as $blog) {
    if (!in_array($blog['username'], $recentAuthors, true)) $recentAuthors[] = $blog['username'];
}

$widgetGif = static function (string $filename): ?string {
    $relativePath = 'assests/images/blog-widgets/' . $filename;
    return file_exists(__DIR__ . '/' . $relativePath) ? $relativePath : null;
};
if (empty($_SESSION['chat_csrf'])) {
    $_SESSION['chat_csrf'] = bin2hex(random_bytes(32));
}

?>

<div class="neo-blog-shell">
    <header class="neo-blog-hero neo-window">
        <div class="neo-titlebar"><span>PINKY_BLOGS.EXE</span><span class="window-dots" aria-hidden="true">─ □ ×</span></div>
        <div class="neo-hero-content">
            <img class="neo-hero-banner" src="assests/images/blog-banner.png?v=<?= (int) filemtime(__DIR__ . '/assests/images/blog-banner.png') ?>" alt="" aria-hidden="true">
            <div>
                <p class="neo-kicker">community journal online ♡</p>
                <h1>Pinky Blogs</h1>
                <p>Cozy stories, reviews, memories and creations from our little corner of the web.</p>
            </div>
            <?php if (isLoggedIn()): ?><a href="create.php" class="neo-action">+ new entry</a><?php endif; ?>
        </div>
        <div class="neo-statusbar"><span>● ONLINE</span><span><?= count($blogs) ?> journal entries loaded</span></div>
    </header>

    <div class="neo-blog-layout">
        <main class="neo-feed neo-window">
            <div class="neo-titlebar"><span>♡ LATEST POSTS</span><span>scroll to browse ↓</span></div>
            <div class="neo-feed-inner">
                <?php if (!$blogs): ?>
                    <div class="neo-empty"><strong>No entries yet!</strong><p>The journal is waiting for its first story ♡</p></div>
                <?php else: ?>
                    <?php foreach ($blogs as $index => $blog):
                        $thumb = basename((string) ($blog['thumbnail'] ?? ''));
                        $thumbFile = __DIR__ . '/assests/blog-thumbnails/' . $thumb;
                    ?>
                        <article class="neo-post<?= ($thumb && file_exists($thumbFile)) ? ' has-thumbnail' : '' ?>">
                            <div class="neo-post-number">ENTRY_<?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>
                            <div class="neo-post-body">
                                <?php if ($thumb && file_exists($thumbFile)): ?>
                                    <a class="neo-post-thumb" href="view.php?id=<?= (int) $blog['id'] ?>">
                                        <img src="assests/blog-thumbnails/<?= rawurlencode($thumb) ?>" alt="Thumbnail for <?= e($blog['title']) ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="neo-post-copy">
                                    <div class="neo-post-meta"><span>@<?= e($blog['username']) ?></span><time datetime="<?= e(date('Y-m-d', strtotime($blog['created_at']))) ?>"><?= e(formatDate($blog['created_at'])) ?></time></div>
                                    <h2><a href="view.php?id=<?= (int) $blog['id'] ?>"><?= e($blog['title']) ?></a></h2>
                                    <p><?= e(createExcerpt($blog['content'], 180)) ?></p>
                                    <a class="neo-read-more" href="view.php?id=<?= (int) $blog['id'] ?>">open entry <span>→</span></a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

        <aside class="neo-blog-sidebar">
            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>ABOUT.TXT</span><span>♡</span></div>
                <div class="neo-widget-content">
                    <h2>Welcome!</h2>
                    <p>A shared journal for anime thoughts, personal stories and everything our writers love.</p>
                    <div class="neo-mini-stats"><span><strong><?= count($blogs) ?></strong> posts</span><span><strong><?= count($recentAuthors) ?></strong> writers</span></div>
                </div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>WRITERS.LOG</span><span>★</span></div>
                <div class="neo-widget-content">
                    <?php if ($recentAuthors): ?>
                        <ul class="neo-writer-list"><?php foreach (array_slice($recentAuthors, 0, 8) as $author): ?><li><span>♥</span> <?= e($author) ?></li><?php endforeach; ?></ul>
                    <?php else: ?><p>No writers online yet.</p><?php endif; ?>
                </div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>QUICK LINKS</span><span>⌁</span></div>
                <nav class="neo-quick-links">
                    <a href="index.php">⌂ homepage</a>
                    <?php if (isLoggedIn()): ?><a href="create.php">✎ write a blog</a><a href="profile.php">♡ my profile</a><?php else: ?><a href="login.php">♙ sign in</a><a href="register.php">+ join us</a><?php endif; ?>
                </nav>
            </section>

            <section class="neo-window neo-widget neo-warning-widget">
                <div class="neo-titlebar"><span>⚠ WARNING!</span><span>!</span></div>
                <?php if ($gif = $widgetGif('warning.gif')): ?><img class="neo-widget-gif" src="<?= e($gif) ?>" alt="Warning decoration"><?php endif; ?>
                <div class="neo-widget-content"><p>This corner of the web contains strong opinions, anime spoilers and lots of pink.</p></div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>CONTACT.ME</span><span>@</span></div>
                <?php if ($gif = $widgetGif('contact.gif')): ?><img class="neo-widget-gif" src="<?= e($gif) ?>" alt="Contact decoration"><?php endif; ?>
                <div class="neo-widget-content">
                    <p>Questions, ideas or just want to say hi?</p>
                    <a class="neo-widget-button" href="mailto:dharinithiyagarajahdharini@gmail.com">send an email</a>
                </div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>CHAT.LOG</span><span>●</span></div>
                <?php if ($gif = $widgetGif('chat.gif')): ?><img class="neo-widget-gif" src="<?= e($gif) ?>" alt="Chat decoration"><?php endif; ?>
                <div class="neo-widget-content neo-chat-widget">
                    <div id="chatMessages" class="neo-chat-messages" aria-live="polite">
                        <p class="neo-chat-loading">Loading messages...</p>
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <form id="chatForm" class="neo-chat-form">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['chat_csrf']) ?>">
                            <label for="chatMessage">Chatting as <strong><?= e($_SESSION['username']) ?></strong></label>
                            <div><input id="chatMessage" name="message" maxlength="300" autocomplete="off" placeholder="Write a message..." required><button type="submit">Send</button></div>
                            <span id="chatStatus" class="neo-chat-status" aria-live="polite"></span>
                        </form>
                    <?php else: ?>
                        <p class="neo-chat-login"><a href="login.php">Sign in</a> to join the chat.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>HOME.URL</span><span>⌂</span></div>
                <?php if ($gif = $widgetGif('home.gif')): ?><img class="neo-widget-gif" src="<?= e($gif) ?>" alt="Home decoration"><?php endif; ?>
                <div class="neo-widget-content"><p>Return to the main room.</p><a class="neo-widget-button" href="index.php">go home</a></div>
            </section>

            <section class="neo-window neo-widget neo-music-widget">
                <div class="neo-titlebar"><span>MUSIC_PLAYER</span><span>♫</span></div>
                <?php if ($gif = $widgetGif('music-player.gif')): ?><img class="neo-widget-gif" src="<?= e($gif) ?>" alt="Music player decoration"><?php endif; ?>
                <div class="neo-widget-content">
                    <p class="neo-track-name">♫ Cozy Song</p>
                    <audio id="blogMusic" preload="metadata" src="assests/music/cozy-song.mp3"></audio>
                    <div class="neo-player-controls"><button type="button" id="blogMusicButton" aria-label="Play music">▶</button><input id="blogMusicProgress" type="range" min="0" value="0" step="1" aria-label="Music progress"><span id="blogMusicTime">0:00</span></div>
                </div>
            </section>

            <section class="neo-window neo-widget">
                <div class="neo-titlebar"><span>YT_NEWS</span><span>▶</span></div>
                <div class="neo-youtube-player">
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/qCt9fLpPfdM?autoplay=1&amp;mute=1&amp;loop=1&amp;playlist=qCt9fLpPfdM&amp;playsinline=1&amp;controls=1&amp;rel=0"
                        title="Pinky Blog YouTube news video"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen></iframe>
                </div>
                <div class="neo-widget-content"><h2>Latest video</h2><p>Now playing automatically with sound muted.</p></div>
            </section>

        </aside>
    </div>
</div>

<script>
(() => {
    const audio = document.getElementById('blogMusic');
    const playButton = document.getElementById('blogMusicButton');
    const progress = document.getElementById('blogMusicProgress');
    const time = document.getElementById('blogMusicTime');
    const formatTime = seconds => `${Math.floor(seconds / 60)}:${String(Math.floor(seconds % 60)).padStart(2, '0')}`;
    if (audio && playButton) {
        playButton.addEventListener('click', () => audio.paused ? audio.play() : audio.pause());
        audio.addEventListener('play', () => { playButton.textContent = '❚❚'; playButton.setAttribute('aria-label', 'Pause music'); });
        audio.addEventListener('pause', () => { playButton.textContent = '▶'; playButton.setAttribute('aria-label', 'Play music'); });
        audio.addEventListener('loadedmetadata', () => { progress.max = Math.floor(audio.duration || 0); });
        audio.addEventListener('timeupdate', () => { progress.value = Math.floor(audio.currentTime); time.textContent = formatTime(audio.currentTime); });
        progress.addEventListener('input', () => { audio.currentTime = Number(progress.value); });
    }

    const messagesBox = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatStatus = document.getElementById('chatStatus');
    const renderMessages = messages => {
        messagesBox.replaceChildren();
        if (!messages.length) {
            const empty = document.createElement('p');
            empty.className = 'neo-chat-loading';
            empty.textContent = 'No messages yet. Say hello! ♡';
            messagesBox.append(empty);
            return;
        }
        messages.forEach(message => {
            const item = document.createElement('div');
            item.className = 'neo-chat-message';
            const header = document.createElement('div');
            const name = document.createElement('strong');
            const time = document.createElement('time');
            const text = document.createElement('p');
            name.textContent = '@' + message.username;
            time.textContent = message.time;
            text.textContent = message.message;
            header.append(name, time);
            item.append(header, text);
            messagesBox.append(item);
        });
        messagesBox.scrollTop = messagesBox.scrollHeight;
    };
    const loadChat = async () => {
        try {
            const response = await fetch('chat.php', { headers: { Accept: 'application/json' }, cache: 'no-store' });
            if (!response.ok) throw new Error('Chat unavailable');
            const data = await response.json();
            renderMessages(data.messages || []);
        } catch { if (chatStatus) chatStatus.textContent = 'Could not refresh chat.'; }
    };
    chatForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const button = chatForm.querySelector('button');
        button.disabled = true;
        chatStatus.textContent = '';
        try {
            const response = await fetch('chat.php', { method: 'POST', body: new FormData(chatForm), headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Message could not be sent.');
            chatForm.reset();
            await loadChat();
        } catch (error) { chatStatus.textContent = error.message; }
        finally { button.disabled = false; }
    });
    loadChat();
    window.setInterval(loadChat, 3000);
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
