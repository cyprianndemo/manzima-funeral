-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2022 at 12:24 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manzima_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bodies`
--

CREATE TABLE `bodies` (
  `body_id` int(11) NOT NULL,
  `place_found` int(11) NOT NULL,
  `cause` int(11) NOT NULL,
  `hospital` varchar(255) NOT NULL,
  `gender` int(255) NOT NULL DEFAULT 3,
  `age_range` int(11) NOT NULL,
  `desc_text` text DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `isClaimed` tinyint(1) NOT NULL DEFAULT 0,
  `isIdentified` tinyint(1) NOT NULL DEFAULT 0,
  `isRequested` tinyint(1) NOT NULL DEFAULT 0,
  `isPicked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bodies`
--

INSERT INTO `bodies` (`body_id`, `place_found`, `cause`, `hospital`, `gender`, `age_range`, `desc_text`, `created_on`, `created_by`, `isClaimed`, `isIdentified`, `isRequested`, `isPicked`) VALUES
(1, 1, 1, 'Nairobi womens UPDATE', 1, 1, 'Scar behing the ear', '2022-03-16 15:36:59', 1, 1, 0, 0, 0),
(2, 10, 2, 'Nairobi womens', 2, 4, 'Scar on face', '2022-03-16 15:36:59', 1, 0, 0, 0, 0),
(3, 1, 2, 'Nairobi womens', 2, 4, 'Red shirt blue jeans\r\nMole on nose', '2022-03-20 12:38:20', 1, 0, 0, 0, 0),
(4, 3, 1, 'Mama Lucy', 3, 4, 'Blue T-shirt black jeans\r\nBlack trenchcoat\r\nBlack spot on near eye', '2022-03-20 12:38:20', 1, 1, 0, 0, 0),
(5, 8, 4, 'N/A', 1, 6, 'black shirt black trouser\r\nScar on right leg\r\nChrist tatoo on the left leg\r\n\r\n', '2022-03-20 12:38:20', 1, 0, 0, 0, 0),
(6, 4, 3, 'Nairobi Womens\'', 3, 5, 'black trouser white scarf\r\n5,11\r\nScar on lef tleg\r\ntatoo on the left leg', '2022-03-20 12:38:20', 1, 0, 0, 0, 0),
(7, 9, 1, 'Kenyatta National Hospital', 1, 6, 'black shirt black trouser\r\nScar on right leg\r\nChrist tatoo on the left leg', '2022-03-20 12:38:20', 1, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bodies`
--
ALTER TABLE `bodies`
  ADD PRIMARY KEY (`body_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `place_found` (`place_found`,`gender`,`age_range`),
  ADD KEY `cause` (`cause`),
  ADD KEY `gender` (`gender`),
  ADD KEY `age_range` (`age_range`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bodies`
--
ALTER TABLE `bodies`
  MODIFY `body_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bodies`
--
ALTER TABLE `bodies`
  ADD CONSTRAINT `bodies_ibfk_1` FOREIGN KEY (`gender`) REFERENCES `gender` (`gender_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bodies_ibfk_2` FOREIGN KEY (`place_found`) REFERENCES `location` (`location_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bodies_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `attendants` (`att_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bodies_ibfk_4` FOREIGN KEY (`cause`) REFERENCES `causes` (`cause_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bodies_ibfk_5` FOREIGN KEY (`age_range`) REFERENCES `age_range` (`range_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
