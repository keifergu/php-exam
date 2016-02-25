-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-08-27 11:50:33
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thinkphp`
--

-- --------------------------------------------------------

--
-- 表的结构 `data_dict`
--

CREATE TABLE IF NOT EXISTS `data_dict` (
  `type_id` int(6) NOT NULL COMMENT '类型ID',
  `belong_type` int(6) NOT NULL COMMENT 'ID所属的类型',
  `type_name` varchar(16) NOT NULL COMMENT '类型名称',
  `status` tinyint(1) NOT NULL COMMENT '课程或题目类型的可用状态',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `lv_2_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `data_dict`
--

INSERT INTO `data_dict` (`type_id`, `belong_type`, `type_name`, `status`) VALUES
(101, 100, '单项选择题', 1),
(102, 100, '多项选择题', 1),
(103, 100, '判断题', 1),
(104, 100, '填空题', 0),
(105, 100, '问答题', 0),
(201, 200, '高等数学', 1),
(202, 200, '大学物理', 1);

-- --------------------------------------------------------

--
-- 表的结构 `question_choice`
--

CREATE TABLE IF NOT EXISTS `question_choice` (
  `id` int(12) unsigned NOT NULL COMMENT '问题ID',
  `course` int(4) unsigned NOT NULL COMMENT '问题所属课程',
  `type` int(4) unsigned NOT NULL COMMENT '问题类型',
  `keyword` varchar(8) DEFAULT NULL COMMENT '关键字',
  `title` varchar(6540) DEFAULT NULL COMMENT '题目',
  `option` varchar(256) DEFAULT NULL COMMENT '选项，不同选项使用半角分号隔开',
  `answer` varchar(8) DEFAULT NULL COMMENT '答案',
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `question_choice`
--

INSERT INTO `question_choice` (`id`, `course`, `type`, `keyword`, `title`, `option`, `answer`) VALUES
(2011010001, 201, 101, NULL, '近几年，汽车市场环境及消费意识形态的变化不包括', '燃油价格居高不下;环境污染日益严重;追求高价格高油耗的高端车型;追求长轴距及宽敞的后排乘坐空间', '3'),
(2011010002, 201, 101, NULL, '飒飒大苏打盛大', '是;不是;', '1');

-- --------------------------------------------------------

--
-- 表的结构 `student_auth`
--

CREATE TABLE IF NOT EXISTS `student_auth` (
  `student_id` varchar(32) NOT NULL COMMENT '验证登陆唯一标示符',
  `student_name` varchar(16) NOT NULL COMMENT '用户名',
  `student_password` varchar(32) NOT NULL COMMENT '密码',
  `login_status` tinyint(1) NOT NULL COMMENT '登陆状态',
  `student_auth` int(4) NOT NULL DEFAULT '0' COMMENT '权限',
  `reg_time` datetime NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `studentid` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生登陆信息表';

--
-- 转存表中的数据 `student_auth`
--

INSERT INTO `student_auth` (`student_id`, `student_name`, `student_password`, `login_status`, `student_auth`, `reg_time`) VALUES
('12313', '谷昆峰', '950820', 0, 0, '0000-00-00 00:00:00'),
('5120146169', '扎西德勒.西格玛.墨尔本晴', 'iamintheworddoyouknow', 0, 0, '2015-08-25 03:38:22');

-- --------------------------------------------------------

--
-- 表的结构 `student_info`
--

CREATE TABLE IF NOT EXISTS `student_info` (
  `student_id` varchar(32) NOT NULL COMMENT '学生登陆ID',
  `student_name` varchar(16) NOT NULL,
  `student_course` varchar(32) NOT NULL COMMENT '学生在进行的课程',
  `belong_teacher` varchar(32) NOT NULL COMMENT '学生的老师',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `student_info`
--

INSERT INTO `student_info` (`student_id`, `student_name`, `student_course`, `belong_teacher`) VALUES
('12313', '谷昆峰', '201;', '112;');

-- --------------------------------------------------------

--
-- 表的结构 `teacher_auth`
--

CREATE TABLE IF NOT EXISTS `teacher_auth` (
  `teacher_id` varchar(32) NOT NULL COMMENT '教师登陆ID',
  `teacher_name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL COMMENT '登陆密码',
  `login_status` tinyint(1) NOT NULL COMMENT '登录状态',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师登陆信息表';

--
-- 转存表中的数据 `teacher_auth`
--

INSERT INTO `teacher_auth` (`teacher_id`, `teacher_name`, `password`, `login_status`) VALUES
('112', 'Bob', '123', 0);

-- --------------------------------------------------------

--
-- 表的结构 `teacher_info`
--

CREATE TABLE IF NOT EXISTS `teacher_info` (
  `teacher_id` int(16) unsigned NOT NULL AUTO_INCREMENT COMMENT '教师ID',
  `teacher_name` varchar(16) NOT NULL COMMENT '教师姓名',
  `course_id` int(8) NOT NULL COMMENT '所属课程',
  `student_num` int(8) NOT NULL COMMENT '学生数量',
  `add_test` varchar(32) NOT NULL COMMENT '该教师所增加的试卷',
  `using_test` varchar(32) NOT NULL COMMENT '处于使用状态的试卷',
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=114 ;

--
-- 转存表中的数据 `teacher_info`
--

INSERT INTO `teacher_info` (`teacher_id`, `teacher_name`, `course_id`, `student_num`, `add_test`, `using_test`) VALUES
(112, 'Bob', 201, 4, '201001', '201001'),
(113, 'jk', 202, 5, '102', '201002');

-- --------------------------------------------------------

--
-- 表的结构 `testpaper_content`
--

CREATE TABLE IF NOT EXISTS `testpaper_content` (
  `test_id` int(8) NOT NULL COMMENT '试卷唯一ID',
  `test_name` varchar(64) NOT NULL COMMENT '试卷名称',
  `course_id` int(4) NOT NULL COMMENT '所属课程ID',
  `course_name` varchar(32) NOT NULL,
  `total_grade` int(4) NOT NULL COMMENT '试卷总分',
  `question_num` int(4) NOT NULL COMMENT '试卷题目数量',
  `time` time NOT NULL COMMENT '该试卷的考试时间',
  `deadline` datetime NOT NULL COMMENT '提交截止日期',
  `content` text NOT NULL COMMENT '试卷',
  `answer` varchar(128) NOT NULL COMMENT '试卷答案',
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `testpaper_content`
--

INSERT INTO `testpaper_content` (`test_id`, `test_name`, `course_id`, `course_name`, `total_grade`, `question_num`, `time`, `deadline`, `content`, `answer`) VALUES
(201001, '西南科技大学2014级高等数学B期末考试试卷（一）', 201, '高等数学', 100, 20, '01:30:00', '0000-00-00 00:00:00', '2011010001,2011010002', ''),
(201002, '测试试卷', 201, '测试', 100, 20, '00:00:00', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `testpaper_grade`
--

CREATE TABLE IF NOT EXISTS `testpaper_grade` (
  `test_type_id` int(16) NOT NULL COMMENT '所属试卷以及问题类型ID',
  `question_type` int(4) NOT NULL COMMENT '所属问题类型ID',
  `grade` int(4) NOT NULL COMMENT '该类问题分数',
  PRIMARY KEY (`test_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `testpaper_grade`
--

INSERT INTO `testpaper_grade` (`test_type_id`, `question_type`, `grade`) VALUES
(201001101, 101, 4),
(201001102, 102, 2);

-- --------------------------------------------------------

--
-- 表的结构 `test_save`
--

CREATE TABLE IF NOT EXISTS `test_save` (
  `save_id` int(32) NOT NULL COMMENT '储存ID',
  `testpaper_id` int(8) NOT NULL COMMENT '所完成试卷ID',
  `testpaper_name` varchar(32) NOT NULL COMMENT '试卷名称',
  `student_id` varchar(32) NOT NULL COMMENT '学生ID',
  `course_id` int(4) NOT NULL COMMENT '课程ID',
  `total_grade` int(11) NOT NULL COMMENT '试卷总分',
  `grade` int(16) NOT NULL COMMENT '得分',
  `question_num` int(12) NOT NULL COMMENT '总共题目的数量',
  `question_correct` varchar(32) NOT NULL COMMENT '正确的题目',
  `question_wrong` varchar(32) NOT NULL COMMENT '错误的题目',
  `answer` varchar(32) NOT NULL COMMENT '学生的答案',
  `submit_time` date NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`save_id`),
  UNIQUE KEY `save_id` (`save_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `test_submit`
--

CREATE TABLE IF NOT EXISTS `test_submit` (
  `submit_id` varchar(64) NOT NULL COMMENT '每一次提交为一个ID',
  `testpaper_id` varchar(32) NOT NULL COMMENT '所完成的试卷ID',
  `course_id` varchar(16) NOT NULL COMMENT '所属课程的ID',
  `student_id` varchar(32) NOT NULL COMMENT '提交该测试的学生ID',
  `question_id` varchar(16) NOT NULL COMMENT '题目ID',
  `answer` varchar(8) NOT NULL COMMENT '提交的答案',
  PRIMARY KEY (`submit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `test_submit`
--

INSERT INTO `test_submit` (`submit_id`, `testpaper_id`, `course_id`, `student_id`, `question_id`, `answer`) VALUES
('201001123132011010001', '201001', '201', '12313', '2011010001', '2'),
('201001123132011010002', '201001', '201', '12313', '2011010002', '2');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
