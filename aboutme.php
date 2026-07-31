<?php
$pageTitle = 'About Me';
require_once __DIR__ . '/includes/header.php';

$aboutAsset = static function (string $filename): ?string {
    $directory = __DIR__ . '/assests/images/about/';
    $candidates = [$filename];
    $stem = pathinfo($filename, PATHINFO_FILENAME);

    foreach (['jpeg', 'jpg', 'png', 'gif', 'webp'] as $extension) {
        $candidates[] = $stem . '.' . $extension;
    }

    foreach (array_unique($candidates) as $candidate) {
        $path = $directory . $candidate;
        if (is_file($path)) {
            return 'assests/images/about/' . rawurlencode($candidate) . '?v=' . filemtime($path);
        }
    }

    return null;
};
$galleryCaptions = ['I love', 'Anya', 'Sweet blush', 'Gojo', 'humm', 'yay us'];
?>

<div class="about-site">
    <header class="about-hero about-panel">
        <?php if ($banner = $aboutAsset('about-banner.jpeg')): ?>
            <img class="about-hero-image" src="<?= e($banner) ?>" alt="I’m a cat lover">
        <?php else: ?>
            <div class="about-hero-placeholder"><span>about-banner.jpeg</span></div>
        <?php endif; ?>
        <div class="about-hero-copy">
            <p class="about-eyebrow">welcome to my little internet corner</p>
            <h1>Tanya_Chan</h1>
            <p>Welcome Home</p>
        </div>
    </header>

    <nav class="about-jump-nav" aria-label="About page sections">
        <a href="#hello">Hello</a><a href="#favorites">Favourites</a><a href="#media">Media</a><a href="#gallery">Gallery</a><a href="#site">Site</a><a href="#contact">Contact</a>
    </nav>

    <div class="about-grid">
        <aside class="about-sidebar">
            <section class="about-panel profile-widget">
                <h2>Profile</h2>
                <?php if ($avatar = $aboutAsset('about-avatar.jpg')): ?>
                    <img class="about-avatar" src="<?= e($avatar) ?>" alt="That’s me — Tanya">
                <?php else: ?>
                    <div class="about-avatar about-image-placeholder">about-avatar.jpg</div>
                <?php endif; ?>
                <h3>Tanya_Chan</h3>
                <p class="soft">Tanya · BSc.IT&amp;M undergraduate</p>
                <dl class="quick-facts"><div><dt>Pronouns</dt><dd>Taa_n_ya</dd></div><div><dt>From</dt><dd>Sri Lanka</dd></div><div><dt>Birthday</dt><dd>16 April 2004</dd></div><div><dt>Languages</dt><dd>English</dd></div></dl>
            </section>

            <section class="about-panel status-widget">
                <h2>Status</h2>
                <p><span class="online-dot"></span> online</p>
                <blockquote>Always happy — studying all the time and having fun with friends. Currently obsessed with anime.</blockquote>
                <small>Last updated: <?= date('F Y') ?></small>
            </section>

            <section class="about-panel">
                <h2>Find me</h2>
                <div class="about-links"><a href="mailto:thiyagarajahdharini@gmail.com">Email</a><a href="profile.php">Pinky profile</a><a href="blogs.php">My blogs</a></div>
            </section>

            <section class="about-panel about-gif-panel">
                <h2>My mascot</h2>
                <?php if ($sideGif = $aboutAsset('about-side.gif')): ?><img src="<?= e($sideGif) ?>" alt="Tanya"><?php else: ?><div class="about-gif-placeholder">about-side.gif</div><?php endif; ?>
            </section>
        </aside>

        <main class="about-main">
            <section class="about-panel about-copy" id="hello">
                <div class="section-heading"><span>01</span><div><p>introduction.txt</p><h2>Hello!</h2></div></div>
                <p>Hello guys, this is Tanya. Welcome to my cozy Pinky Blog, which I created for you all to read, write, and play games. You can have fun talking to other users here.</p>
                <p>Well, that’s all for now. Have fun using my site!</p>
                <div class="note-box"><strong>Little note</strong><p>Welcome Home ♡</p></div>
            </section>

            <section class="about-panel" id="favorites">
                <div class="section-heading"><span>02</span><div><p>favourites.list</p><h2>Things I love</h2></div></div>
                <div class="interest-grid">
                    <article><h3>Favourite things</h3><ul><li>Chainsaw Man</li><li>Pizza</li><li>Mojito</li></ul></article>
                    <article><h3>Entertainment</h3><ul><li>Anime</li><li>Movies and TV series</li><li>Books</li></ul></article>
                    <article><h3>Quick facts</h3><ul><li>Favourite colour: Black</li><li>Personality: Funny</li><li>Current obsession: Anime</li></ul></article>
                </div>
            </section>

            <section class="about-panel" id="media">
                <div class="section-heading"><span>03</span><div><p>now_playing.exe</p><h2>Media corner</h2></div></div>
                <div class="media-grid">
                    <div class="now-card"><span>Watching</span><strong>Chainsmoker Cat</strong></div>
                    <div class="now-card"><span>Reading</span><strong>Girl In Peaces</strong></div>
                    <div class="now-card"><span>Playing</span><strong>Genshin Impact</strong></div>
                </div>
            </section>

            <section class="about-panel" id="gallery">
                <div class="section-heading"><span>04</span><div><p>memories.folder</p><h2>Mini gallery</h2></div></div>
                <div class="about-gallery">
                    <?php for ($i = 1; $i <= 6; $i++): $galleryImage = $aboutAsset("about-gallery-$i.gif"); ?>
                        <figure><?php if ($galleryImage): ?><img src="<?= e($galleryImage) ?>" alt="<?= e($galleryCaptions[$i - 1]) ?>"><?php else: ?><div class="gallery-placeholder">about-gallery-<?= $i ?>.gif</div><?php endif; ?><figcaption><?= e($galleryCaptions[$i - 1]) ?></figcaption></figure>
                    <?php endfor; ?>
                </div>
            </section>

            <section class="about-panel" id="site">
                <div class="section-heading"><span>05</span><div><p>website.info</p><h2>About Pinky Blog</h2></div></div>
                <div class="site-info-grid"><div><strong>Created</strong><span>2026</span></div><div><strong>Made with</strong><span>PHP · CSS · JavaScript</span></div><div><strong>Theme</strong><span>Cozy anime journal</span></div><div><strong>Best viewed</strong><span>On any modern screen</span></div></div>
                <h3 class="subheading">Updates</h3>
                <ul class="update-log"><li><time><?= date('Y-m-d') ?></time><span>About Me page added.</span></li><li><time>2026</time><span>Pinky Blog opened its doors.</span></li></ul>
            </section>

            <section class="about-panel contact-panel" id="contact">
                <div class="section-heading"><span>06</span><div><p>guestbook.mail</p><h2>Say hello</h2></div></div>
                <p>Questions, kind messages, recommendations, and friendly hellos are always welcome.</p>
                <a class="about-contact-button" href="mailto:thiyagarajahdharini@gmail.com">Send me an email</a>
            </section>
        </main>

        <aside class="about-sidebar right">
            <section class="about-panel">
                <h2>Navigation</h2><div class="about-links"><a href="index.php">Home</a><a href="blogs.php">Blogs</a><a href="game.html">Game</a><a href="profile.php">Profile</a></div>
            </section>
            <section class="about-panel">
                <h2>Likes</h2><div class="tag-cloud"><span>anime</span><span>movies</span><span>books</span><span>TV series</span></div>
            </section>
            <section class="about-panel">
                <h2>Dislikes</h2><ul class="tiny-list"><li>Boring movies</li><li>People with terrible mood swings</li><li>Short-tempered behaviour</li></ul>
            </section>
            <section class="about-panel">
                <h2>Goals</h2><label class="goal"><input type="checkbox" disabled> Having fun</label><label class="goal"><input type="checkbox" disabled> Travelling</label><label class="goal"><input type="checkbox" disabled> Eating</label><label class="goal"><input type="checkbox" disabled> Earning</label>
            </section>
            <section class="about-panel button-wall">
                <h2>Buttons & blinkies</h2>
                <?php for ($i = 1; $i <= 4; $i++): $button = $aboutAsset("about-button-$i.gif"); ?>
                    <?php if ($button): ?><img src="<?= e($button) ?>" alt="Decorative website button <?= $i ?>"><?php else: ?><span>about-button-<?= $i ?>.gif</span><?php endif; ?>
                <?php endfor; ?>
            </section>
        </aside>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
