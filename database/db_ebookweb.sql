-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2024 at 03:08 AM
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
-- Database: `db_ebookweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'TNO'),
(2, 'Kadokawa'),
(3, 'yenisey'),
(4, 'Fuse'),
(6, 'asasas'),
(7, 'George Orwell'),
(8, 'Sun Tzu'),
(9, 'Musashi Miyamoto'),
(10, 'David Glantz'),
(11, 'Tom Clancy'),
(12, 'John Lewis');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `pdf_url` varchar(255) DEFAULT NULL,
  `date_published` date DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `language` varchar(50) NOT NULL,
  `author_id` int(11) NOT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `isbn` varchar(20) NOT NULL,
  `pages` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `description`, `price`, `image_url`, `pdf_url`, `date_published`, `date_added`, `language`, `author_id`, `publisher`, `isbn`, `pages`) VALUES
(34, 'Tensura Volume 6', 'Rimuru adventure around the world', 23.00, 'uploads/resized_71lz+YIi8zL._AC_UF894,1000_QL80_.jpg', 'pdf_files/Tensura Volume 6.pdf', '2024-06-08', '2024-06-17', 'English', 4, 'Japan', ' 9781632366405', 326),
(36, 'Tensura Vol 7', 'Volume 7', 12.00, 'uploads/resized_cover.png', 'pdf_files/Tensura Vol 7.pdf', '2024-06-14', '2024-06-18', 'English', 4, 'Japan', ' 9781975301163 ', 317),
(37, 'Tensura Vol 8', 'Volume 8', 23.00, 'uploads/resized_cover (1).png', 'pdf_files/Tensura Vol 8.pdf', '2024-06-12', '2024-06-18', 'English', 4, 'Japan', ' 9781975301164', 257),
(38, 'Tensura Vol 9', 'Volume 9', 23.00, 'uploads/resized_cover (2).png', 'pdf_files/Tensura Vol 9.pdf', '2024-06-02', '2024-06-18', 'English', 4, 'Japan', ' 9781975301165', 284),
(39, 'Volume 10', 'Volume 10', 32.00, 'uploads/resized_cover.jpeg', 'pdf_files/Volume 10.pdf', '2024-06-02', '2024-06-18', 'English', 4, 'Japan', ' 9781975301166', 301),
(40, 'Tensura Volume 11', 'Volume 11', 23.00, 'uploads/resized_cover (3).png', 'pdf_files/Tensura Volume 11.pdf', '2024-06-06', '2024-06-18', 'English', 4, 'Japan', ' 9781975301168', 301),
(41, 'Tensura Volume 12', 'Volume 12', 23.00, 'uploads/resized_cover (4).png', 'pdf_files/Tensura Volume 12.pdf', '2024-06-21', '2024-06-18', 'English', 4, 'Japan', ' 9781975301169', 263),
(42, 'Art of War', 'Art of War by Sun Tzu', 21.00, 'uploads/resized_the-art-of-war-9781626860605_hr.jpg', 'pdf_files/Art of War.pdf', '2024-06-13', '2024-06-18', 'English', 8, 'China', ' 9781975301182', 66),
(44, 'A Book of Five Rings', 'Masterpiece of a book', 12.00, 'uploads/resized_867247.jpg', 'pdf_files/A Book of Five Rings.pdf', '2024-06-08', '2024-06-18', 'English', 9, 'Japan', ' 9781975301180', 102),
(45, 'When Titans Clashed', 'World War II', 21.00, 'uploads/resized_91QdDeRg54L._AC_UF1000,1000_QL80_.jpg', 'pdf_files/When Titans Clashed.pdf', '2024-06-09', '2024-06-18', 'English', 10, 'England', ' 9781975301143', 577),
(46, 'Hunt for Red October', 'Red October', 12.00, 'uploads/resized_mini_magick20190616-19681-xwyhn7.png', 'pdf_files/Hunt for Red October.pdf', '2024-06-21', '2024-06-18', 'English', 11, 'England', ' 9781975301132', 302),
(48, 'The Cold War ', 'The Cold War ended in 1991 with a fall of Soviet Union. Since then, one full generation has grown up who have no personal recollection of that era. The libraries are full of scholarly tomes covering the Cold War period, which tell the whole story. However, it is rare to find a book which captures the essence of the Cold War in one concise volume, readable by students and the general public. John Lewis Gaddis, a professor of history at Yale University has filled up that gap by writing his treatise “The Cold War: A New History” especially aimed at students and the laymen. This book review covers the various facets of Lewis’s book giving the strengths and weaknesses of the author’s treatment of the subject.', 23.00, 'uploads/resized_coldwar.JPG', 'pdf_files/The Cold War .pdf', '2024-06-08', '2024-06-18', 'English', 12, 'England', '4896374738', 254);

-- --------------------------------------------------------

--
-- Table structure for table `book_genres`
--

CREATE TABLE `book_genres` (
  `book_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_genres`
--

INSERT INTO `book_genres` (`book_id`, `genre_id`) VALUES
(34, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 4),
(44, 4),
(45, 4),
(46, 4),
(48, 4);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(4, 'History'),
(2, 'Horror'),
(1, 'Manga'),
(3, 'Politics');

-- --------------------------------------------------------

--
-- Table structure for table `payment_options`
--

CREATE TABLE `payment_options` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `expiration_date` date NOT NULL,
  `cvv` int(11) NOT NULL,
  `billing_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_options`
--

INSERT INTO `payment_options` (`id`, `user_id`, `card_number`, `expiration_date`, `cvv`, `billing_address`) VALUES
(3, 5, '1', '2024-06-19', 2233, '12222');

-- --------------------------------------------------------

--
-- Table structure for table `purchased_books`
--

CREATE TABLE `purchased_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchased_books`
--

INSERT INTO `purchased_books` (`id`, `user_id`, `book_id`, `purchase_date`) VALUES
(4, 5, 34, '2024-06-17 19:45:16'),
(6, 5, 44, '2024-06-18 06:45:29'),
(7, 5, 46, '2024-06-18 06:45:44'),
(8, 5, 45, '2024-06-18 06:46:01'),
(10, 5, 39, '2024-06-18 06:49:17'),
(11, 3, 39, '2024-06-18 07:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `book_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(23, 34, 5, 5, 'i like slime', '2024-06-17 12:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_cards`
--

CREATE TABLE `user_cards` (
  `card_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `expiration_date` date NOT NULL,
  `cvv` varchar(4) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cards`
--

INSERT INTO `user_cards` (`card_id`, `user_id`, `card_number`, `expiration_date`, `cvv`, `billing_address`, `created_at`) VALUES
(9, 5, '21221', '2024-06-14', '212', 'Stone Street', '2024-06-17 14:28:39'),
(10, 5, '33333', '2024-06-13', '122', 'Water Street', '2024-06-17 14:28:57'),
(11, 5, '92399', '2024-06-22', '122', 'Fire Street', '2024-06-17 14:29:10'),
(12, 3, '2121', '2024-06-05', '221', 'Bruh Street', '2024-06-18 00:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `is_admin` int(11) DEFAULT 0,
  `display_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `username`, `email`, `password`, `is_admin`, `display_name`) VALUES
(3, 'robin', 'robin123@gmail.com', '$2y$10$BesDSL2euap.ppCCRRwUTOu4WNDQUKLsjaVYJxQcmvGGK0EKiX406', 0, ''),
(4, 'tunguska', 'tunguska@gmail.com', '$2y$10$N2gM3/qo4KhoVgMC9XnLquc9RuwODq37z0.xxbkJt2IfbT26j46Ii', 1, ''),
(5, 'batman', 'batman@gmail.com', '$2y$10$5SwukimMy7BqJ2SfJkRHE.Pfb9tta9iynFThjRGQaD8k0/pEGsTHW', 0, ''),
(6, 'brucewayne', 'brucewayne@gmail.com', '$2y$10$Kw5vuvW7APwKe4SiiYF9jONz6KUUYW9hGIFHDeW9PCeOYA/JmDpuW', 0, 'brucewayne');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `book_id`, `added_at`) VALUES
(67, 5, NULL, '2024-06-17 08:24:48'),
(68, 5, NULL, '2024-06-17 08:24:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_author` (`author_id`);

--
-- Indexes for table `book_genres`
--
ALTER TABLE `book_genres`
  ADD PRIMARY KEY (`book_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `payment_options`
--
ALTER TABLE `payment_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `purchased_books`
--
ALTER TABLE `purchased_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_cards`
--
ALTER TABLE `user_cards`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_options`
--
ALTER TABLE `payment_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchased_books`
--
ALTER TABLE `purchased_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_cards`
--
ALTER TABLE `user_cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`);

--
-- Constraints for table `book_genres`
--
ALTER TABLE `book_genres`
  ADD CONSTRAINT `book_genres_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_options`
--
ALTER TABLE `payment_options`
  ADD CONSTRAINT `payment_options_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `purchased_books`
--
ALTER TABLE `purchased_books`
  ADD CONSTRAINT `purchased_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchased_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `user_cards`
--
ALTER TABLE `user_cards`
  ADD CONSTRAINT `user_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
