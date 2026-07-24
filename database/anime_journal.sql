-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2026 at 09:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anime_journal`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogpost`
--

CREATE TABLE `blogpost` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogpost`
--

INSERT INTO `blogpost` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`, `thumbnail`) VALUES
(1, 1, 'Why Spirited Away Still Feels Like Coming Home', 'Every time I revisit Spirited Away, I am struck by how gently it welcomes you back. Chihiro\'s journey through the spirit world is not just a fantasy adventure — it is a story about growing up, losing your name, and finding courage in unfamiliar places.\r\n\r\nWhat moves me most is the bathhouse itself. It feels alive, cluttered, and strangely comforting, like a rainy afternoon spent in a countryside inn. The film never rushes you. It lets quiet moments breathe: train rides over flooded plains, soot sprites working in silence, Haku remembering who he was.\r\n\r\nSpirited Away reminds me that kindness can be brave. Chihiro does not defeat Yubaba with a sword. She wins through patience, empathy, and refusing to forget the people she cares about.\r\n\r\nIf you have not watched it in years, give it another evening. Make tea. Let the film unfold slowly. You might find, like I always do, that it still feels like coming home.', '2026-03-12 05:00:00', '2026-03-12 05:00:00', NULL),
(2, 2, 'Five Cozy Anime Perfect for Rainy Afternoons', 'Some days call for blankets, warm drinks, and stories that do not shout at you. Here are five anime I return to whenever the sky turns grey:\r\n\r\n1. Natsume\'s Book of Friends — gentle, melancholic, and full of quiet magic.\r\n2. Laid-Back Camp — friendship, thermoses, and the joy of doing very little.\r\n3. Barakamon — creativity, countryside calm, and learning to breathe again.\r\n4. Mushishi — poetic, mysterious, and perfect for slow listening.\r\n5. Aria the Animation — soft sci-fi with kindness at its center.\r\n\r\nCozy anime is not boring. It is restorative. These shows understand that peace can be just as memorable as action.', '2026-03-10 08:30:00', '2026-03-10 08:30:00', NULL),
(3, 3, 'Howl\'s Moving Castle: Restlessness Worn Like a Beautiful Coat', 'Howl is one of my favorite characters because he is glamorous and terrified at the same time. He performs confidence beautifully, yet his room tells another story — scattered papers, half-finished spells, a heart that keeps trying to disappear.\r\n\r\nSophie enters his life not as a damsel, but as someone who sees through performance. Her curse becomes a strange gift: she stops worrying about how the world sees her and starts telling the truth.\r\n\r\nThe film is about learning that running away does not make you free. Howl hides inside his castle, Sophie hides inside old age, and both must step into ordinary courage.\r\n\r\nEvery rewatch, I notice another small detail in the moving castle — cups, clutter, warmth. It feels like a home built by someone who desperately wants to belong.', '2026-03-08 03:45:00', '2026-03-08 03:45:00', NULL),
(4, 1, 'The Ending of Your Name Made Me Call an Old Friend', 'I watched Your Name alone on a laptop years ago and ended up staring at the credits in silence. It was not just the twist that got me. It was the idea that two people could share something so meaningful that their bodies remembered it even when their minds forgot.\r\n\r\nThe film captures that strange teenage feeling when everything matters urgently, yet you cannot explain why. The comet, the shrine, the braided cord — all of it becomes a language for connection.\r\n\r\nAfter it ended, I texted a friend I had drifted away from. We met for coffee the next week. I think that is the highest praise I can give a film: it made me reach for someone real.', '2026-03-05 13:15:00', '2026-03-05 13:15:00', NULL),
(5, 2, 'A Silent Voice and the Courage to Say Sorry', 'A Silent Voice is difficult to watch, but I think it should be required viewing for anyone who has ever been cruel without understanding the damage.\r\n\r\nShoya does not get an easy redemption. The film makes him sit with guilt, rejection, and the slow work of rebuilding trust. Shoko is not a symbol — she has her own pain, limits, and desires.\r\n\r\nWhat I love is how the movie treats communication as something physical. Words fail, sign language helps, silence matters, and listening becomes an act of love.\r\n\r\nIt left me thinking about the apologies I still owe, and the friendships worth repairing.', '2026-03-02 05:50:00', '2026-03-02 05:50:00', NULL),
(6, 3, 'Mitsuri Kanroji: Strength That Refuses to Be Loud', 'Mitsuri is often reduced to jokes about appetite and enthusiasm, but beneath that brightness is one of Demon Slayer\'s most interesting ideas: softness is not weakness.\r\n\r\nShe was told to shrink herself to become more \"acceptable.\" Instead, she built a fighting style around the body she actually has. Her flexibility, speed, and emotional honesty are strengths, not punchlines.\r\n\r\nIn a genre that loves brooding men with tragic backstories, Mitsuri is refreshing because she likes herself. She loves food, colors, people, and life without apology.\r\n\r\nCharacters like Mitsuri matter because they tell shy viewers that warmth can still be powerful.', '2026-02-28 10:30:00', '2026-02-28 10:30:00', NULL),
(7, 1, 'Weathering With You: What We Sacrifice for Clear Skies', 'Makoto Shinkai loves distance — between people, between seasons, between the world we want and the one we have. Weathering With You asks whether love is enough when the weather itself becomes a moral choice.\r\n\r\nHodaka and Hina feel like kids trying to build a tiny shelter inside a stormy society. Their sunshine service is hopeful, almost fairy-tale-like, until the film reminds us that miracles often cost someone else.\r\n\r\nI still think about the rooftop scene, the city glowing after rain, and the question lingering underneath: should one person carry the weight of everyone\'s sunshine?\r\n\r\nIt is messy, romantic, and painfully human.', '2026-02-25 08:00:00', '2026-02-25 08:00:00', NULL),
(8, 2, 'One Piece and the Art of Never Giving Up', 'One Piece is enormous, silly, heartbreaking, and sincere. I started it because friends would not stop talking about it. I stayed because it understands hope better than almost any story I know.\r\n\r\nLuffy is not a complex antihero. He wants freedom, food, and friends. Somehow that simplicity becomes profound over hundreds of episodes. Every crew member carries grief, yet they keep laughing on the ship.\r\n\r\nThe manga is especially beautiful when it slows down: Nami asking for help, Robin saying she wants to live, Brook remembering a promise for decades.\r\n\r\nIf you have ever thought the series is \"too long,\" I understand. But some journeys are long because they are worth walking slowly.', '2026-02-20 02:30:00', '2026-02-20 02:30:00', NULL),
(10, 1, 'Ponyo', 'Love Ponyo', '2026-07-16 21:21:47', '2026-07-16 21:21:47', ''),
(11, 1, 'Ponyo', 'I love Ponyo', '2026-07-16 21:26:32', '2026-07-16 21:26:32', 'awhhhh 😭.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT 'default-profile.png',
  `description` text DEFAULT NULL,
  `discord` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `x_link` varchar(255) DEFAULT NULL,
  `spotify` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `profile_image`, `description`, `discord`, `youtube`, `x_link`, `spotify`, `cover_image`) VALUES
(1, 'sakura_writer', 'sakura@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'default-profile.png', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'mochi_reviews', 'mochi@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'default-profile.png', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'lantern_dreams', 'lantern@animejournal.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'default-profile.png', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_blog_user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogpost`
--
ALTER TABLE `blogpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `fk_blog_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
