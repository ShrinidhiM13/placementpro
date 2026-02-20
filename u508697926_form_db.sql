-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 20, 2026 at 11:23 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u508697926_form_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Alumni`
--

CREATE TABLE `Alumni` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `companyId` bigint(20) NOT NULL,
  `jobTitle` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Application`
--

CREATE TABLE `Application` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `driveId` bigint(20) NOT NULL,
  `status` enum('APPLIED','APTITUDE','CLEARED','INTERVIEW_SCHEDULED','SELECTED','REJECTED') NOT NULL DEFAULT 'APPLIED',
  `appliedAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Branch`
--

CREATE TABLE `Branch` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Certification`
--

CREATE TABLE `Certification` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `issuer` varchar(191) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Company`
--

CREATE TABLE `Company` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Drive`
--

CREATE TABLE `Drive` (
  `id` bigint(20) NOT NULL,
  `companyId` bigint(20) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `minCgpa` double NOT NULL,
  `maxBacklogs` int(11) NOT NULL,
  `status` enum('OPEN','CLOSED','COMPLETED') NOT NULL DEFAULT 'OPEN',
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DriveBranch`
--

CREATE TABLE `DriveBranch` (
  `driveId` bigint(20) NOT NULL,
  `branchId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Education`
--

CREATE TABLE `Education` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `degree` varchar(191) NOT NULL,
  `institute` varchar(191) NOT NULL,
  `year` int(11) NOT NULL,
  `marks` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ImportLog`
--

CREATE TABLE `ImportLog` (
  `id` bigint(20) NOT NULL,
  `fileName` varchar(191) NOT NULL,
  `totalRows` int(11) NOT NULL,
  `inserted` int(11) NOT NULL,
  `failed` int(11) NOT NULL,
  `importType` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `InterviewSlot`
--

CREATE TABLE `InterviewSlot` (
  `id` bigint(20) NOT NULL,
  `driveId` bigint(20) NOT NULL,
  `startTime` datetime(3) NOT NULL,
  `endTime` datetime(3) NOT NULL,
  `room` varchar(191) NOT NULL,
  `isBooked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `JobPost`
--

CREATE TABLE `JobPost` (
  `id` bigint(20) NOT NULL,
  `alumniId` bigint(20) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `JobRole`
--

CREATE TABLE `JobRole` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `JobRoleSkill`
--

CREATE TABLE `JobRoleSkill` (
  `jobRoleId` bigint(20) NOT NULL,
  `skillId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `MentorBooking`
--

CREATE TABLE `MentorBooking` (
  `id` bigint(20) NOT NULL,
  `slotId` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `MentorSlot`
--

CREATE TABLE `MentorSlot` (
  `id` bigint(20) NOT NULL,
  `alumniId` bigint(20) NOT NULL,
  `startTime` datetime(3) NOT NULL,
  `endTime` datetime(3) NOT NULL,
  `isBooked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Notification`
--

CREATE TABLE `Notification` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `title` varchar(191) NOT NULL,
  `message` varchar(191) NOT NULL,
  `isRead` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PlacementRecord`
--

CREATE TABLE `PlacementRecord` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `companyId` bigint(20) NOT NULL,
  `jobRoleId` bigint(20) NOT NULL,
  `package` double NOT NULL,
  `placedAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

CREATE TABLE `Project` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `techStack` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Session`
--

CREATE TABLE `Session` (
  `id` bigint(20) NOT NULL,
  `token` varchar(191) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `expiresAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Session`
--

INSERT INTO `Session` (`id`, `token`, `userId`, `expiresAt`) VALUES
(1, 'af263303-b8d2-41ce-ab33-3912d2a38144', 1, '2026-02-27 10:22:23.650');

-- --------------------------------------------------------

--
-- Table structure for table `Skill`
--

CREATE TABLE `Skill` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `branchId` bigint(20) NOT NULL,
  `registerNumber` varchar(191) NOT NULL,
  `cgpa` double NOT NULL,
  `backlogCount` int(11) NOT NULL DEFAULT 0,
  `graduationYear` int(11) NOT NULL,
  `placementStatus` enum('UNPLACED','PLACED') NOT NULL DEFAULT 'UNPLACED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `StudentInterview`
--

CREATE TABLE `StudentInterview` (
  `id` bigint(20) NOT NULL,
  `studentId` bigint(20) NOT NULL,
  `driveId` bigint(20) NOT NULL,
  `slotId` bigint(20) NOT NULL,
  `status` enum('SCHEDULED','COMPLETED','CANCELLED') NOT NULL DEFAULT 'SCHEDULED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `StudentSkill`
--

CREATE TABLE `StudentSkill` (
  `studentId` bigint(20) NOT NULL,
  `skillId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `passwordHash` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `role` enum('STUDENT','TPO','ALUMNI') NOT NULL,
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `name`, `email`, `createdAt`, `isActive`, `passwordHash`, `phone`, `role`, `updatedAt`) VALUES
(1, 'Admin', 'admin@gmail.com', '2026-02-20 10:20:56.000', 1, '$2a$12$YA0fYP3lNeXdNG/XIg3UZ.zI5NAcQFNscS7io2I2uJsd9voXmfgBW', '9876543210', 'TPO', '2026-02-20 10:20:56.000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Alumni`
--
ALTER TABLE `Alumni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Alumni_userId_key` (`userId`),
  ADD KEY `Alumni_companyId_fkey` (`companyId`);

--
-- Indexes for table `Application`
--
ALTER TABLE `Application`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Application_studentId_driveId_key` (`studentId`,`driveId`),
  ADD KEY `Application_driveId_fkey` (`driveId`);

--
-- Indexes for table `Branch`
--
ALTER TABLE `Branch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Branch_name_key` (`name`);

--
-- Indexes for table `Certification`
--
ALTER TABLE `Certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Certification_studentId_fkey` (`studentId`);

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Company_name_key` (`name`);

--
-- Indexes for table `Drive`
--
ALTER TABLE `Drive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Drive_companyId_fkey` (`companyId`);

--
-- Indexes for table `DriveBranch`
--
ALTER TABLE `DriveBranch`
  ADD PRIMARY KEY (`driveId`,`branchId`),
  ADD KEY `DriveBranch_branchId_fkey` (`branchId`);

--
-- Indexes for table `Education`
--
ALTER TABLE `Education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Education_studentId_fkey` (`studentId`);

--
-- Indexes for table `ImportLog`
--
ALTER TABLE `ImportLog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `InterviewSlot`
--
ALTER TABLE `InterviewSlot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `InterviewSlot_driveId_fkey` (`driveId`);

--
-- Indexes for table `JobPost`
--
ALTER TABLE `JobPost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `JobPost_alumniId_fkey` (`alumniId`);

--
-- Indexes for table `JobRole`
--
ALTER TABLE `JobRole`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `JobRole_name_key` (`name`);

--
-- Indexes for table `JobRoleSkill`
--
ALTER TABLE `JobRoleSkill`
  ADD PRIMARY KEY (`jobRoleId`,`skillId`),
  ADD KEY `JobRoleSkill_skillId_fkey` (`skillId`);

--
-- Indexes for table `MentorBooking`
--
ALTER TABLE `MentorBooking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MentorBooking_slotId_fkey` (`slotId`),
  ADD KEY `MentorBooking_studentId_fkey` (`studentId`);

--
-- Indexes for table `MentorSlot`
--
ALTER TABLE `MentorSlot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MentorSlot_alumniId_fkey` (`alumniId`);

--
-- Indexes for table `Notification`
--
ALTER TABLE `Notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Notification_userId_fkey` (`userId`);

--
-- Indexes for table `PlacementRecord`
--
ALTER TABLE `PlacementRecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PlacementRecord_studentId_fkey` (`studentId`),
  ADD KEY `PlacementRecord_companyId_fkey` (`companyId`),
  ADD KEY `PlacementRecord_jobRoleId_fkey` (`jobRoleId`);

--
-- Indexes for table `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Project_studentId_fkey` (`studentId`);

--
-- Indexes for table `Session`
--
ALTER TABLE `Session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Session_token_key` (`token`),
  ADD KEY `Session_userId_fkey` (`userId`);

--
-- Indexes for table `Skill`
--
ALTER TABLE `Skill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Skill_name_key` (`name`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Student_userId_key` (`userId`),
  ADD UNIQUE KEY `Student_registerNumber_key` (`registerNumber`),
  ADD KEY `Student_branchId_fkey` (`branchId`);

--
-- Indexes for table `StudentInterview`
--
ALTER TABLE `StudentInterview`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `StudentInterview_studentId_driveId_key` (`studentId`,`driveId`),
  ADD KEY `StudentInterview_driveId_fkey` (`driveId`),
  ADD KEY `StudentInterview_slotId_fkey` (`slotId`);

--
-- Indexes for table `StudentSkill`
--
ALTER TABLE `StudentSkill`
  ADD PRIMARY KEY (`studentId`,`skillId`),
  ADD KEY `StudentSkill_skillId_fkey` (`skillId`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `User_email_key` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Alumni`
--
ALTER TABLE `Alumni`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Application`
--
ALTER TABLE `Application`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Branch`
--
ALTER TABLE `Branch`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Certification`
--
ALTER TABLE `Certification`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Company`
--
ALTER TABLE `Company`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Drive`
--
ALTER TABLE `Drive`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Education`
--
ALTER TABLE `Education`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ImportLog`
--
ALTER TABLE `ImportLog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `InterviewSlot`
--
ALTER TABLE `InterviewSlot`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `JobPost`
--
ALTER TABLE `JobPost`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `JobRole`
--
ALTER TABLE `JobRole`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `MentorBooking`
--
ALTER TABLE `MentorBooking`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `MentorSlot`
--
ALTER TABLE `MentorSlot`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PlacementRecord`
--
ALTER TABLE `PlacementRecord`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Project`
--
ALTER TABLE `Project`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Session`
--
ALTER TABLE `Session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Skill`
--
ALTER TABLE `Skill`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Student`
--
ALTER TABLE `Student`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `StudentInterview`
--
ALTER TABLE `StudentInterview`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Alumni`
--
ALTER TABLE `Alumni`
  ADD CONSTRAINT `Alumni_companyId_fkey` FOREIGN KEY (`companyId`) REFERENCES `Company` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Alumni_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `Application`
--
ALTER TABLE `Application`
  ADD CONSTRAINT `Application_driveId_fkey` FOREIGN KEY (`driveId`) REFERENCES `Drive` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Application_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `Certification`
--
ALTER TABLE `Certification`
  ADD CONSTRAINT `Certification_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Drive`
--
ALTER TABLE `Drive`
  ADD CONSTRAINT `Drive_companyId_fkey` FOREIGN KEY (`companyId`) REFERENCES `Company` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `DriveBranch`
--
ALTER TABLE `DriveBranch`
  ADD CONSTRAINT `DriveBranch_branchId_fkey` FOREIGN KEY (`branchId`) REFERENCES `Branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `DriveBranch_driveId_fkey` FOREIGN KEY (`driveId`) REFERENCES `Drive` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Education`
--
ALTER TABLE `Education`
  ADD CONSTRAINT `Education_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `InterviewSlot`
--
ALTER TABLE `InterviewSlot`
  ADD CONSTRAINT `InterviewSlot_driveId_fkey` FOREIGN KEY (`driveId`) REFERENCES `Drive` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `JobPost`
--
ALTER TABLE `JobPost`
  ADD CONSTRAINT `JobPost_alumniId_fkey` FOREIGN KEY (`alumniId`) REFERENCES `Alumni` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `JobRoleSkill`
--
ALTER TABLE `JobRoleSkill`
  ADD CONSTRAINT `JobRoleSkill_jobRoleId_fkey` FOREIGN KEY (`jobRoleId`) REFERENCES `JobRole` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `JobRoleSkill_skillId_fkey` FOREIGN KEY (`skillId`) REFERENCES `Skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `MentorBooking`
--
ALTER TABLE `MentorBooking`
  ADD CONSTRAINT `MentorBooking_slotId_fkey` FOREIGN KEY (`slotId`) REFERENCES `MentorSlot` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `MentorBooking_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `MentorSlot`
--
ALTER TABLE `MentorSlot`
  ADD CONSTRAINT `MentorSlot_alumniId_fkey` FOREIGN KEY (`alumniId`) REFERENCES `Alumni` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `Notification`
--
ALTER TABLE `Notification`
  ADD CONSTRAINT `Notification_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `PlacementRecord`
--
ALTER TABLE `PlacementRecord`
  ADD CONSTRAINT `PlacementRecord_companyId_fkey` FOREIGN KEY (`companyId`) REFERENCES `Company` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PlacementRecord_jobRoleId_fkey` FOREIGN KEY (`jobRoleId`) REFERENCES `JobRole` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PlacementRecord_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `Project`
--
ALTER TABLE `Project`
  ADD CONSTRAINT `Project_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Session`
--
ALTER TABLE `Session`
  ADD CONSTRAINT `Session_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `Student_branchId_fkey` FOREIGN KEY (`branchId`) REFERENCES `Branch` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Student_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `User` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `StudentInterview`
--
ALTER TABLE `StudentInterview`
  ADD CONSTRAINT `StudentInterview_driveId_fkey` FOREIGN KEY (`driveId`) REFERENCES `Drive` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `StudentInterview_slotId_fkey` FOREIGN KEY (`slotId`) REFERENCES `InterviewSlot` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `StudentInterview_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `StudentSkill`
--
ALTER TABLE `StudentSkill`
  ADD CONSTRAINT `StudentSkill_skillId_fkey` FOREIGN KEY (`skillId`) REFERENCES `Skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `StudentSkill_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
