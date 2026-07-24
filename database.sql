-- Anime Journal Database Schema
-- Import this file via phpMyAdmin or MySQL CLI before running the application.

CREATE DATABASE IF NOT EXISTS anime_journal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE anime_journal;

-- Users table: stores registered accounts
CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog posts table: stores user-created blog entries
CREATE TABLE IF NOT EXISTS blogPost (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_blog_user
        FOREIGN KEY (user_id) REFERENCES user(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_blog_user_id ON blogPost(user_id);

-- =====================================================
-- Sample data (demo password for all users: password)
-- =====================================================

INSERT INTO user (username, email, password, role) VALUES
('sakura_writer', 'sakura@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('mochi_reviews', 'mochi@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('lantern_dreams', 'lantern@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO blogPost (user_id, title, content, created_at, updated_at) VALUES
(1, 'Why Spirited Away Still Feels Like Coming Home',
'Every time I revisit Spirited Away, I am struck by how gently it welcomes you back. Chihiro''s journey through the spirit world is not just a fantasy adventure — it is a story about growing up, losing your name, and finding courage in unfamiliar places.

What moves me most is the bathhouse itself. It feels alive, cluttered, and strangely comforting, like a rainy afternoon spent in a countryside inn. The film never rushes you. It lets quiet moments breathe: train rides over flooded plains, soot sprites working in silence, Haku remembering who he was.

Spirited Away reminds me that kindness can be brave. Chihiro does not defeat Yubaba with a sword. She wins through patience, empathy, and refusing to forget the people she cares about.

If you have not watched it in years, give it another evening. Make tea. Let the film unfold slowly. You might find, like I always do, that it still feels like coming home.',
'2026-03-12 10:30:00', '2026-03-12 10:30:00'),

(2, 'Five Cozy Anime Perfect for Rainy Afternoons',
'Some days call for blankets, warm drinks, and stories that do not shout at you. Here are five anime I return to whenever the sky turns grey:

1. Natsume''s Book of Friends — gentle, melancholic, and full of quiet magic.
2. Laid-Back Camp — friendship, thermoses, and the joy of doing very little.
3. Barakamon — creativity, countryside calm, and learning to breathe again.
4. Mushishi — poetic, mysterious, and perfect for slow listening.
5. Aria the Animation — soft sci-fi with kindness at its center.

Cozy anime is not boring. It is restorative. These shows understand that peace can be just as memorable as action.',
'2026-03-10 14:00:00', '2026-03-10 14:00:00'),

(3, 'Howl''s Moving Castle: Restlessness Worn Like a Beautiful Coat',
'Howl is one of my favorite characters because he is glamorous and terrified at the same time. He performs confidence beautifully, yet his room tells another story — scattered papers, half-finished spells, a heart that keeps trying to disappear.

Sophie enters his life not as a damsel, but as someone who sees through performance. Her curse becomes a strange gift: she stops worrying about how the world sees her and starts telling the truth.

The film is about learning that running away does not make you free. Howl hides inside his castle, Sophie hides inside old age, and both must step into ordinary courage.

Every rewatch, I notice another small detail in the moving castle — cups, clutter, warmth. It feels like a home built by someone who desperately wants to belong.',
'2026-03-08 09:15:00', '2026-03-08 09:15:00'),

(1, 'The Ending of Your Name Made Me Call an Old Friend',
'I watched Your Name alone on a laptop years ago and ended up staring at the credits in silence. It was not just the twist that got me. It was the idea that two people could share something so meaningful that their bodies remembered it even when their minds forgot.

The film captures that strange teenage feeling when everything matters urgently, yet you cannot explain why. The comet, the shrine, the braided cord — all of it becomes a language for connection.

After it ended, I texted a friend I had drifted away from. We met for coffee the next week. I think that is the highest praise I can give a film: it made me reach for someone real.',
'2026-03-05 18:45:00', '2026-03-05 18:45:00'),

(2, 'A Silent Voice and the Courage to Say Sorry',
'A Silent Voice is difficult to watch, but I think it should be required viewing for anyone who has ever been cruel without understanding the damage.

Shoya does not get an easy redemption. The film makes him sit with guilt, rejection, and the slow work of rebuilding trust. Shoko is not a symbol — she has her own pain, limits, and desires.

What I love is how the movie treats communication as something physical. Words fail, sign language helps, silence matters, and listening becomes an act of love.

It left me thinking about the apologies I still owe, and the friendships worth repairing.',
'2026-03-02 11:20:00', '2026-03-02 11:20:00'),

(3, 'Mitsuri Kanroji: Strength That Refuses to Be Loud',
'Mitsuri is often reduced to jokes about appetite and enthusiasm, but beneath that brightness is one of Demon Slayer''s most interesting ideas: softness is not weakness.

She was told to shrink herself to become more "acceptable." Instead, she built a fighting style around the body she actually has. Her flexibility, speed, and emotional honesty are strengths, not punchlines.

In a genre that loves brooding men with tragic backstories, Mitsuri is refreshing because she likes herself. She loves food, colors, people, and life without apology.

Characters like Mitsuri matter because they tell shy viewers that warmth can still be powerful.',
'2026-02-28 16:00:00', '2026-02-28 16:00:00'),

(1, 'Weathering With You: What We Sacrifice for Clear Skies',
'Makoto Shinkai loves distance — between people, between seasons, between the world we want and the one we have. Weathering With You asks whether love is enough when the weather itself becomes a moral choice.

Hodaka and Hina feel like kids trying to build a tiny shelter inside a stormy society. Their sunshine service is hopeful, almost fairy-tale-like, until the film reminds us that miracles often cost someone else.

I still think about the rooftop scene, the city glowing after rain, and the question lingering underneath: should one person carry the weight of everyone''s sunshine?

It is messy, romantic, and painfully human.',
'2026-02-25 13:30:00', '2026-02-25 13:30:00'),

(2, 'One Piece and the Art of Never Giving Up',
'One Piece is enormous, silly, heartbreaking, and sincere. I started it because friends would not stop talking about it. I stayed because it understands hope better than almost any story I know.

Luffy is not a complex antihero. He wants freedom, food, and friends. Somehow that simplicity becomes profound over hundreds of episodes. Every crew member carries grief, yet they keep laughing on the ship.

The manga is especially beautiful when it slows down: Nami asking for help, Robin saying she wants to live, Brook remembering a promise for decades.

If you have ever thought the series is "too long," I understand. But some journeys are long because they are worth walking slowly.',
'2026-02-20 08:00:00', '2026-02-20 08:00:00');
