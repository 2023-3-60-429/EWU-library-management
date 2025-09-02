-- EWU Project SQL Dump
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ewu_admin` (
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `ewu_admin` (`email`, `password`, `role`) VALUES
('head@gmail.com', 'head', 'head'),
('teacher1@gmail.com', 'teacher1', 'admin'),
('teacher2@gmail.com', 'teacher2', 'admin'),
('teacher3@gmail.com', 'teacher3', 'admin');

-- Table structure for table `ewu_answer`
CREATE TABLE `ewu_answer` (
  `qid` VARCHAR(50) NOT NULL,
  `ansid` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ewu_answer` (`qid`, `ansid`) VALUES
('55892169bf6a7', '55892169d2efc'),
('5589216a3646e', '5589216a48722'),
('558922117fcef', '5589221195248'),
('55892211e44d5', '55892211f1fa7'),
('558922894c453', '558922895ea0a'),
('558922899ccaa', '55892289aa7cf'),
('558923538f48d', '558923539a46c'),
('55892353f05c4', '55892354051be'),
('558973f4389ac', '558973f462e61'),
('558973f4c46f2', '558973f4d4abe'),
('558973f51600d', '558973f526fc5'),
('558973f55d269', '558973f57af07'),
('558973f5abb1a', '558973f5e764a'),
('5589751a63091', '5589751a81bf4'),
('5589751ad32b8', '5589751adbdbd'),
('5589751b304ef', '5589751b3b04d'),
('5589751b749c9', '5589751b9a98c'),
('5bd1a29b0514c', '5bd1a29b1c417'),
('5bd1a29b7d4b8', '5bd1a29b8ae6e');

-- Table structure for table `ewu_feedback`
CREATE TABLE `ewu_feedback` (
  `id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `subject` VARCHAR(500) NOT NULL,
  `feedback` VARCHAR(500) NOT NULL,
  `date` DATE NOT NULL,
  `time` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `ewu_history`
CREATE TABLE `ewu_history` (
  `email` VARCHAR(50) NOT NULL,
  `eid` VARCHAR(50) NOT NULL,
  `score` INT NOT NULL,
  `level` INT NOT NULL,
  `sahi` INT NOT NULL,
  `wrong` INT NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `ewu_options`
CREATE TABLE `ewu_options` (
  `qid` VARCHAR(50) NOT NULL,
  `option` VARCHAR(5000) NOT NULL,
  `optionid` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ewu_options` (`qid`, `option`, `optionid`) VALUES
('55892169bf6a7', 'usermod', '55892169d2efc'),
('55892169bf6a7', 'useradd', '55892169d2f05'),
('55892169bf6a7', 'useralter', '55892169d2f09'),
('55892169bf6a7', 'groupmod', '55892169d2f0c'),
('5589216a3646e', '751', '5589216a48713'),
('5589216a3646e', '752', '5589216a4871a'),
('5589216a3646e', '754', '5589216a4871f'),
('5589216a3646e', '755', '5589216a48722'),
('558922117fcef', 'echo', '5589221195248'),
('558922117fcef', 'print', '558922119525a'),
('558922117fcef', 'printf', '5589221195265'),
('558922117fcef', 'cout', '5589221195270'),
('55892211e44d5', 'int a', '55892211f1f97'),
('55892211e44d5', '$a', '55892211f1fa7'),
('55892211e44d5', 'long int a', '55892211f1fb4'),
('55892211e44d5', 'int a$', '55892211f1fbd');

-- Table structure for table `ewu_questions`
CREATE TABLE `ewu_questions` (
  `eid` VARCHAR(50) NOT NULL,
  `qid` VARCHAR(50) NOT NULL,
  `qns` TEXT NOT NULL,
  `choice` INT NOT NULL,
  `sn` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ewu_questions` (`eid`, `qid`, `qns`, `choice`, `sn`) VALUES
('558920ff906b8', '55892169bf6a7', 'what is command for changing user information??', 4, 1),
('558920ff906b8', '5589216a3646e', 'what is permission for view only for other??', 4, 2),
('558921841f1ec', '558922117fcef', 'what is command for print in php??', 4, 1),
('558921841f1ec', '55892211e44d5', 'which is a variable of php??', 4, 2);

-- Table structure for table `ewu_quiz`
CREATE TABLE `ewu_quiz` (
  `eid` VARCHAR(50) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `sahi` INT NOT NULL,
  `wrong` INT NOT NULL,
  `total` INT NOT NULL,
  `time` BIGINT NOT NULL,
  `intro` TEXT NOT NULL,
  `tag` VARCHAR(100) NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ewu_quiz` (`eid`, `title`, `sahi`, `wrong`, `total`, `time`, `intro`, `tag`, `date`, `email`) VALUES
('558920ff906b8', 'Linux : File Managment', 2, 1, 2, 5, '', 'linux', '2018-10-20 14:47:56', 'teacher2@gmail.com'),
('558921841f1ec', 'Php Coding', 2, 1, 2, 5, '', 'PHP', '2018-10-20 14:47:04', 'teacher1@gmail.com');

-- Table structure for table `ewu_rank`
CREATE TABLE `ewu_rank` (
  `email` VARCHAR(50) NOT NULL,
  `score` INT NOT NULL,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `ewu_user`
CREATE TABLE `ewu_user` (
  `name` VARCHAR(50) NOT NULL,
  `gender` VARCHAR(5) NOT NULL,
  `college` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `mob` BIGINT NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
