SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `invited_to_work_on` (
  `UserId` int(11) NOT NULL,
  `ProjectId` int(11) NOT NULL,
  `Accepted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `project` (
  `ProjectId` int(11) NOT NULL,
  `ProjectName` varchar(31) NOT NULL,
  `ProjectManager` int(11) NOT NULL,
  `ProjectDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sharedtoview` (
  `ExpirationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `AccessToken` varchar(64) DEFAULT NULL,
  `ProjectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `BreakReminder` int(11) NOT NULL DEFAULT '120'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `workingtime` (
  `ProjectId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `StartTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EndTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `invited_to_work_on`
  ADD PRIMARY KEY (`UserId`,`ProjectId`),
  ADD KEY `Project` (`ProjectId`);

ALTER TABLE `project`
  ADD PRIMARY KEY (`ProjectId`),
  ADD KEY `ProjectManager` (`ProjectManager`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`),
  ADD UNIQUE KEY `email` (`EmailAddress`);

ALTER TABLE `workingtime`
  ADD PRIMARY KEY (`ProjectId`,`UserId`,`StartTime`),
  ADD KEY `UserID` (`UserId`);


ALTER TABLE `project`
  MODIFY `ProjectId` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `invited_to_work_on`
  ADD CONSTRAINT `Project` FOREIGN KEY (`ProjectId`) REFERENCES `project` (`ProjectId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `User` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `project`
  ADD CONSTRAINT `ProjectManager` FOREIGN KEY (`ProjectManager`) REFERENCES `user` (`UserId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `workingtime`
  ADD CONSTRAINT `workingtime_ibfk_1` FOREIGN KEY (`ProjectId`) REFERENCES `project` (`ProjectId`),
  ADD CONSTRAINT `workingtime_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
