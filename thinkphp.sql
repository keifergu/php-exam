-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-19 12:01:06
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
-- 表的结构 `app_option`
--

CREATE TABLE IF NOT EXISTS `app_option` (
  `question_id` varchar(16) NOT NULL COMMENT '课程号+问题类型+编号',
  `course_id` varchar(8) NOT NULL COMMENT '问题所属课程',
  `type` varchar(8) NOT NULL COMMENT '问题类型',
  `keyword` varchar(8) DEFAULT NULL COMMENT '关键字',
  `title` varchar(6540) NOT NULL COMMENT '题目',
  `a` varchar(128) NOT NULL,
  `b` varchar(128) NOT NULL,
  `c` varchar(128) DEFAULT NULL,
  `d` varchar(128) DEFAULT NULL,
  `e` varchar(128) DEFAULT NULL,
  `f` varchar(128) DEFAULT NULL,
  `g` varchar(128) DEFAULT NULL,
  `h` varchar(128) DEFAULT NULL,
  `img` varchar(128) DEFAULT NULL,
  `answer` varchar(8) NOT NULL COMMENT '答案',
  PRIMARY KEY (`question_id`),
  UNIQUE KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_option`
--

INSERT INTO `app_option` (`question_id`, `course_id`, `type`, `keyword`, `title`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `h`, `img`, `answer`) VALUES
('2011010001', '201', '101', NULL, '近几年，汽车市场环境及消费意识形态的变化不包括', '燃油价格居高不下', '环境污染日益严重', '追求高价格高油耗的高端车型', '追求长轴距及宽敞的后排乘坐空间', '', '', '', '', '', '3'),
('2011010002', '201', '101', NULL, '计算机网络的基本分类方法主要有两种：一种是根据网络所使用的传输技术；另一种是根据', '网络协议', '网络操作系统类型', '覆盖范围与规模', '网络服务器类型与规模', NULL, NULL, NULL, NULL, NULL, '2'),
('2011010003', '201', '101', NULL, '计算机网络拓扑通过网络中结点与通信线路之间的几何关系来表示', '网络层次', '协议关系', '体系结构', '网络结构', NULL, NULL, NULL, NULL, NULL, '1'),
('2011010004', '201', '101', NULL, '在TCP/IP参考模型中，传输层的主要作用是在互联网络的源主机与目的主机对等实体之间建立用于会话的', '点－点连接', '操作连接', '端－端连接', '控制连接', NULL, NULL, NULL, NULL, NULL, '4'),
('2011020001', '201', '102', NULL, '全新一代IS FSPORT运动版与普通版在车身尺寸上的区别是', '宽度增加5mm', '高度增加5mm', '长度增加5mm', '轴距缩小5毫米', '车身增加5mm', NULL, NULL, NULL, 'title:__PUBLIC__/img_option/2011020001t;', '13'),
('2011030001', '201', '103', NULL, '全新一代Lexus ES300h油电混合动力车型，采用阿特金森循环发动机、高达12.5：1的高压缩比和电水泵大大提升了车辆的燃油经济性。', '正确', '错误', '', '', '', '', '', '', '', '1');

-- --------------------------------------------------------

--
-- 表的结构 `app_paper_content`
--

CREATE TABLE IF NOT EXISTS `app_paper_content` (
  `paper_id` varchar(16) NOT NULL COMMENT '试卷唯一ID',
  `paper_name` varchar(64) NOT NULL COMMENT '试卷名称',
  `course_id` varchar(8) NOT NULL COMMENT '所属课程ID',
  `total_grade` varchar(8) NOT NULL COMMENT '试卷总分',
  `question_num` varchar(8) NOT NULL COMMENT '试卷题目数量',
  `test_time` time NOT NULL COMMENT '该试卷的考试时间',
  `deadline` datetime NOT NULL COMMENT '提交截止日期',
  `content` text NOT NULL COMMENT '试卷',
  `answer` varchar(128) NOT NULL COMMENT '试卷答案',
  PRIMARY KEY (`paper_id`),
  UNIQUE KEY `paper_id` (`paper_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_paper_content`
--

INSERT INTO `app_paper_content` (`paper_id`, `paper_name`, `course_id`, `total_grade`, `question_num`, `test_time`, `deadline`, `content`, `answer`) VALUES
('201001', '西南科技大学2014级高等数学B期末考试试卷（一）', '201', '100', '6', '01:30:00', '2015-11-30 00:00:00', '2011010001;2011020001;2011030001;2011010002;2011010003;2011010004', '3;13;1;2;4;1'),
('201002', '西南科技大学计算机基本技能训练期末试卷', '201', '100', '3', '00:00:00', '0000-00-00 00:00:00', '2011010001;2011020001;2011030001', '');

-- --------------------------------------------------------

--
-- 表的结构 `app_paper_grade`
--

CREATE TABLE IF NOT EXISTS `app_paper_grade` (
  `test_type_id` int(16) NOT NULL COMMENT '试卷号+问题类型',
  `paper_id` varchar(16) NOT NULL COMMENT '试卷号',
  `question_type` int(4) NOT NULL COMMENT '所属问题类型ID',
  `grade` int(4) NOT NULL COMMENT '该类问题分数',
  PRIMARY KEY (`test_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_paper_grade`
--

INSERT INTO `app_paper_grade` (`test_type_id`, `paper_id`, `question_type`, `grade`) VALUES
(201001101, '201001', 101, 4),
(201001102, '201001', 102, 2),
(201001103, '201001', 103, 4);

-- --------------------------------------------------------

--
-- 表的结构 `app_student_auth`
--

CREATE TABLE IF NOT EXISTS `app_student_auth` (
  `student_id` varchar(32) NOT NULL COMMENT '验证登陆唯一标示符',
  `student_password` varchar(32) NOT NULL COMMENT '密码',
  `login_status` tinyint(1) NOT NULL COMMENT '登陆状态',
  `student_auth` int(4) NOT NULL DEFAULT '0' COMMENT '权限',
  `reg_time` datetime NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `studentid` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生登陆信息表';

--
-- 转存表中的数据 `app_student_auth`
--

INSERT INTO `app_student_auth` (`student_id`, `student_password`, `login_status`, `student_auth`, `reg_time`) VALUES
('123', '123', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `app_student_grade`
--

CREATE TABLE IF NOT EXISTS `app_student_grade` (
  `id` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '试卷号+学号',
  `student_id` varchar(32) CHARACTER SET utf8 NOT NULL,
  `course_id` varchar(16) CHARACTER SET utf8 NOT NULL,
  `paper_id` varchar(32) CHARACTER SET utf8 NOT NULL,
  `grade` varchar(8) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `app_student_info`
--

CREATE TABLE IF NOT EXISTS `app_student_info` (
  `student_id` varchar(32) NOT NULL COMMENT '学生登陆ID',
  `student_name` varchar(16) NOT NULL,
  `student_course` varchar(32) NOT NULL COMMENT '学生在进行的课程',
  `belong_teacher` varchar(32) NOT NULL COMMENT '学生的老师',
  `finish_paper` varchar(32) DEFAULT NULL COMMENT '所完成的试卷号',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_student_info`
--

INSERT INTO `app_student_info` (`student_id`, `student_name`, `student_course`, `belong_teacher`, `finish_paper`) VALUES
('123', '谷昆峰', '201', '112;113', '201001');

-- --------------------------------------------------------

--
-- 表的结构 `app_submit_exam`
--

CREATE TABLE IF NOT EXISTS `app_submit_exam` (
  `submit_id` varchar(64) NOT NULL COMMENT '试卷号+学号+题号',
  `num` varchar(16) NOT NULL COMMENT '在试卷中的顺序',
  `paper_id` varchar(32) NOT NULL COMMENT '所完成的试卷ID',
  `course_id` varchar(16) NOT NULL COMMENT '所属课程的ID',
  `student_id` varchar(32) NOT NULL COMMENT '提交该测试的学生ID',
  `question_id` varchar(16) NOT NULL COMMENT '题目ID',
  `type` varchar(8) NOT NULL COMMENT '问题类型',
  `answer` varchar(8) NOT NULL COMMENT '提交的答案',
  `status` int(11) NOT NULL COMMENT '0为错误 1为正确',
  `time` datetime NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`submit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_submit_exam`
--

INSERT INTO `app_submit_exam` (`submit_id`, `num`, `paper_id`, `course_id`, `student_id`, `question_id`, `type`, `answer`, `status`, `time`) VALUES
('2010011232011010001', '1', '201001', '201', '123', '2011010001', '101', '2', 0, '0000-00-00 00:00:00'),
('2010011232011010002', '4', '201001', '201', '123', '2011010002', '101', '4', 0, '0000-00-00 00:00:00'),
('2010011232011010003', '5', '201001', '201', '123', '2011010003', '101', '2', 0, '0000-00-00 00:00:00'),
('2010011232011010004', '6', '201001', '201', '123', '2011010004', '101', '2', 0, '0000-00-00 00:00:00'),
('2010011232011020001', '2', '201001', '201', '123', '2011020001', '102', '3;2', 0, '0000-00-00 00:00:00'),
('2010011232011030001', '3', '201001', '201', '123', '2011030001', '103', '3;1', 0, '0000-00-00 00:00:00'),
('2010021232011010001', '1', '201002', '201', '123', '2011010001', '101', '3', 0, '0000-00-00 00:00:00'),
('2010021232011020001', '2', '201002', '201', '123', '2011020001', '102', '4;2', 0, '0000-00-00 00:00:00'),
('2010021232011030001', '3', '201002', '201', '123', '2011030001', '103', '4;1', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `app_submit_summary`
--

CREATE TABLE IF NOT EXISTS `app_submit_summary` (
  `save_id` varchar(32) NOT NULL COMMENT '试卷号+学号',
  `paper_id` varchar(16) NOT NULL COMMENT '试卷ID',
  `student_id` varchar(32) NOT NULL COMMENT '学生ID',
  `course_id` varchar(8) NOT NULL COMMENT '课程ID',
  `grade` varchar(8) NOT NULL COMMENT '得分',
  `question_correct` varchar(32) NOT NULL COMMENT '正确的题目',
  `question_wrong` varchar(32) NOT NULL COMMENT '错误的题目',
  `answer` varchar(32) NOT NULL COMMENT '学生的答案',
  `submit_time` date NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`save_id`),
  UNIQUE KEY `save_id` (`save_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_submit_summary`
--

INSERT INTO `app_submit_summary` (`save_id`, `paper_id`, `student_id`, `course_id`, `grade`, `question_correct`, `question_wrong`, `answer`, `submit_time`) VALUES
('201001123', '201001', '123', '201', '12', '2011010001', '2011020001;2011030001', '4;12;2', '0000-00-00');

-- --------------------------------------------------------

--
-- 表的结构 `app_sys`
--

CREATE TABLE IF NOT EXISTS `app_sys` (
  `type_id` int(6) NOT NULL COMMENT '类型ID',
  `belong_type` int(6) NOT NULL COMMENT 'ID所属的类型',
  `type_name` varchar(16) NOT NULL COMMENT '类型名称',
  `status` tinyint(1) NOT NULL COMMENT '课程或题目类型的可用状态',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `lv_2_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_sys`
--

INSERT INTO `app_sys` (`type_id`, `belong_type`, `type_name`, `status`) VALUES
(101, 100, '单项选择题', 1),
(102, 100, '多项选择题', 1),
(103, 100, '判断题', 1),
(104, 100, '填空题', 0),
(105, 100, '问答题', 0),
(201, 200, '高等数学', 1),
(202, 200, '大学物理', 1);

-- --------------------------------------------------------

--
-- 表的结构 `app_teacher_auth`
--

CREATE TABLE IF NOT EXISTS `app_teacher_auth` (
  `teacher_id` varchar(32) NOT NULL COMMENT '教师登陆ID',
  `teacher_name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL COMMENT '登陆密码',
  `login_status` tinyint(1) NOT NULL COMMENT '登录状态',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师登陆信息表';

--
-- 转存表中的数据 `app_teacher_auth`
--

INSERT INTO `app_teacher_auth` (`teacher_id`, `teacher_name`, `password`, `login_status`) VALUES
('112', 'Bob', '123', 0);

-- --------------------------------------------------------

--
-- 表的结构 `app_teacher_info`
--

CREATE TABLE IF NOT EXISTS `app_teacher_info` (
  `teacher_id` varchar(32) NOT NULL COMMENT '教师ID',
  `teacher_name` varchar(16) NOT NULL COMMENT '教师姓名',
  `course_id` varchar(8) NOT NULL COMMENT '所属课程',
  `add_test` varchar(32) NOT NULL COMMENT '该教师所增加的试卷',
  `using_test` varchar(32) NOT NULL COMMENT '处于使用状态的试卷',
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_teacher_info`
--

INSERT INTO `app_teacher_info` (`teacher_id`, `teacher_name`, `course_id`, `add_test`, `using_test`) VALUES
('112', 'Bob', '201', '201001', '201001'),
('113', 'Tec', '201', '201002', '201002');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
