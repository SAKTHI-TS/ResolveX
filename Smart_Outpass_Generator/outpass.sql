-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 06:09 AM
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
-- Database: `outpass`
--

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `faculty_name` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `mailid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `user_id`, `faculty_name`, `dept`, `year`, `phoneno`, `mailid`) VALUES
(1, 112233, 'raja', 'CSBS', 'III', '7708482134', 'madhan2gmail.com'),
(2, 115566, 'Venky', 'CSE', 'III', '75556632546', 'venky@gamil.com');

-- --------------------------------------------------------

--
-- Table structure for table `gate`
--

CREATE TABLE `gate` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `security_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hod`
--

CREATE TABLE `hod` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hod_name` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `mailid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hod`
--

INSERT INTO `hod` (`id`, `user_id`, `hod_name`, `dept`, `phoneno`, `mailid`) VALUES
(1, 77777, 'seenivasan', 'CSBS', '7755669955', 'csbs@gmail.com'),
(2, 88888, 'Raja', 'CSE', '7996655447', 'Raja02@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `leaveapply`
--

CREATE TABLE `leaveapply` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `Year` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user_id`, `password`, `role`) VALUES
(1, '22bcb028', '12345', 'Students'),
(2, '22bcs087', '12345', 'Students'),
(3, '112233', '123456', 'Fac'),
(4, '115566', '123456', 'Fac'),
(5, '77777', '123456', 'HOD'),
(6, '88888', '123456', 'HOD');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `mentorname` varchar(255) NOT NULL,
  `mphoneno` varchar(10) NOT NULL,
  `images` varchar(255) NOT NULL,
  `Dob` date NOT NULL,
  `Year` varchar(255) NOT NULL,
  `Dept` varchar(255) NOT NULL,
  `Fname` varchar(255) NOT NULL,
  `smobileno` varchar(10) NOT NULL,
  `fmobileno` varchar(10) NOT NULL,
  `place` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `Name`, `user_id`, `mentorname`, `mphoneno`, `images`, `Dob`, `Year`, `Dept`, `Fname`, `smobileno`, `fmobileno`, `place`) VALUES
(1, 'Madhan M', '22bcb028', 'Venkateshwaran', '7826852656', '1111.jpg', '2004-11-07', 'III', 'CSBS', 'Mohan raj', '7826852656', '7708482134', 'Karur'),
(2, 'Sakthi T S', '22bcs087', 'Karthik K', '8778475247', '2222.jpg', '2005-10-10', 'III', 'CSE', 'YYY', '8778952656', '9952572566', 'Karur');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gate`
--
ALTER TABLE `gate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hod`
--
ALTER TABLE `hod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaveapply`
--
ALTER TABLE `leaveapply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gate`
--
ALTER TABLE `gate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hod`
--
ALTER TABLE `hod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leaveapply`
--
ALTER TABLE `leaveapply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
