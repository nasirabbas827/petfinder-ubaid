-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 07:40 AM
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
-- Database: `petfinder_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `petID` int(11) NOT NULL,
  `bookingDate` datetime DEFAULT NULL,
  `status` enum('pending','approved','canceled') DEFAULT 'pending',
  `paymentStatus` varchar(20) DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingID`, `userID`, `petID`, `bookingDate`, `status`, `paymentStatus`) VALUES
(2, 3, 1, '2024-06-12 07:03:23', 'approved', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text DEFAULT NULL,
  `sent_datetime` datetime DEFAULT current_timestamp(),
  `reply_text` text DEFAULT NULL,
  `reply_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `petID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `energyLevel` varchar(50) DEFAULT NULL,
  `friendliness` varchar(50) DEFAULT NULL,
  `easeOfTraining` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `imageURL` varchar(255) DEFAULT NULL,
  `vendorInfo` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `nearbyArea` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`petID`, `name`, `age`, `price`, `breed`, `size`, `color`, `energyLevel`, `friendliness`, `easeOfTraining`, `status`, `imageURL`, `vendorInfo`, `category`, `nearbyArea`) VALUES
(1, 'Horse', 23, 5000.00, 'Nothing', 'Large', 'Black', 'eaf', 'yes', 'yes', 'not available for booking', 'uploads/Screenshot 2024-06-02 113639.png', 'Nasir Abbas', 'Plan1', 'Lahore');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `age`) VALUES
(3, 'user1', '$2y$10$T1MgtoFfBSexO3vG4XCuC.4I09.Qjq51UKrN3YgIY4aSNUb.muJ3.', 'nasiryt.827@gmail.com', '3242', 23);

-- --------------------------------------------------------

--
-- Table structure for table `website_info`
--

CREATE TABLE `website_info` (
  `infoID` int(11) NOT NULL,
  `section` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_info`
--

INSERT INTO `website_info` (`infoID`, `section`, `title`, `content`) VALUES
(3, 'About', 'Our Mission', 'Our mission is to provide loving homes for every pet in need. We strive to connect animals with caring individuals and families who are eager to provide them with a forever home.'),
(4, 'About', 'Our Vision', 'Our vision is a world where every animal is valued and cherished, where no pet is left behind or forgotten. We envision a community where adoption is the first choice for those seeking a companion animal.'),
(5, 'About', 'Our Team', 'Meet our dedicated team of animal lovers who work tirelessly to ensure the well-being of every pet in our care. From adoption counselors to veterinary staff, we are committed to making a difference.'),
(6, 'About', 'Contact Us', 'Have questions or want to learn more about our organization? Contact us today to speak with a member of our team. We are here to help you find your perfect pet match and support you throughout the adoption process.'),
(7, 'Adopting Pets', 'Why Adopting is Important', 'Adopting a pet not only saves a life, but it also provides a loving home to an animal in need. By choosing adoption, you are giving a second chance to a deserving pet and making a positive impact on their life.'),
(8, 'Adopting Pets', 'Adoption Process', 'Our adoption process is simple and straightforward. From browsing available pets to completing an adoption application, our team will guide you through each step to ensure a successful match. Start your journey to pet parenthood today!'),
(9, 'Adopting Pets', 'Benefits of Pet Adoption', 'There are countless benefits to pet adoption, including companionship, improved mental and physical health, and the satisfaction of knowing you have made a difference in an animals life. Discover the joys of pet adoption today!'),
(10, 'Adopting Pets', 'Pet Adoption FAQs', 'Got questions about pet adoption? Browse our frequently asked questions for answers to common inquiries about the adoption process, fees, and more. If you do not find what you are looking for, feel free to reach out to our team for assistance.'),
(11, 'Animal Shelters & Rescues', 'Shelter Services', 'Our animal shelter offers a range of services, including pet adoption, spay/neuter clinics, microchipping, and more. We are committed to providing quality care and support to pets in need.'),
(12, 'Animal Shelters & Rescues', 'Volunteer Opportunities', 'Looking to make a difference in the lives of animals? Join our volunteer program and become part of a compassionate community dedicated to helping pets in need. Whether it is walking dogs, socializing cats, or assisting with adoption events, there are plenty of ways to get involved.'),
(13, 'Animal Shelters & Rescues', 'Donation Options', 'Support our shelter and rescue efforts by making a donation today. Your generous contribution helps us provide food, shelter, medical care, and enrichment for animals awaiting their forever homes. Every dollar makes a difference!'),
(14, 'Animal Shelters & Rescues', 'Shelter Locations', 'With multiple shelter locations across the region, finding your new furry friend is easier than ever. Visit us at one of our adoption centers to meet our adoptable pets and learn more about our programs and services.'),
(15, 'Pet-Finder Foundation', 'Our Programs', 'Learn about the programs and initiatives offered by the Pet-Finder Foundation, including spay/neuter initiatives, pet food assistance programs, and community outreach efforts. Together, we can make a difference in the lives of pets and their families.'),
(16, 'Pet-Finder Foundation', 'Donation Drives', 'Support our mission by participating in one of our donation drives. From pet food and supplies to monetary donations, every contribution helps us continue our lifesaving work. Join us in making a positive impact in the lives of pets in need.'),
(17, 'Pet-Finder Foundation', 'Impact Stories', 'Discover heartwarming stories of pets whose lives have been transformed through the efforts of the Pet-Finder Foundation. From rescue and rehabilitation to adoption success stories, these tales showcase the power of love and compassion.'),
(18, 'Pet-Finder Foundation', 'Get Involved', 'Ready to make a difference? Find out how you can get involved with the Pet-Finder Foundation. Whether it is volunteering your time, donating supplies, or spreading the word about our organization, there are plenty of ways to lend your support.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`petID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_info`
--
ALTER TABLE `website_info`
  ADD PRIMARY KEY (`infoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `website_info`
--
ALTER TABLE `website_info`
  MODIFY `infoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
