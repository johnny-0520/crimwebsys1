-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2024 at 12:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `criminology_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_name`, `author`) VALUES
(3, 'Theories of Crime', 'Victor E. Kappeler'),
(4, 'Criminology: A Sociological Understanding', 'Steven E. Barkan'),
(5, 'Criminal Law and Procedure', 'Daniel E. Hall'),
(6, 'Policing Today', 'Frank J. Schmalleger'),
(7, 'Forensic Science: From the Crime Scene to the Crime Lab', 'Richard Saferstein'),
(8, 'Serial Killers: The Method and Madness of Monsters', 'Peter Vronsky'),
(9, 'The Anatomy of Motive: The FBI\'s Legendary Mindhunter Explores the Key to Understanding and Catching Violent Criminals', 'John E. Douglas'),
(10, 'The Crime Book: Big Ideas Simply Explained', 'DK'),
(11, 'Mindhunter: Inside the FBI\'s Elite Serial Crime Unit', 'John E. Douglas, Mark Olshaker'),
(12, 'In Cold Blood', 'Truman Capote'),
(13, 'Philippine Criminology: A Comprehensive Study', 'Maria Dela Cruz'),
(14, 'Justice in the Archipelago: Understanding the Philippine Legal System', 'Antonio Santos'),
(15, 'Crime and Punishment in the Pearl of the Orient', 'Isabel Reyes'),
(16, 'Manila Murders: Dark Secrets of the City', 'Rodrigo Gomez'),
(17, 'Under the Philippine Sun: Crime and Justice Stories', 'Carmen Rivera'),
(18, 'The Hidden Crimes: Investigating Philippine Cold Cases', 'Luis Del Rosario'),
(19, 'Breaking Point: Philippine Drug War Chronicles', 'Juan Carlos Ramirez'),
(20, 'Behind Bars: Philippine Prisons Exposed', 'Elena Fernandez'),
(21, 'Criminal Minds: Insights into Behavioral Analysis', 'David Rossi'),
(22, 'The Art of Criminal Investigation', 'Alex Cross'),
(23, 'Under the Manila Moon: Mysteries of the Philippine Night', 'Isabella Reyes'),
(24, 'Justice Delayed: Legal Challenges in the Philippine Judicial System', 'Manuel Ocampo'),
(25, 'Beyond Bars: Stories of Redemption from Philippine Prisons', 'Lourdes Fernandez'),
(26, 'Blood on the Streets: Crime and Order in Philippine Cities', 'Ramon Gutierrez'),
(27, 'The Crime Solver\'s Handbook', 'Samantha Holmes'),
(28, 'The Dark Side of Justice: Philippine True Crime Stories', 'Carlos Hernandez'),
(29, 'Cold Cases and Hot Pursuits: Philippine Unsolved Mysteries', 'Gabriel Ramos'),
(30, 'The Mind of a Master Criminal', 'Mikhail Ivanov'),
(31, 'The Silent Witness: Philippine Legal Thriller', 'Andres Dela Cruz'),
(32, 'Justice in the Tropics: Crime and Punishment in the Philippines', 'Cristina Salazar'),
(33, 'The Criminal\'s Mind: Insights from a Filipino Detective', 'Antonio Reyes'),
(34, 'Deadly Alliances: Crime Syndicates in the Philippines', 'Lucio Rodriguez'),
(35, 'The Art of Interrogation: Techniques and Controversies', 'Isabel Marquez'),
(36, 'In Pursuit of Justice: Philippine Legal Stories', 'Ramon Fernandez'),
(37, 'Shadows of Corruption: Navigating the Philippine Legal System', 'Elena Santiago'),
(38, 'Beyond the Badge: Stories from Filipino Police Officers', 'Luis Mendoza'),
(39, 'The Art of Forensics: Solving Crimes in the Philippines', 'Sofia Alvaro'),
(40, 'The Criminal\'s Code: Decoding Filipino Criminal Behavior', 'Carlos Cruz');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `return_schedule` datetime NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'BORROWED',
  `Date_Time_Return` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `book_id`, `borrow_date`, `return_schedule`, `Status`, `Date_Time_Return`) VALUES
(66, 40, 7, '2024-01-20 23:17:01', '2024-01-25 23:17:01', 'RETURNED', '2024-01-21 01:08:11'),
(67, 40, 28, '2024-01-21 12:32:47', '2024-01-26 12:32:47', 'BORROWED', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `suffix_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `first_name` varchar(225) NOT NULL,
  `last_name` varchar(225) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `middle_name`, `suffix_name`, `address`, `contact_number`, `year_level`, `section`, `first_name`, `last_name`, `email`, `username`, `role`) VALUES
(40, 'CRUZ', '', 'Sipocot, Camarines Sur', '91234556899', 'js', 'A', 'KENNETH', 'MARQUEZ', '', '', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
