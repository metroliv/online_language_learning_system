-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:50 PM
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
-- Database: `language_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `user_name`, `topic`, `message`, `created_at`) VALUES
(1, 'victor', 'how can i do this', 'what', '2025-03-18 15:49:24'),
(2, 'victor', 'how can i do this', 'hiow', '2025-03-18 15:50:47'),
(3, 'victor', 'how can i do this', 'mknk', '2025-03-18 15:50:58'),
(4, 'victor', 'how can i do this', 'jhj', '2025-03-18 15:51:26'),
(5, 'victor', 'how can i do this', 'km', '2025-03-18 15:51:51'),
(6, 'victor', 'how can i do this', 'kmnk', '2025-03-18 15:52:16'),
(7, 'victor', 'how can i do this', 'lojmkl', '2025-03-18 15:56:07'),
(8, 'victor', 'how can i do this', 'p0oiuj', '2025-03-18 15:56:15'),
(9, 'victor', 'kl', 'poikjjik', '2025-03-18 15:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `lang_id` int(11) NOT NULL,
  `lang_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`lang_id`, `lang_name`) VALUES
(1, 'English'),
(2, 'Spanish'),
(3, 'French'),
(4, 'German'),
(5, 'Chinese'),
(6, 'Japanese'),
(7, 'Russian'),
(8, 'Arabic'),
(9, 'Portuguese'),
(10, 'Italian');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `audio_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `lang_id`, `title`, `content`, `audio_link`) VALUES
(1, 1, 'Basic Greetings', 'Learn how to greet people in English.', 'audio/english_greetings.mp3'),
(2, 1, 'Common Phrases', 'Essential phrases used in daily conversations.', 'audio/english_phrases.mp3'),
(3, 1, 'Numbers and Counting', 'Learn how to count and use numbers.', 'audio/english_numbers.mp3'),
(4, 2, 'Saludos Básicos', 'Aprende a saludar en español.', 'audio/spanish_greetings.mp3'),
(5, 2, 'Frases Comunes', 'Frases esenciales para la vida cotidiana.', 'audio/spanish_phrases.mp3'),
(6, 2, 'Números y Contar', 'Aprende a contar en español.', 'audio/spanish_numbers.mp3'),
(7, 3, 'Salutations de Base', 'Apprenez à saluer en français.', 'audio/french_greetings.mp3'),
(8, 3, 'Phrases Courantes', 'Phrases essentielles pour la conversation quotidienne.', 'audio/french_phrases.mp3'),
(9, 3, 'Les Nombres et le Comptage', 'Apprenez à compter en français.', 'audio/french_numbers.mp3'),
(10, 1, 'Days of the Week', 'Learn the names of the days in English.', 'audio/english_days.mp3'),
(11, 1, 'Telling Time', 'Learn how to ask and tell time in English.', 'audio/english_time.mp3'),
(12, 1, 'Basic Grammar', 'Introduction to English grammar rules.', 'audio/english_grammar.mp3'),
(13, 1, 'Travel Phrases', 'Common phrases for traveling and transportation.', 'audio/english_travel.mp3'),
(14, 1, 'Ordering Food', 'Learn how to order food in restaurants.', 'audio/english_food.mp3'),
(15, 2, 'Días de la Semana', 'Aprende los nombres de los días en español.', 'audio/spanish_days.mp3'),
(16, 2, 'Diciendo la Hora', 'Aprende a preguntar y decir la hora.', 'audio/spanish_time.mp3'),
(17, 2, 'Gramática Básica', 'Introducción a las reglas gramaticales del español.', 'audio/spanish_grammar.mp3'),
(18, 2, 'Frases de Viaje', 'Frases comunes para viajar y transporte.', 'audio/spanish_travel.mp3'),
(19, 2, 'Pidiendo Comida', 'Aprende a pedir comida en restaurantes.', 'audio/spanish_food.mp3'),
(20, 3, 'Jours de la Semaine', 'Apprenez les jours de la semaine en français.', 'audio/french_days.mp3'),
(21, 3, 'Dire l’Heure', 'Apprenez à dire et demander l’heure.', 'audio/french_time.mp3'),
(22, 3, 'Grammaire de Base', 'Introduction aux règles de grammaire française.', 'audio/french_grammar.mp3'),
(23, 3, 'Phrases de Voyage', 'Phrases courantes pour le voyage.', 'audio/french_travel.mp3'),
(24, 3, 'Commander de la Nourriture', 'Apprenez à commander de la nourriture.', 'audio/french_food.mp3'),
(25, 4, 'Grundlegende Begrüßungen', 'Lerne, wie man auf Deutsch grüßt.', 'audio/german_greetings.mp3'),
(26, 4, 'Häufige Sätze', 'Wichtige Sätze für den Alltag.', 'audio/german_phrases.mp3'),
(27, 4, 'Zahlen und Zählen', 'Lerne die Zahlen und das Zählen.', 'audio/german_numbers.mp3'),
(28, 4, 'Reisephrasen', 'Nützliche Phrasen für Reisen.', 'audio/german_travel.mp3'),
(29, 4, 'Essen bestellen', 'Lerne, wie man Essen in Restaurants bestellt.', 'audio/german_food.mp3'),
(30, 5, '基本问候', '学习如何用中文打招呼。', 'audio/chinese_greetings.mp3'),
(31, 5, '常用短语', '日常交流中最常用的短语。', 'audio/chinese_phrases.mp3'),
(32, 5, '数字和计数', '学习如何用中文数数。', 'audio/chinese_numbers.mp3'),
(33, 5, '旅行用语', '旅行和交通的常见短语。', 'audio/chinese_travel.mp3'),
(34, 5, '点餐', '学习如何在餐厅点餐。', 'audio/chinese_food.mp3'),
(35, 1, 'Common Idioms', 'Learn frequently used idioms in English.', 'audio/english_idioms.mp3'),
(36, 1, 'Business English', 'Essential vocabulary and phrases for the workplace.', 'audio/english_business.mp3'),
(37, 1, 'English Pronunciation Tips', 'Master English pronunciation with useful tips.', 'audio/english_pronunciation.mp3'),
(38, 1, 'Writing Emails in English', 'Learn how to write professional emails.', 'audio/english_emails.mp3'),
(39, 1, 'English for Interviews', 'Important expressions for job interviews.', 'audio/english_interviews.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `lesson_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'What does the idiom \"Break the ice\" mean?', 'To break something cold', 'To make people feel comfortable', 'To talk about ice', 'To get angry', 'B'),
(2, 2, 'Which is a formal greeting in business English?', 'Hey buddy!', 'Yo!', 'Good morning, Mr. Smith.', 'What’s up?', 'C'),
(3, 3, 'Which word is stressed in the phrase \"I never said she stole my money\"?', 'I', 'Said', 'Money', 'Stole', 'A'),
(4, 4, 'How should you start a formal email?', 'Hey there!', 'Yo!', 'Dear Sir/Madam,', 'What’s up?', 'C'),
(5, 5, 'Which phrase is suitable for a job interview?', 'I dunno', 'I am highly motivated and eager to learn.', 'Sup?', 'Let’s party!', 'B'),
(6, 6, '¿Qué significa \"Estar en las nubes\"?', 'Estar atento', 'Soñar despierto', 'Caer del cielo', 'Tener hambre', 'B'),
(7, 7, '¿Cómo se dice \"Nice to meet you\" en español?', 'Buenos días', 'Encantado/a de conocerte', 'Hasta luego', 'De nada', 'B'),
(8, 8, '¿Cuál es la pronunciación correcta de \"ll\" en español?', 'Como \"y\" en inglés', 'Como \"sh\" en inglés', 'Como \"ch\" en inglés', 'Como \"g\" en inglés', 'A'),
(9, 9, '¿Cómo iniciar un correo formal en español?', 'Oye, ¿qué pasa?', 'Hola amigo', 'Estimado/a señor/a', 'Nos vemos pronto', 'C'),
(10, 10, '¿Qué frase es apropiada para una entrevista de trabajo?', 'No sé', 'Estoy motivado y listo para aprender.', 'Vamos de fiesta', 'No me interesa', 'B'),
(11, 11, 'Que signifie \"Avoir le cafard\"?', 'Avoir un cafard chez soi', 'Être triste', 'Être fatigué', 'Avoir faim', 'B'),
(12, 12, 'Comment dire \"Good morning\" en français?', 'Bonsoir', 'Bonjour', 'Salut', 'Bonne nuit', 'B'),
(13, 13, 'Quel mot est mal prononcé dans \"Je suis allé au marché\"?', 'Je', 'Suis', 'Allé', 'Marché', 'D'),
(14, 14, 'Comment commencer un email formel en français?', 'Yo!', 'Cher Monsieur/Madame,', 'Salut!', 'Ça roule?', 'B'),
(15, 15, 'Quelle phrase est correcte pour un entretien d’embauche?', 'J’adore faire la fête!', 'Je suis motivé et prêt à apprendre.', 'Je ne sais pas.', 'On y va!', 'B'),
(16, 16, 'Was bedeutet \"Tomaten auf den Augen haben\"?', 'Etwas nicht sehen', 'Tomaten essen', 'Müde sein', 'Glücklich sein', 'A'),
(17, 17, 'Wie begrüßt man jemanden formell auf Deutsch?', 'Was geht?', 'Hallo', 'Guten Tag', 'Na?', 'C'),
(18, 18, 'Welcher Buchstabe wird am stärksten betont in \"Ich liebe dich\"?', 'Ich', 'Liebe', 'Dich', 'Alle gleich', 'B'),
(19, 19, 'Wie beginnt man eine formelle E-Mail auf Deutsch?', 'Hi Kumpel!', 'Sehr geehrter Herr/Frau,', 'Tschüss!', 'Yo!', 'B'),
(20, 20, 'Welche Aussage ist für ein Bewerbungsgespräch geeignet?', 'Ich liebe es zu feiern!', 'Ich bin motiviert und lernbereit.', 'Keine Ahnung.', 'Lass uns Spaß haben!', 'B'),
(21, 21, '成语\"画蛇添足\"的意思是什么？', '给蛇加上腿', '画画比赛', '做多余的事', '旅行', 'C'),
(22, 22, '如何用中文正式问候别人？', '你好！', '喂！', '嘿！', '咋样？', 'A'),
(23, 23, '汉语拼音中的\"zh\"发音像什么？', 'Sh', 'Ch', 'J', 'Z', 'B'),
(24, 24, '正式邮件如何开头？', '嘿，你好！', '亲爱的先生/女士，', '喂！', '怎么啦？', 'B'),
(25, 25, '面试时，哪种回答最合适？', '我不知道。', '我对这个职位很感兴趣并愿意学习。', '让我们玩吧！', '别烦我！', 'B'),
(26, 1, 'How do you say hello in English?', 'Hola', 'Bonjour', 'Hello', 'Ciao', 'C'),
(27, 1, 'Which of these is a polite greeting?', 'Go away', 'Good morning', 'Bye', 'Whatever', 'B'),
(28, 1, 'What is the correct response to \"How are you?\"', 'I am fine, thank you.', 'Go away.', 'Nothing.', 'Leave me alone.', 'A'),
(29, 1, 'Which greeting is most formal?', 'Hey!', 'Yo!', 'Good evening.', 'What’s up?', 'C'),
(30, 1, 'What do you say when you leave?', 'Hello', 'Goodbye', 'Nice to meet you', 'Pardon?', 'B'),
(31, 1, 'Which greeting is best for a business meeting?', 'Hey dude!', 'Good afternoon.', 'Yo!', 'Sup?', 'B'),
(32, 1, 'What is the correct response to \"Nice to meet you\"?', 'Same to you.', 'Nice to meet you too.', 'No, thanks.', 'What?', 'B'),
(33, 1, 'How do you greet someone in the morning?', 'Good night.', 'Good afternoon.', 'Good morning.', 'See you.', 'C'),
(34, 1, 'Which phrase is commonly used on the phone?', 'Hello, who is this?', 'Yo, what’s up?', 'Goodbye.', 'Talk later.', 'A'),
(35, 1, 'What is a friendly way to greet a friend?', 'Dear Sir/Madam', 'Hey, how’s it going?', 'Pleased to meet you.', 'Excuse me.', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_access_level` int(11) NOT NULL DEFAULT 1,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `created_at`, `user_access_level`, `phone_number`) VALUES
(1, 'victor', 'victormulinge92@gmail.com', '$2y$10$7VuAlhVG7R6hfkSQYFpAz.JBjjJDfEY5Ee2HRicoE2KDlEIpKlF7C', '2025-03-18 10:35:16', 1, ''),
(2, 'admin', 'mulingevictor01@gmail.com', '$2y$10$8xJE5eH6dZ.4Cny1.Tbjj.49d1Sa1hpRB3PjXa2EIPl33vXv3x1hK', '2025-03-18 16:41:25', 1, ''),
(3, 'victor mulinge muendo', 'victormulinge@gmail.com', '$2y$10$N7WE5iB1BbjGNXvDkfn91eEAMDA22aOX4XFr/KkbDuM2tlni2GIY2', '2025-03-18 17:11:25', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `progress_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `status` enum('Completed','In Progress') DEFAULT NULL,
  `score` int(11) DEFAULT 0,
  `user_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`progress_id`, `user_id`, `lesson_id`, `status`, `score`, `user_name`) VALUES
(1, 1, 1, 'Completed', 0, NULL),
(2, 1, 1, 'Completed', 0, NULL),
(3, 1, 1, 'Completed', 0, NULL),
(4, 1, 1, 'Completed', 100, NULL),
(5, 1, 1, 'Completed', 100, NULL),
(6, 1, 1, 'Completed', 0, NULL),
(7, 1, 1, 'Completed', 0, NULL),
(8, 1, 1, 'Completed', 0, NULL),
(9, 1, 1, 'Completed', 0, NULL),
(10, 1, 1, 'Completed', 0, NULL),
(11, 1, 1, 'Completed', 0, NULL),
(12, 1, 1, 'Completed', 0, NULL),
(13, 1, 1, 'Completed', 100, NULL),
(14, 1, 1, 'Completed', 100, NULL),
(15, 1, 1, 'Completed', 100, NULL),
(16, 1, 2, 'Completed', 100, NULL),
(17, 1, 1, 'Completed', 91, NULL),
(18, 1, 1, 'Completed', 100, NULL),
(19, 1, 1, 'Completed', 45, NULL),
(20, 1, 1, 'Completed', 36, NULL),
(21, 1, 2, 'Completed', 0, NULL),
(22, 1, 1, 'Completed', 18, NULL),
(23, 1, 2, 'Completed', 0, NULL),
(24, 1, 2, 'Completed', 100, NULL),
(25, 1, 2, 'Completed', 0, NULL),
(26, 1, 2, 'Completed', 0, NULL),
(27, 1, 2, 'Completed', 0, NULL),
(28, 1, 2, 'Completed', 0, NULL),
(29, 1, 2, 'Completed', 0, NULL),
(30, 1, 2, 'Completed', 100, NULL),
(31, 1, 2, 'Completed', 100, NULL),
(32, 1, 2, 'Completed', 100, NULL),
(33, 1, 2, 'Completed', 100, NULL),
(34, 1, 2, 'Completed', 100, NULL),
(35, 1, 2, 'Completed', 100, NULL),
(36, 1, 2, 'Completed', 100, NULL),
(37, 1, 2, 'Completed', 100, NULL),
(38, 1, 2, 'Completed', 100, NULL),
(39, 1, 2, 'Completed', 100, NULL),
(40, 1, 2, 'Completed', 100, NULL),
(41, 1, 2, 'Completed', 100, NULL),
(42, 1, 2, 'Completed', 100, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `lang_id` (`lang_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`);

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
