-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2022 at 11:08 PM
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
-- Database: `hotelthegrandchandiram`
--

-- --------------------------------------------------------

--
-- Table structure for table `career`
--

CREATE TABLE `career` (
  `care_id` int(10) UNSIGNED NOT NULL,
  `care_name` varchar(255) NOT NULL,
  `care_phone` varchar(255) NOT NULL,
  `care_email` varchar(255) NOT NULL,
  `care_file` text DEFAULT NULL,
  `care_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `care_ip` varchar(15) NOT NULL,
  `care_status` int(1) NOT NULL DEFAULT 1,
  `care_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `care_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `care_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `career`
--

INSERT INTO `career` (`care_id`, `care_name`, `care_phone`, `care_email`, `care_file`, `care_date`, `care_ip`, `care_status`, `care_create_at`, `care_update_at`, `care_delete_at`) VALUES
(1, 'Sarkar', '9966544231', 'ss@gm.com', 'sarkar_1618124215104386407.pdf', '2021-04-11 06:56:55', '::1', 1, '2021-04-11 06:56:55', '2021-04-11 09:35:44', '2021-04-11 09:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `emp_cat`
--

CREATE TABLE `emp_cat` (
  `cat_id` int(10) UNSIGNED NOT NULL,
  `cat_role_id_ref` int(11) UNSIGNED NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_status` int(1) NOT NULL DEFAULT 1,
  `cat_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cat_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cat_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emp_cat`
--

INSERT INTO `emp_cat` (`cat_id`, `cat_role_id_ref`, `cat_name`, `cat_status`, `cat_create_at`, `cat_update_at`, `cat_delete_at`) VALUES
(1, 1, 'Admin', 1, '2021-01-02 08:06:29', '2021-01-25 10:46:54', NULL),
(20, 0, 'Front Office Manager', 1, '2021-02-28 17:18:21', '2021-02-28 17:18:21', NULL),
(21, 0, 'Accountant', 1, '2021-02-28 17:18:33', '2021-02-28 17:18:33', NULL),
(22, 0, 'Supervisor', 1, '2021-02-28 17:18:48', '2021-02-28 17:18:48', NULL),
(23, 0, 'Roomboy', 1, '2021-02-28 17:18:57', '2021-02-28 17:18:57', NULL),
(24, 0, 'Sweeper', 1, '2021-02-28 17:19:00', '2021-02-28 17:19:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emp_doc`
--

CREATE TABLE `emp_doc` (
  `doc_id` int(10) NOT NULL,
  `doc_user_id_ref` int(10) UNSIGNED NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `doc_img` text DEFAULT NULL,
  `doc_status` int(1) NOT NULL DEFAULT 1,
  `doc_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doc_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `doc_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `emp_salary`
--

CREATE TABLE `emp_salary` (
  `sal_id` int(10) NOT NULL,
  `sal_user_id_ref` int(10) UNSIGNED NOT NULL,
  `sal_amount` int(10) UNSIGNED NOT NULL,
  `sal_date` date DEFAULT NULL,
  `sal_status` int(1) NOT NULL DEFAULT 1,
  `sal_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sal_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sal_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emp_salary`
--

INSERT INTO `emp_salary` (`sal_id`, `sal_user_id_ref`, `sal_amount`, `sal_date`, `sal_status`, `sal_create_at`, `sal_update_at`, `sal_delete_at`) VALUES
(1, 2, 20000, '0000-00-00', 1, '2022-06-07 07:50:36', '2022-06-07 07:50:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `enq_id` int(10) UNSIGNED NOT NULL,
  `enq_name` text DEFAULT NULL,
  `enq_phone` varchar(20) NOT NULL,
  `enq_email` text DEFAULT NULL,
  `enq_ar_date` date DEFAULT NULL,
  `enq_ip` varchar(15) NOT NULL,
  `enq_status` int(11) NOT NULL DEFAULT 1,
  `enq_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `enq_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `enq_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gal_id` int(10) UNSIGNED NOT NULL,
  `gal_img` text NOT NULL,
  `gal_img_lg` text DEFAULT NULL,
  `gal_img_name` text DEFAULT NULL,
  `gal_img_status` int(1) NOT NULL DEFAULT 1,
  `gal_img_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gal_img_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gal_img_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gal_id`, `gal_img`, `gal_img_lg`, `gal_img_name`, `gal_img_status`, `gal_img_update_at`, `gal_img_create_at`, `gal_img_delete_at`) VALUES
(2, '1617871789909828681.jpg', '1617871789765909284_lg.jpg', NULL, 1, '2021-04-08 08:49:49', '2021-04-08 08:32:05', NULL),
(3, '1617871732231017554.jpg', '1617871732914678410_lg.jpg', NULL, 1, '2021-04-08 08:48:53', '2021-04-08 08:32:24', NULL),
(4, '1617871755618143023.jpg', '1617871755315272207_lg.jpg', NULL, 1, '2021-04-08 08:49:16', '2021-04-08 08:39:39', NULL),
(5, '1617871804490482201.jpg', '1617871804432032910_lg.jpg', NULL, 1, '2021-04-08 08:50:05', '2021-04-08 08:44:25', NULL),
(6, '1617871840436482904.jpg', '1617871840813706769_lg.jpg', NULL, 1, '2021-04-08 08:50:40', '2021-04-08 08:44:34', NULL),
(7, '1617871831444366864.jpg', '1617871831644660455_lg.jpg', NULL, 1, '2021-04-08 08:50:31', '2021-04-08 08:45:13', NULL),
(8, '16178794176512877.jpg', '1617879417748124397_lg.jpg', NULL, 1, '2022-05-17 07:03:21', '2021-04-08 08:45:38', NULL),
(9, '161787154568074334.jpg', '1617871545449439200_lg.jpg', NULL, 1, '2022-05-17 07:03:20', '2021-04-08 08:45:45', NULL),
(10, '1617871702831755489.jpg', '1617871702481141338_lg.jpg', NULL, 1, '2022-05-17 07:03:19', '2021-04-08 08:45:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `mem_id` int(10) UNSIGNED NOT NULL,
  `mem_img` text DEFAULT NULL,
  `mem_name` varchar(255) NOT NULL,
  `mem_email` text DEFAULT NULL,
  `mem_phone` varchar(20) NOT NULL,
  `mem_password` text NOT NULL,
  `mem_alternative_phone` text DEFAULT NULL,
  `mem_address` text DEFAULT NULL,
  `mem_dob` date DEFAULT NULL,
  `mem_anniversary` date DEFAULT NULL,
  `mem_status` int(1) NOT NULL DEFAULT 1,
  `mem_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mem_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mem_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`mem_id`, `mem_img`, `mem_name`, `mem_email`, `mem_phone`, `mem_password`, `mem_alternative_phone`, `mem_address`, `mem_dob`, `mem_anniversary`, `mem_status`, `mem_update_at`, `mem_create_at`, `mem_delete_at`) VALUES
(1, NULL, 'Suprotive Sarkar', 'ssr@gmail.com', '9064389417', '123456', NULL, NULL, NULL, NULL, 1, '2021-02-26 15:58:59', '2021-02-24 09:56:06', NULL),
(2, NULL, 'Aditya Narayan Guru', 'aditguru186@gmail.com', '9040144080', 'Aditya@12345', NULL, NULL, NULL, NULL, 1, '2021-02-24 14:03:54', '2021-02-24 14:03:54', NULL),
(3, NULL, 'Sanjay sahu', 'sanju84.sahu@gmail.com', '7364984013', 'San@1234', NULL, NULL, NULL, NULL, 1, '2021-02-25 04:45:11', '2021-02-25 04:45:11', NULL),
(4, NULL, 'S Sarkar', 'sup@gmail.com', '7894561230', '123456', NULL, NULL, NULL, NULL, 1, '2021-02-27 06:37:19', '2021-02-27 06:37:19', NULL),
(5, NULL, 'Nakul', 'nakulsarkar23@gmail.com', '7003232243', '123456', NULL, NULL, NULL, NULL, 1, '2021-03-19 17:52:19', '2021-03-19 17:52:19', NULL),
(6, NULL, 'Supro', 'ff@gm.com', '9064389417', '906438', NULL, NULL, NULL, NULL, 1, '2021-04-13 09:23:38', '2021-04-13 06:47:34', NULL),
(7, NULL, 'Dummy Name', 'dn@gmail.com', '7894561231', '123456', NULL, NULL, NULL, NULL, 1, '2022-03-10 07:48:11', '2022-03-10 07:48:11', NULL),
(8, NULL, 'Rohit Das', 'rdas2147@gmail.com', '8436303116', '2012india', NULL, NULL, NULL, NULL, 1, '2022-05-16 04:23:59', '2022-05-16 04:23:59', NULL),
(9, NULL, 'A', 'abc@gmail.com', '9000001234', 'a123123', NULL, NULL, NULL, NULL, 1, '2022-05-28 06:44:27', '2022-05-28 06:44:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quick_contact`
--

CREATE TABLE `quick_contact` (
  `qid` int(11) NOT NULL,
  `qname` varchar(255) NOT NULL,
  `qemail` varchar(255) NOT NULL,
  `qmobile` varchar(50) DEFAULT NULL,
  `qsubject` varchar(255) NOT NULL,
  `qmsg` varchar(1000) NOT NULL,
  `qdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `qip` varchar(15) NOT NULL,
  `qstatus` int(11) NOT NULL DEFAULT 1,
  `qcreateat` timestamp NOT NULL DEFAULT current_timestamp(),
  `qdeleteat` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quick_contact`
--

INSERT INTO `quick_contact` (`qid`, `qname`, `qemail`, `qmobile`, `qsubject`, `qmsg`, `qdate`, `qip`, `qstatus`, `qcreateat`, `qdeleteat`) VALUES
(1, 'Sarkar', 'ss@gm.com', '9966544311', 'sds', 'sdfsdfd', '2021-04-08 09:31:01', '192.168.0.105', 2, '2021-04-08 09:31:01', '2021-04-11 07:51:44'),
(2, 'g nhn', 'ss@gm.com', '9966544231', 'dvd', 'xcvxcvxcvxcvxcv', '2021-04-08 09:31:50', '192.168.0.105', 2, '2021-04-08 09:31:50', '2021-04-11 07:48:40'),
(3, 'g nhn', 'ss@gm.com', '9966544231', 'dvd', 'xcvxcvxcvxcvxcv', '2021-04-08 09:32:28', '192.168.0.105', 2, '2021-04-08 09:32:28', '2021-04-11 07:48:37'),
(4, 'g nhn', 'ss@gm.com', '9966544231', 'dvd', 'bnbvnvbnvbnv', '2021-04-08 09:32:42', '192.168.0.105', 2, '2021-04-08 09:32:42', '2021-04-11 07:48:43'),
(5, 'Suprotive Sarkar', 'ha@gmail.com', '9867438941', 'Fhcbhgv', 'Hbabsjd. Djbd s shs. Hd sjcbrkdbs', '2021-04-10 09:12:29', '192.168.29.67', 1, '2021-04-10 09:12:29', '2021-04-10 09:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_list`
--

CREATE TABLE `reservation_list` (
  `res_id` int(10) UNSIGNED NOT NULL,
  `res_mem_id_ref` int(10) UNSIGNED DEFAULT NULL,
  `res_g_name` varchar(1000) NOT NULL,
  `res_g_phone` varchar(1000) NOT NULL,
  `res_g_email` varchar(500) DEFAULT NULL,
  `res_g_address` text NOT NULL,
  `res_g_city` varchar(700) NOT NULL,
  `res_g_zipcode` varchar(50) NOT NULL,
  `res_g_country` varchar(500) DEFAULT NULL,
  `res_g_adult` int(11) NOT NULL,
  `res_g_child` int(11) NOT NULL DEFAULT 0,
  `res_g_doc` text DEFAULT NULL,
  `res_g_note` text DEFAULT NULL,
  `res_g_totalamt` float(11,2) DEFAULT NULL,
  `res_g_intime` time DEFAULT NULL,
  `res_g_outtime` time DEFAULT NULL,
  `res_status` int(1) NOT NULL,
  `res_cancel_time` timestamp NULL DEFAULT NULL,
  `res_refund_amount` float(10,2) DEFAULT NULL,
  `res_refund_way` int(11) DEFAULT NULL COMMENT '0-cash/1-online',
  `res_refund_transaction_id` text DEFAULT NULL,
  `res_refund_note` text DEFAULT NULL,
  `res_refund_by` int(10) UNSIGNED DEFAULT NULL,
  `res_refund_time` timestamp NULL DEFAULT NULL,
  `res_by` int(10) UNSIGNED DEFAULT NULL,
  `res_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `res_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `res_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation_list`
--

INSERT INTO `reservation_list` (`res_id`, `res_mem_id_ref`, `res_g_name`, `res_g_phone`, `res_g_email`, `res_g_address`, `res_g_city`, `res_g_zipcode`, `res_g_country`, `res_g_adult`, `res_g_child`, `res_g_doc`, `res_g_note`, `res_g_totalamt`, `res_g_intime`, `res_g_outtime`, `res_status`, `res_cancel_time`, `res_refund_amount`, `res_refund_way`, `res_refund_transaction_id`, `res_refund_note`, `res_refund_by`, `res_refund_time`, `res_by`, `res_create_at`, `res_update_at`, `res_delete_at`) VALUES
(3, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'Hatikota', 'Puri', '752001', 'India', 3, 2, NULL, NULL, NULL, '06:25:00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-02-26 06:41:22', '2021-02-26 16:03:36', NULL),
(5, 4, 'S Sarkar', '7894561230', 'sup@gmail.com', 'zdcvxvxcv', 'xcvxcvxcvxcv', '654321', NULL, 4, 0, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-02-27 06:38:38', '2021-02-27 07:17:49', '2021-02-27 19:47:49'),
(6, 3, 'Sanjay sahu', '7364984013', 'sanju84.sahu@gmail.com', 'xczxvxv', 'cvxcvcxv', '654321', 'India', 5, 0, 'sanjay_sahu_1614495445670612471.pdf', NULL, NULL, '13:30:00', NULL, 3, NULL, 100.00, 0, 'tid', 'note', 1, '2021-03-18 18:17:50', NULL, '2021-02-28 06:58:23', '2021-03-18 05:47:50', '2021-02-28 19:51:16'),
(7, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'HYD', 'HYD', '500084', 'INDIA', 1, 0, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-19 12:34:29', '2021-03-19 12:40:58', '2021-03-20 01:10:58'),
(8, 5, 'Nakul', '7003232243', 'nakulsarkar23@gmail.com', 'Adr', 'city', '747474', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, 3, '2021-03-20 06:25:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-19 17:53:01', '2021-03-19 17:55:30', NULL),
(9, 5, 'Nakul', '7003232243', 'nakulsarkar23@gmail.com', 'adr', 'city', '747474', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-19 18:00:21', '2021-03-31 06:22:43', NULL),
(10, 5, 'Nakul', '7003232243', 'nakulsarkar23@gmail.com', 'df', 'fds', '747474', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-19 18:04:35', '2021-03-19 18:04:35', NULL),
(11, 5, 'Nakul', '7003232243', 'nakulsarkar23@gmail.com', 'sdsa', 'sd', '747474', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-19 18:32:47', '2021-03-19 18:32:47', NULL),
(12, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'Temp', 'Temp1', '5000000', 'INDIA', 3, 0, NULL, NULL, NULL, NULL, NULL, 3, '2021-03-20 07:12:33', 2798.00, 1, 'qwerty12345', NULL, 1, '2021-03-20 07:14:54', NULL, '2021-03-19 18:38:45', '2021-03-19 18:44:54', NULL),
(13, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'XYZ', 'TEMPCity', '751024', 'India', 1, 0, NULL, NULL, NULL, NULL, NULL, 3, '2021-03-31 00:32:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-30 11:49:48', '2021-03-30 12:02:19', NULL),
(14, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'HATIKOTA', 'PURI', '752001', 'India', 1, 0, 'aditya_narayan_guru_1617106058536206529.jpg', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-30 12:08:37', '2021-03-30 12:08:37', NULL),
(15, 2, 'Aditya Narayan Guru', '9040144080', 'aditguru186@gmail.com', 'zczxcczx', 'zxcxzczxc', '789456', NULL, 5, 6, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-04-14 09:42:36', '2021-04-14 09:42:36', NULL),
(16, 1, 'Suprotive Sarkar', '9064389417', 'ssr@gmail.com', 'ccfdx', 'dsfsdf', '654321', NULL, 4, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-04-14 12:45:32', '2021-04-14 12:45:32', NULL),
(17, 1, 'Suprotive Sarkar', '9064389417', 'ssr@gmail.com', 'b gcnn', 'vgnvng', '134754', NULL, 3, 1, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-16 04:21:05', '2022-05-16 04:21:05', NULL),
(18, 8, 'Rohit Das', '8436303116', 'rdas2147@gmail.com', 'guygu', 'bbhuby', '456123', NULL, 3, 3, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-16 04:29:42', '2022-05-16 04:37:10', NULL),
(19, NULL, 'R Das', '8436303116,7003825805', 'rdas123@gmail.com,rd007@gmail.com', 'xcvxcv', 'cxvxcvcv', '894561', NULL, 4, 0, NULL, 'morning flight', 2797.00, '01:05:00', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-16 04:35:35', '2022-05-16 04:38:08', '2022-05-16 04:38:08'),
(20, 8, 'Rohit Das', '8436303116', 'rdas2147@gmail.com', 'frdghfgh', 'hjbhbj', '123456', NULL, 3, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-28 05:53:12', '2022-05-28 05:53:12', NULL),
(21, 8, 'Rohit Das', '8436303116', 'rdas2147@gmail.com', 'kol', 'kol', '742101', NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-28 07:11:17', '2022-05-28 07:14:27', '2022-05-28 07:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_pay`
--

CREATE TABLE `reservation_pay` (
  `pay_id` int(10) UNSIGNED NOT NULL,
  `pay_res_id_ref` int(11) UNSIGNED NOT NULL,
  `pay_amount` float(11,2) NOT NULL,
  `pay_by` int(10) UNSIGNED DEFAULT NULL,
  `pay_type` int(11) NOT NULL DEFAULT 0,
  `pay_transaction` text DEFAULT NULL,
  `pay_status` int(1) NOT NULL DEFAULT 1,
  `pay_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pay_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pay_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation_pay`
--

INSERT INTO `reservation_pay` (`pay_id`, `pay_res_id_ref`, `pay_amount`, `pay_by`, `pay_type`, `pay_transaction`, `pay_status`, `pay_create_at`, `pay_update_at`, `pay_delete_at`) VALUES
(3, 3, 4477.76, NULL, 1, 'pay_Gg6hKMPTdWPWdM', 1, '2021-02-26 19:11:22', '2021-02-26 19:11:22', NULL),
(5, 5, 4251.52, NULL, 1, 'pay_GgVBtUKr1U2stR', 2, '2021-02-27 19:08:38', '2021-02-27 07:17:49', '2021-02-27 19:47:49'),
(6, 6, 5595.52, NULL, 1, 'pay_Ggu3lPBwLIY9T2', 2, '2021-02-28 19:28:23', '2021-02-28 07:21:16', '2021-02-28 19:51:16'),
(7, 7, 2238.88, NULL, 1, 'pay_GoVurvvfO2VQCX', 2, '2021-03-20 01:04:29', '2021-03-19 12:40:58', '2021-03-20 01:10:58'),
(8, 8, 2238.88, NULL, 1, 'pay_GobMg98Oa3yAo3', 1, '2021-03-20 06:23:01', '2021-03-20 06:23:01', NULL),
(9, 9, 649.00, NULL, 1, 'pay_GobUPh03YRdTrl', 1, '2021-03-20 06:30:20', '2021-03-20 06:30:20', NULL),
(10, 10, 949.00, NULL, 1, 'pay_GobYskS3oYYs8N', 1, '2021-03-20 06:34:34', '2021-03-20 06:34:34', NULL),
(11, 11, 49560.00, NULL, 1, 'pay_Goc2fe4HXKzXZH', 1, '2021-03-20 07:02:46', '2021-03-20 07:02:46', NULL),
(12, 12, 2798.88, NULL, 1, 'pay_Goc8r7Q2Euehfk', 1, '2021-03-20 07:08:44', '2021-03-20 07:08:44', NULL),
(13, 13, 2797.76, NULL, 1, 'pay_Gsr2s5FDohF1GF', 1, '2021-03-31 00:19:48', '2021-03-31 00:19:48', NULL),
(14, 14, 949.00, NULL, 1, 'pay_GsrMnEtsl2e3RC', 1, '2021-03-31 00:38:37', '2021-03-31 00:38:37', NULL),
(15, 15, 6377.28, NULL, 1, 'pay_GyktlAOAlM4kqT', 1, '2021-04-14 09:42:36', '2021-04-14 09:42:36', NULL),
(16, 16, 2125.76, NULL, 1, 'pay_Gyo0yP83NaOGQt', 2, '2021-04-14 12:45:30', '2022-03-10 07:57:45', '2022-03-10 07:57:45'),
(17, 16, 1000.00, 1, 0, NULL, 1, '2022-03-10 07:57:51', '2022-03-10 07:57:51', NULL),
(18, 17, 4251.52, NULL, 1, 'pay_JVlUvS6wiPnqIv', 1, '2022-05-16 04:21:03', '2022-05-16 04:21:03', NULL),
(19, 18, 4251.52, NULL, 1, 'pay_JVle1g5b431FZm', 1, '2022-05-16 04:29:41', '2022-05-16 04:29:41', NULL),
(20, 19, 1000.00, 1, 0, NULL, 2, '2022-05-16 04:35:35', '2022-05-16 04:38:08', '2022-05-16 04:38:08'),
(21, 19, 1000.00, 1, 0, NULL, 2, '2022-05-16 04:36:08', '2022-05-16 04:38:08', '2022-05-16 04:38:08'),
(22, 19, 1132.00, 1, 0, NULL, 2, '2022-05-16 04:36:27', '2022-05-16 04:38:08', '2022-05-16 04:38:08'),
(23, 20, 2125.76, NULL, 1, 'pay_JaXTf2pQ63dnVo', 1, '2022-05-28 05:53:11', '2022-05-28 05:53:11', NULL),
(24, 21, 949.00, NULL, 1, 'pay_JaYo82osEV2YZy', 2, '2022-05-28 07:11:15', '2022-05-28 07:14:27', '2022-05-28 07:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_rooms`
--

CREATE TABLE `reservation_rooms` (
  `resrooms_id` int(10) UNSIGNED NOT NULL,
  `resrooms_res_id_ref` int(10) UNSIGNED NOT NULL,
  `resrooms_room_id_ref` int(10) UNSIGNED NOT NULL,
  `resrooms_roomtype` varchar(500) NOT NULL,
  `resrooms_room_no` varchar(100) NOT NULL,
  `resrooms_roomprice` float(10,2) NOT NULL,
  `resrooms_exbed_qty` int(10) DEFAULT 0,
  `resrooms_extrabed_price` varchar(200) DEFAULT NULL,
  `resrooms_in` date NOT NULL,
  `resrooms_out` date NOT NULL,
  `resrooms_status` int(1) NOT NULL DEFAULT 1,
  `resrooms_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resrooms_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resrooms_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation_rooms`
--

INSERT INTO `reservation_rooms` (`resrooms_id`, `resrooms_res_id_ref`, `resrooms_room_id_ref`, `resrooms_roomtype`, `resrooms_room_no`, `resrooms_roomprice`, `resrooms_exbed_qty`, `resrooms_extrabed_price`, `resrooms_in`, `resrooms_out`, `resrooms_status`, `resrooms_create_at`, `resrooms_update_at`, `resrooms_delete_at`) VALUES
(3, 3, 15, 'Premium Double Bed', '417', 1999.00, 0, '699', '2021-02-27', '2021-02-28', 1, '2021-02-26 06:41:22', '2021-02-26 06:41:22', NULL),
(4, 3, 30, 'Premium Double Bed', '418', 1999.00, 0, '699', '2021-02-27', '2021-02-28', 1, '2021-02-26 06:41:22', '2021-02-26 06:41:22', NULL),
(5, 5, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2021-02-27', '2021-03-01', 2, '2021-02-27 06:38:38', '2021-02-27 06:38:38', '2021-02-27 19:47:49'),
(6, 5, 56, 'Deluxe Double Bed', '206', 949.00, 0, '299', '2021-02-27', '2021-03-01', 2, '2021-02-27 06:38:38', '2021-02-27 06:38:38', '2021-02-27 19:47:49'),
(7, 6, 54, 'Deluxe Triple Bed', '204', 1249.00, 0, '299', '2021-03-03', '2021-03-05', 2, '2021-02-28 06:58:23', '2021-02-28 06:58:23', '2021-02-28 19:51:16'),
(8, 6, 58, 'Deluxe Triple Bed', '208', 1249.00, 0, '299', '2021-03-03', '2021-03-05', 2, '2021-02-28 06:58:23', '2021-02-28 06:58:23', '2021-02-28 19:51:16'),
(9, 7, 15, 'Premium Double Bed', '417', 1999.00, 0, '699', '2021-04-01', '2021-04-02', 2, '2021-03-19 12:34:29', '2021-03-19 12:34:29', '2021-03-20 01:10:58'),
(10, 8, 66, 'Family Rooms', '216', 1999.00, 0, NULL, '2021-03-20', '2021-03-21', 1, '2021-03-19 17:53:01', '2021-03-19 17:53:01', NULL),
(11, 9, 51, 'Standard Double Bed', '201', 649.00, 0, '299', '2021-03-20', '2021-03-21', 1, '2021-03-19 18:00:21', '2021-03-19 18:00:21', NULL),
(12, 10, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2021-03-26', '2021-03-27', 1, '2021-03-19 18:04:35', '2021-03-19 18:04:35', NULL),
(13, 11, 4, 'Luxury Conference Halls', '001', 21000.00, 0, NULL, '2021-03-25', '2021-03-27', 1, '2021-03-19 18:32:47', '2021-03-19 18:32:47', NULL),
(14, 12, 31, 'Premium Triple Bed', '419', 2499.00, 0, '699', '2021-03-28', '2021-03-29', 1, '2021-03-19 18:38:45', '2021-03-19 18:38:45', NULL),
(15, 13, 54, 'Deluxe Triple Bed', '204', 1249.00, 0, '299', '2021-03-31', '2021-04-02', 1, '2021-03-30 11:49:48', '2021-03-30 11:49:48', NULL),
(16, 14, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2021-04-01', '2021-04-02', 1, '2021-03-30 12:08:37', '2021-03-30 12:08:37', NULL),
(17, 15, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2021-04-14', '2021-04-16', 1, '2021-04-14 09:42:36', '2021-04-14 09:42:36', NULL),
(18, 15, 56, 'Deluxe Double Bed', '206', 949.00, 0, '299', '2021-04-14', '2021-04-16', 1, '2021-04-14 09:42:36', '2021-04-14 09:42:36', NULL),
(19, 15, 57, 'Deluxe Double Bed', '207', 949.00, 0, '299', '2021-04-14', '2021-04-16', 1, '2021-04-14 09:42:36', '2021-04-14 09:42:36', NULL),
(20, 16, 59, 'Deluxe Double Bed', '209', 949.00, 0, '299', '2021-04-14', '2021-04-15', 1, '2021-04-14 12:45:32', '2021-04-14 12:45:32', NULL),
(21, 16, 67, 'Deluxe Double Bed', '301', 949.00, 0, '299', '2021-04-14', '2021-04-15', 1, '2021-04-14 12:45:32', '2021-04-14 12:45:32', NULL),
(22, 17, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2022-05-18', '2022-05-20', 1, '2022-05-16 04:21:05', '2022-05-16 04:21:05', NULL),
(23, 17, 56, 'Deluxe Double Bed', '206', 949.00, 0, '299', '2022-05-18', '2022-05-20', 1, '2022-05-16 04:21:05', '2022-05-16 04:21:05', NULL),
(24, 18, 57, 'Deluxe Double Bed', '207', 949.00, 0, '299', '2022-05-17', '2022-05-19', 1, '2022-05-16 04:29:42', '2022-05-16 04:29:42', NULL),
(25, 18, 59, 'Deluxe Double Bed', '209', 949.00, 0, '299', '2022-05-17', '2022-05-19', 1, '2022-05-16 04:29:42', '2022-05-16 04:29:42', NULL),
(26, 19, 58, 'Deluxe Triple Bed', '208', 1249.00, 1, '299', '2022-05-16', '2022-05-18', 2, '2022-05-16 04:35:35', '2022-05-16 04:35:35', '2022-05-16 04:38:08'),
(27, 20, 55, 'Deluxe Double Bed', '205', 949.00, 0, '299', '2022-06-02', '2022-06-03', 1, '2022-05-28 05:53:12', '2022-05-28 05:53:12', NULL),
(28, 20, 56, 'Deluxe Double Bed', '206', 949.00, 0, '299', '2022-06-02', '2022-06-03', 1, '2022-05-28 05:53:12', '2022-05-28 05:53:12', NULL),
(29, 21, 57, 'Deluxe Double Bed', '207', 949.00, 0, '299', '2022-06-02', '2022-06-03', 2, '2022-05-28 07:11:17', '2022-05-28 07:11:17', '2022-05-28 07:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `room_amenities`
--

CREATE TABLE `room_amenities` (
  `am_id` int(10) UNSIGNED NOT NULL,
  `am_name` varchar(300) NOT NULL,
  `am_slug` varchar(300) NOT NULL,
  `am_status` int(1) NOT NULL DEFAULT 1,
  `am_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `am_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `am_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_amenities`
--

INSERT INTO `room_amenities` (`am_id`, `am_name`, `am_slug`, `am_status`, `am_update_at`, `am_create_at`, `am_delete_at`) VALUES
(1, 'Cable Tv', 'cable-tv', 2, '2021-01-07 23:43:04', '2021-01-07 23:35:38', '2021-01-07 23:43:04'),
(2, 'Heater', 'heater', 1, '2021-01-07 23:42:55', '2021-01-07 23:35:49', NULL),
(3, 'Fridge', 'fridge', 1, '2021-01-07 23:35:54', '2021-01-07 23:35:54', NULL),
(4, 'Room Services', 'room-services', 1, '2021-01-07 23:43:10', '2021-01-07 23:36:12', NULL),
(5, '24hrs Hot and Cold water supply', '24hrs-hot-and-cold-water-supply', 1, '2021-01-08 08:44:52', '2021-01-08 08:44:52', NULL),
(6, 'Wifi', 'wifi', 1, '2021-01-08 08:45:03', '2021-01-08 08:45:03', NULL),
(7, 'Bar-services', 'barservices', 2, '2021-01-18 17:00:22', '2021-01-18 17:00:11', '2021-01-18 17:00:22'),
(8, 'Bar Services', 'bar-services', 2, '2021-02-05 17:52:34', '2021-01-18 17:00:32', '2021-02-06 06:22:34'),
(9, 'Laundry', 'laundry', 1, '2021-01-18 17:01:03', '2021-01-18 17:01:03', NULL),
(10, 'Breakfast', 'breakfast', 1, '2021-01-18 17:01:24', '2021-01-18 17:01:24', NULL),
(11, 'Tour Guide', 'tour-guide', 1, '2021-01-18 17:01:47', '2021-01-18 17:01:47', NULL),
(12, 'Medical Emergency', 'medical-emergency', 1, '2021-01-24 06:04:51', '2021-01-24 06:04:51', NULL),
(13, 'Swimming Guard', 'swimming-guard', 2, '2021-01-27 06:01:10', '2021-01-27 06:00:43', '2021-01-27 06:01:10'),
(14, 'Attached Kitchen', 'attached-kitchen', 1, '2021-01-31 07:38:37', '2021-01-31 07:38:37', NULL),
(15, 'Attached Bathroom', 'attached-bathroom', 1, '2021-01-31 07:39:00', '2021-01-31 07:39:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_category`
--

CREATE TABLE `room_category` (
  `roomcat_id` int(10) UNSIGNED NOT NULL,
  `roomcat_name` varchar(300) NOT NULL,
  `roomcat_slug` varchar(300) NOT NULL,
  `roomcat_smalldesc` text DEFAULT NULL,
  `roomcat_fulldesc` text DEFAULT NULL,
  `roomcat_price` int(11) NOT NULL,
  `roomcat_extrabed` int(11) DEFAULT NULL,
  `roomcat_tax` float(11,2) DEFAULT NULL,
  `roomcat_type` int(1) NOT NULL DEFAULT 1,
  `roomcat_adult` int(11) DEFAULT NULL,
  `roomcat_child` int(11) DEFAULT NULL,
  `roomcat_amenities` text DEFAULT NULL,
  `roomcat_thumb` text DEFAULT NULL,
  `roomcat_thumb_alt` text DEFAULT NULL,
  `roomcat_coverimg` text DEFAULT NULL,
  `roomcat_status` int(1) NOT NULL DEFAULT 1,
  `roomcat_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `roomcat_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `roomcat_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_category`
--

INSERT INTO `room_category` (`roomcat_id`, `roomcat_name`, `roomcat_slug`, `roomcat_smalldesc`, `roomcat_fulldesc`, `roomcat_price`, `roomcat_extrabed`, `roomcat_tax`, `roomcat_type`, `roomcat_adult`, `roomcat_child`, `roomcat_amenities`, `roomcat_thumb`, `roomcat_thumb_alt`, `roomcat_coverimg`, `roomcat_status`, `roomcat_update_at`, `roomcat_create_at`, `roomcat_delete_at`) VALUES
(1, 'Luxury Conference Halls', 'luxury-conference-halls', 'Luxury Conference Halls are the place for meet-ups for numerous people, converging all along for meetings, conferences and religious events. ', '<p><big>Luxury Conference Halls are the place for meet-ups for numerous people, converging all along for meetings, conferences and religious events. Numerous people or parties get together, to carry out events, e.g, Bhagawat Kathas. Halls are perfectly suited to accomodate a mass of people for carrying out their necessities, at the cheapest price available,&nbsp;not to mention about the air conditioned comfortable hall.&nbsp;</big></p>\r\n\r\n<p><big>We also provide kitchen facilities for the occupants, and hereby assure that the stay will be auspicious and luxurious.</big></p>\r\n', 21000, NULL, 18.33, 2, 500, 500, '2,4,5,6,9,10,11,12,14', 'luxury_conference_halls_1617884207952892.jpg', 'luxury hall', 'luxury_conference_halls_1612678666816363.jpg', 1, '2021-01-20 08:05:26', '2021-01-07 13:08:27', NULL),
(3, 'Premium Double Bed', 'premium-double-bed', 'These rooms are pretty luxurious and they are available at optimal prices.The colour texture of the room is brilliant along with all the requisite facilities. ', '<p>These rooms are pretty luxurious and they are available at optimal prices.The colour texture of the room is brilliant along with all the requisite facilities, hence, the rooms give you an allusion of luxury. Rooms are profoundly spacious, optimally compact with a king sized bed perfectly suitable for 2 adults and a child to have plenty of luxury.<br />\r\nThese rooms also get you the view of the Jagannath temple and the Grand Road.</p>\r\n', 1999, 699, NULL, 2, 2, 2, '2,4,5,6,9,10,15', 'premium_double_bed_1617884354451440.jpg', 'Double Bed Luxurious Room', 'premium_double_bed_1614279478568771.jpg', 1, '2021-01-13 09:45:21', '2021-01-07 13:17:14', NULL),
(5, 'Standard Double Bed', 'standard-double-bed', 'Standard Triple bed rooms contain all the minimum necessities for accommodating  2-3 adults including children, with profound space for a comfortable stay.', '<p>Standard Triple bed rooms contain all the minimum necessities for accommodating&nbsp; 2-3&nbsp;adults including children, with profound space for a comfortable stay.</p>\r\n', 649, 299, NULL, 1, 2, 2, '4,6,9,10,15', 'standard_double_bed_1617884607501065.jpg', 'Standard Double Bed', 'standard_double_bed_1612969345895676.jpg', 1, '2021-01-13 09:40:46', '2021-01-07 13:39:05', NULL),
(7, 'Standard Triple Bed', 'standard-triple-bed', 'Standard Triple bed rooms contain all the minimum necessities for accommodating  3-4 adults, with profound space for a comfortable stay.', '<p><big>Standard Triple bed rooms contain all the minimum necessities for accommodating &nbsp;3-4 adults, with profound space for a comfortable stay.</big></p>\r\n', 849, 399, 18.06, 1, 4, 2, '4,5,6,9,12,15', 'standard_triple_bed_1612968828721840.jpg', 'Standard Triple Bed', 'standard_triple_bed_161296882877473.jpg', 1, '2021-01-13 09:55:55', '2021-01-10 12:06:35', NULL),
(9, 'Deluxe Double Bed', 'deluxe-double-bed', 'Deluxe Double Bed room is perfectly suitable for people who want a cozy stay at an subsidised rate. The hospitality guests get is second to none. Please check out the room photos', 'Deluxe Double Bed room is perfectly suitable for people who want a cozy stay at an subsidised rate. The hospitality guests get is second to none. The room is spacious enough to accommodate 2-3 adults including children.\r\n', 949, 299, 18.03, 2, 2, 2, '4,5,6,9,10,15', 'deluxe_double_bed_1618044400604734.jpg', 'Deluxe Double Bed room', 'deluxe_double_bed_1617960379447998.jpg', 1, '2021-01-19 12:25:03', '2021-01-19 12:25:03', NULL),
(10, 'Deluxe Triple Bed', 'deluxe-triple-bed', 'Deluxe Triple Bed room is perfectly suitable for people who want a cozy stay at an subsidised rate. The hospitality guests get is second to none. Please check out the room pictures for details. ', '<p><big>Deluxe Triple Bed room is perfectly suitable for people who want a cozy stay at an subsidised rate. The hospitality guests get is second to none. The room is spacious enough to accommodate 3-4 adults, including children, not to mention&nbsp;the guests can add beds, if the numbers are a little higher. If you are looking for a spacious room with perfect comfort and cushion for your stay, this the place you need to book.</big></p>\r\n', 1249, 299, 11.00, 2, 3, 2, '2,3,4,5,6,9,10,15', 'deluxe_triple_bed_1617884836983620.jpg', 'Deluxe Triple Bed', 'deluxe_triple_bed_1612970321592454.jpg', 1, '2021-01-23 11:38:44', '2021-01-23 11:38:44', NULL),
(11, 'Conference Halls', 'conference-halls', 'Conference Halls are the place for meet-ups for numerous people, converging all along for meetings, conferences and religious events', '<p><big>Conference Halls are the place for meet-ups for numerous people, converging all along for meetings, conferences and religious events. Numerous people or parties get together, to carry out events, e.g, Bhagawat Kathas. Halls are perfectly suited to accomodate a mass of people for carrying out their necessities, at the cheapest price available.</big></p>\r\n\r\n<p><big>We also provide kitchen facilities for the occupants, and hereby assure that the stay will be auspicious and luxurious.</big></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n', 10999, NULL, 18.00, 1, 500, NULL, '2,5,6,9,11,12,14,15', 'conference_halls_1617884870103287.jpg', 'Conference Halls', 'conference_halls_1612679000287675.jpg', 1, '2021-01-31 07:35:30', '2021-01-31 07:35:30', NULL),
(12, 'Premium Triple Bed', 'premium-triple-bed', 'These rooms are pretty luxurious and they are available at optimal prices. Perfectly spacious to accommodate 3-4 adults.', '<p><big>These rooms are pretty luxurious and they are available at optimal prices and quite spacious to accommodate adequate number of people for three beds. The colour texture of the room is brilliant along with all the requisite facilities, hence, the rooms give you an allusion of luxury. Rooms are profoundly spacious, optimally compact with a king sized bed perfectly suitable for 3-4&nbsp;adults including children to have plenty of luxury.</big></p>\r\n', 2499, 699, NULL, 2, 3, 1, '2,4,5,6,9,10,12,15', 'premium_triple_bed_1617884438102633.jpg', 'Luxurious Triple Bed', 'premium_triple_bed_1612965794132645.jpg', 1, '2021-02-10 07:55:08', '2021-02-10 07:55:08', NULL),
(13, 'Family Rooms', 'family-rooms', 'Family Rooms, as the very name suggests, are mainly for accommodating families or parties where the guest numbers are high, roughly 15 odd people.', '<p><big>Family Rooms, as the very name suggests,&nbsp;are mainly for accommodating families or parties where the guest numbers are high, roughly 15 odd people. These rooms are well equipped with the standard ammentites for a cozy and stable stay at the hotel.&nbsp;</big></p>\r\n', 1999, NULL, NULL, 1, 15, 7, '5,15', NULL, NULL, NULL, 1, '2021-02-10 15:56:05', '2021-02-10 15:56:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_fac`
--

CREATE TABLE `room_fac` (
  `rfac_id` int(10) UNSIGNED NOT NULL,
  `rfac_roomcat_id_ref` int(11) UNSIGNED NOT NULL,
  `rfac_fac_id_ref` int(10) UNSIGNED NOT NULL,
  `rfac_qty` text DEFAULT NULL,
  `rfac_status` int(1) NOT NULL DEFAULT 1,
  `rfac_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rfac_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rfac_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_fac`
--

INSERT INTO `room_fac` (`rfac_id`, `rfac_roomcat_id_ref`, `rfac_fac_id_ref`, `rfac_qty`, `rfac_status`, `rfac_update_at`, `rfac_create_at`, `rfac_delete_at`) VALUES
(1, 1, 2, '4', 1, '2021-02-28 07:51:45', '2021-02-28 07:51:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_facility`
--

CREATE TABLE `room_facility` (
  `fac_id` int(10) UNSIGNED NOT NULL,
  `fac_name` varchar(300) NOT NULL,
  `fac_slug` varchar(300) DEFAULT NULL,
  `fac_status` int(1) NOT NULL DEFAULT 1,
  `fac_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fac_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fac_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_facility`
--

INSERT INTO `room_facility` (`fac_id`, `fac_name`, `fac_slug`, `fac_status`, `fac_update_at`, `fac_create_at`, `fac_delete_at`) VALUES
(1, 'Fan', 'fan', 1, '2021-01-09 08:26:38', '2021-01-09 08:24:39', NULL),
(2, 'Table', 'table', 1, '2021-01-09 08:26:39', '2021-01-09 08:24:50', NULL),
(3, 'Chair', 'chair', 1, '2021-01-09 08:26:39', '2021-01-09 08:24:53', '2021-01-09 08:26:00'),
(4, 'Electric Kettle', 'electric-kettle', 2, '2021-01-27 06:00:11', '2021-01-09 08:25:13', '2021-01-27 06:00:11'),
(5, 'Light', 'light', 1, '2021-01-27 05:59:58', '2021-01-09 08:25:26', NULL),
(6, 'Extension Cord', 'extension-cord', 1, '2021-01-09 17:55:59', '2021-01-09 17:55:59', NULL),
(7, 'Immersion Rod', 'immersion-rod', 0, '2021-01-31 09:24:19', '2021-01-27 06:00:28', NULL),
(8, 'Mic', 'mic', 1, '2021-02-05 17:52:50', '2021-01-31 07:39:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_gallery`
--

CREATE TABLE `room_gallery` (
  `rg_id` int(10) UNSIGNED NOT NULL,
  `rg_roomcat_id_ref` int(11) UNSIGNED NOT NULL,
  `rg_img_lg` text NOT NULL,
  `rg_img_md` text NOT NULL,
  `rg_img_name` text DEFAULT NULL,
  `rg_img_alt` text DEFAULT NULL,
  `rg_img_order` int(11) NOT NULL DEFAULT 1,
  `rg_img_status` int(1) NOT NULL DEFAULT 1,
  `rg_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rg_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rg_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_gallery`
--

INSERT INTO `room_gallery` (`rg_id`, `rg_roomcat_id_ref`, `rg_img_lg`, `rg_img_md`, `rg_img_name`, `rg_img_alt`, `rg_img_order`, `rg_img_status`, `rg_update_at`, `rg_create_at`, `rg_delete_at`) VALUES
(1, 9, 'deluxe_double_bed_16179581514125936_lg.jpg', 'deluxe_double_bed_16179581516263512_md.jpg', NULL, NULL, 1, 1, '2021-04-09 08:49:11', '2021-04-09 08:49:11', NULL),
(2, 9, 'deluxe_double_bed_16179581661816841_lg.jpg', 'deluxe_double_bed_16179581663091159_md.jpg', NULL, NULL, 1, 1, '2021-04-09 08:49:26', '2021-04-09 08:49:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_list`
--

CREATE TABLE `room_list` (
  `room_id` int(10) UNSIGNED NOT NULL,
  `room_roomcat_id_ref` int(11) UNSIGNED NOT NULL,
  `room_no` varchar(300) NOT NULL,
  `room_status` int(1) NOT NULL DEFAULT 1,
  `room_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_list`
--

INSERT INTO `room_list` (`room_id`, `room_roomcat_id_ref`, `room_no`, `room_status`, `room_create_at`, `room_update_at`, `room_delete_at`) VALUES
(1, 5, '101', 2, '2021-01-20 06:23:19', '2021-01-20 07:53:51', '2021-01-20 07:53:51'),
(2, 3, '203', 2, '2021-01-20 06:23:28', '2021-01-20 07:50:02', '2021-02-13 08:07:24'),
(3, 5, '103', 2, '2021-01-20 06:23:39', '2021-01-20 07:54:13', '2021-02-13 08:06:51'),
(4, 1, '001', 1, '2021-01-20 06:23:48', '2021-01-20 07:53:37', NULL),
(5, 5, '106', 2, '2021-01-20 10:24:00', '2021-01-20 10:24:00', '2021-02-13 08:07:10'),
(6, 5, '105', 2, '2021-01-20 10:24:09', '2021-01-20 10:24:09', '2021-02-13 08:07:07'),
(7, 5, '104', 2, '2021-01-20 10:24:26', '2021-01-20 10:24:26', '2021-02-13 08:07:05'),
(8, 5, '102', 2, '2021-01-20 10:25:06', '2021-01-20 10:25:06', '2021-02-13 08:06:49'),
(9, 5, '107', 2, '2021-01-20 10:25:15', '2021-01-20 10:25:15', '2021-02-13 08:07:13'),
(10, 3, '204', 2, '2021-01-20 10:26:05', '2021-01-20 10:26:05', '2021-02-13 08:07:29'),
(11, 3, '201', 2, '2021-01-20 10:26:13', '2021-01-20 10:26:13', '2021-02-13 08:07:17'),
(12, 5, '108', 2, '2021-01-20 10:29:20', '2021-01-20 10:29:20', '2021-02-13 08:07:15'),
(13, 3, '202', 2, '2021-01-20 10:29:55', '2021-01-20 10:29:55', '2021-02-13 08:07:21'),
(14, 10, '503', 2, '2021-01-24 05:54:14', '2021-01-24 05:54:14', '2021-02-13 08:07:42'),
(15, 3, '417', 1, '2021-01-24 05:54:16', '2021-01-24 05:54:16', NULL),
(16, 10, '504', 2, '2021-01-24 05:54:23', '2021-01-24 05:54:23', '2021-02-13 08:07:43'),
(17, 10, '501', 2, '2021-01-24 05:57:05', '2021-01-24 05:57:05', '2021-02-13 08:07:31'),
(18, 1, '003', 2, '2021-01-24 06:02:22', '2021-01-24 06:02:22', '2021-02-13 08:05:32'),
(19, 1, '004', 2, '2021-01-24 06:02:28', '2021-01-24 06:02:28', '2021-02-13 08:05:55'),
(26, 11, '008', 2, '2021-01-31 07:41:44', '2021-01-31 07:41:44', '2021-02-13 08:05:43'),
(27, 11, '009', 2, '2021-01-31 07:41:49', '2021-01-31 07:41:49', '2021-02-13 08:05:47'),
(28, 11, '006', 2, '2021-01-31 07:41:54', '2021-01-31 07:41:54', '2021-02-13 08:05:38'),
(29, 11, '002', 1, '2021-01-31 07:42:03', '2021-01-31 07:42:03', NULL),
(30, 3, '418', 1, '2021-02-12 19:38:39', '2021-02-12 19:38:39', NULL),
(31, 12, '419', 1, '2021-02-12 19:39:11', '2021-02-12 19:39:11', NULL),
(32, 3, '420', 1, '2021-02-12 19:39:23', '2021-02-12 19:39:23', NULL),
(33, 3, '421', 1, '2021-02-12 19:39:34', '2021-02-12 19:39:34', NULL),
(34, 3, '422', 1, '2021-02-12 19:39:54', '2021-02-12 19:39:54', NULL),
(35, 3, '423', 1, '2021-02-12 19:41:31', '2021-02-12 19:41:31', NULL),
(36, 3, '424', 1, '2021-02-12 19:41:40', '2021-02-12 19:41:40', NULL),
(37, 3, '425', 1, '2021-02-12 19:41:51', '2021-02-12 19:41:51', NULL),
(38, 3, '426', 1, '2021-02-12 19:42:04', '2021-02-12 19:42:04', NULL),
(39, 3, '427', 1, '2021-02-12 19:42:12', '2021-02-12 19:42:12', NULL),
(40, 3, '519', 1, '2021-02-12 19:42:38', '2021-02-12 19:42:38', NULL),
(41, 3, '520', 1, '2021-02-12 19:42:49', '2021-02-12 19:42:49', NULL),
(42, 12, '521', 1, '2021-02-12 19:43:01', '2021-02-12 19:43:01', NULL),
(43, 3, '522', 1, '2021-02-12 19:43:15', '2021-02-12 19:43:15', NULL),
(44, 3, '523', 1, '2021-02-12 19:43:27', '2021-02-12 19:43:27', NULL),
(45, 3, '524', 1, '2021-02-12 19:43:34', '2021-02-12 19:43:34', NULL),
(46, 3, '525', 1, '2021-02-12 19:43:40', '2021-02-12 19:43:40', NULL),
(47, 3, '526', 1, '2021-02-12 19:43:51', '2021-02-12 19:43:51', NULL),
(48, 3, '527', 1, '2021-02-12 19:44:10', '2021-02-12 19:44:10', NULL),
(49, 3, '528', 1, '2021-02-12 19:44:16', '2021-02-12 19:44:16', NULL),
(50, 3, '529', 1, '2021-02-12 19:44:23', '2021-02-12 19:44:23', NULL),
(51, 5, '201', 1, '2021-02-14 18:09:22', '2021-02-14 18:09:22', NULL),
(52, 5, '202', 1, '2021-02-14 18:09:28', '2021-02-14 18:09:28', NULL),
(53, 5, '203', 1, '2021-02-14 18:10:13', '2021-02-14 18:10:13', NULL),
(54, 10, '204', 1, '2021-02-14 18:10:32', '2021-02-14 18:10:32', NULL),
(55, 9, '205', 1, '2021-02-14 18:10:48', '2021-02-14 18:10:48', NULL),
(56, 9, '206', 1, '2021-02-14 18:10:55', '2021-02-14 18:10:55', NULL),
(57, 9, '207', 1, '2021-02-14 18:11:03', '2021-02-14 18:11:03', NULL),
(58, 10, '208', 1, '2021-02-14 18:11:16', '2021-02-14 18:11:16', NULL),
(59, 9, '209', 1, '2021-02-14 18:12:27', '2021-02-14 18:12:27', NULL),
(60, 10, '210', 1, '2021-02-14 18:12:32', '2021-02-14 18:12:32', NULL),
(61, 10, '211', 1, '2021-02-14 18:12:38', '2021-02-14 18:12:38', NULL),
(62, 10, '212', 1, '2021-02-14 18:12:43', '2021-02-14 18:12:43', NULL),
(63, 10, '213', 1, '2021-02-14 18:12:47', '2021-02-14 18:12:47', NULL),
(64, 10, '214', 1, '2021-02-14 18:12:53', '2021-02-14 18:12:53', NULL),
(65, 10, '215', 1, '2021-02-14 18:12:59', '2021-02-14 18:12:59', NULL),
(66, 13, '216', 1, '2021-02-14 18:13:11', '2021-02-14 18:13:11', NULL),
(67, 9, '301', 1, '2021-02-14 19:02:18', '2021-02-14 19:02:18', NULL),
(68, 9, '302', 1, '2021-02-14 19:02:58', '2021-02-14 19:02:58', NULL),
(69, 9, '303', 1, '2021-02-14 19:03:05', '2021-02-14 19:03:05', NULL),
(70, 9, '304', 1, '2021-02-14 19:03:09', '2021-02-14 19:03:09', NULL),
(71, 10, '305', 1, '2021-02-14 19:03:15', '2021-02-14 19:03:15', NULL),
(72, 10, '306', 1, '2021-02-14 19:03:20', '2021-02-14 19:03:20', NULL),
(73, 10, '307', 1, '2021-02-14 19:03:25', '2021-02-14 19:03:25', NULL),
(74, 9, '308', 1, '2021-02-14 19:03:34', '2021-02-14 19:03:34', NULL),
(75, 9, '309', 1, '2021-02-14 19:03:45', '2021-02-14 19:03:45', NULL),
(76, 9, '310', 1, '2021-02-14 19:03:51', '2021-02-14 19:03:51', NULL),
(77, 9, '311', 1, '2021-02-14 19:03:55', '2021-02-14 19:03:55', NULL),
(78, 9, '312', 1, '2021-02-14 19:04:02', '2021-02-14 19:04:02', NULL),
(79, 10, '313', 1, '2021-02-14 19:06:01', '2021-02-14 19:06:01', NULL),
(80, 9, '314', 1, '2021-02-14 19:06:08', '2021-02-14 19:06:08', NULL),
(81, 9, '315', 1, '2021-02-14 19:06:15', '2021-02-14 19:06:15', NULL),
(82, 13, '316', 1, '2021-02-14 19:06:24', '2021-02-14 19:06:24', NULL),
(83, 10, '317', 1, '2021-02-14 19:06:33', '2021-02-14 19:06:33', NULL),
(84, 10, '318', 1, '2021-02-14 19:06:39', '2021-02-14 19:06:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testi_id` int(10) UNSIGNED NOT NULL,
  `testi_name` text NOT NULL,
  `testi_text` text NOT NULL,
  `testi_img` text DEFAULT NULL,
  `testi_status` int(1) NOT NULL DEFAULT 1,
  `testi_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `testi_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `testi_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testi_id`, `testi_name`, `testi_text`, `testi_img`, `testi_status`, `testi_create_at`, `testi_update_at`, `testi_delete_at`) VALUES
(1, 'Suprotive Sarkar', 'Lorem Ipsun bla bla bla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla blabla bla', 'suprotive_sarkar_1610181882336099120.png', 2, '2021-01-09 08:40:58', '2021-02-25 10:36:44', '2021-02-25 10:36:44'),
(2, 'Demo Name', 'demo demo demo demo demo demo demo demo demo demo demo demo demo demo demo demo', NULL, 2, '2021-01-09 08:47:08', '2021-02-25 10:36:42', '2021-02-25 10:36:42'),
(3, 'Xyz Name', 'ndsnf hehrf iwehwewi hweh ieweeh wrhg q w jqe  e owehwruvge ewgwh gbqe ouhrgh qbwir guqwhri uq mwigweh weu iuqe', 'xyz_name_1610531387867578585.jpg', 2, '2021-01-13 07:25:49', '2021-02-25 10:36:39', '2021-02-25 10:36:39'),
(4, 'Suprotive Sarkar', 'jdskcnkm fkndfanfn sgjgsjng jkdsnk js nsdn nsnfn kjn knvdskn slvnkjb kjl nsho kja lmdfknb jkjsn jop', 'suprotive_sarkar_1614284902665317064.jpg', 2, '2021-02-25 20:27:37', '2021-02-25 20:28:34', '2021-02-25 20:28:34'),
(5, 'Suprotive Sarkar', 'Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem Ipsum', 'suprotive_sarkar_1614410887316428722.jpg', 2, '2021-02-27 07:28:07', '2021-02-27 07:28:24', '2021-02-27 07:28:24'),
(6, 'S Sarkar', 'dvdfbfgn rthrj rnj etjetj rsy rysj dvdfbfgn rthrj rnj etjetj rsy rysj dvdfbfgn rthrj rnj etjetj rsy rysj dvdfbfgn rthrj rnj etjetj rsy rysj dvdfbfgn rthrj rnj etjetj rsy rysj dvdfbfgn rthrj rnj etjetj rsy rysj', 's_sarkar_161803828599457343.png', 2, '2021-04-10 07:04:45', '2021-04-10 07:09:37', '2021-04-10 07:09:37'),
(7, 'Suprotive Sarkar', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maiores sed obcaecati, magni excepturi, temporibus vero, inventore tenetur assumenda natus sequi labore. Voluptates eligendi dolores quod temporibus aperiam adipisci, quasi reprehenderit.', 'suprotive_sarkar_1618038823636653417.png', 1, '2021-04-10 07:10:08', '2021-04-10 07:31:08', NULL),
(8, 'MAMA', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maiores sed obcaecati, magni excepturi, temporibus vero, inventore tenetur assumenda natus sequi labore. Voluptates eligendi dolores quod temporibus aperiam adipisci, quasi reprehenderit.', 'demo_name_demo_name_1618038815557261557.jpg', 1, '2021-04-10 07:11:25', '2022-06-07 08:21:22', NULL),
(9, 'Rohit Das', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maiores sed obcaecati, magni excepturi, temporibus vero, inventore tenetur assumenda natus sequi labore. Voluptates eligendi dolores quod temporibus aperiam adipisci, quasi reprehenderit.', 'a_sarkar_1618038868552372431.png', 1, '2021-04-10 07:14:28', '2022-06-07 08:21:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_list`
--

CREATE TABLE `users_list` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_role_id_ref` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_cat_id_ref` int(10) UNSIGNED NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_email` text DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `user_dob` date DEFAULT NULL,
  `user_join` date DEFAULT NULL,
  `user_img_name` varchar(255) NOT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `user_salary` float(10,2) NOT NULL,
  `user_leaving` date DEFAULT NULL,
  `user_status` int(1) NOT NULL DEFAULT 1,
  `user_canlogin` int(1) NOT NULL DEFAULT 0,
  `user_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_list`
--

INSERT INTO `users_list` (`user_id`, `user_role_id_ref`, `user_name`, `user_code`, `user_password`, `user_cat_id_ref`, `user_phone`, `user_email`, `user_address`, `user_dob`, `user_join`, `user_img_name`, `user_img`, `user_salary`, `user_leaving`, `user_status`, `user_canlogin`, `user_create_at`, `user_update_at`, `user_delete_at`) VALUES
(1, 1, 'hotel', '000', '123456', 1, '9064389417', 'ss@gmail.com', 'demo address', '0000-00-00', '0000-00-00', '', NULL, 0.00, NULL, 1, 1, '2021-01-23 11:04:21', '2021-01-23 11:04:21', '2021-02-28 17:20:01'),
(2, 3, 'Abhijit chatterjee', '001', '2012india', 20, '8372078007', 'abhi111@gmail.com', 'Tarakeshwar', '0000-00-00', '0000-00-00', '', 'abhijit_chatterjee_1654588199917383.jpg', 20000.00, NULL, 1, 1, '2022-06-07 07:50:00', '2022-06-07 07:50:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_role`
--

CREATE TABLE `users_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_status` int(1) NOT NULL DEFAULT 1,
  `role_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_role`
--

INSERT INTO `users_role` (`role_id`, `role_name`, `role_status`, `role_create_at`, `role_update_at`, `role_delete_at`) VALUES
(1, 'Super admin', 1, '2021-01-23 10:08:45', '2021-01-23 10:09:19', NULL),
(2, 'Admin', 1, '2021-01-23 10:09:08', '2021-01-23 10:09:08', NULL),
(3, 'Reception', 1, '2021-01-23 10:09:08', '2021-01-23 10:16:08', NULL),
(4, 'Others', 1, '2021-01-23 11:02:45', '2021-01-23 11:02:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `activity_id` int(10) UNSIGNED NOT NULL,
  `activity_user_name` text NOT NULL,
  `activity_user_password` text NOT NULL,
  `activity_status` int(1) NOT NULL DEFAULT 1 COMMENT '0-Unable to login/1-Logedin',
  `activity_create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `activity_update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `activity_delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`activity_id`, `activity_user_name`, `activity_user_password`, `activity_status`, `activity_create_at`, `activity_update_at`, `activity_delete_at`) VALUES
(1, '9064389417', '123456', 2, '2021-01-25 13:54:44', '2021-01-26 07:44:38', '2021-01-26 07:44:38'),
(2, '9064389417', 'zdcvzdv', 0, '2021-01-25 13:55:38', '2021-01-25 13:55:38', NULL),
(3, '9064389417', '123456', 1, '2021-01-25 13:57:42', '2021-01-25 13:57:42', NULL),
(4, '9064389417', '123456', 2, '2021-01-26 05:18:15', '2021-01-27 07:51:51', '2021-01-27 07:51:51'),
(5, 'Suprotive', '123456', 1, '2021-01-26 07:00:30', '2021-01-26 07:00:30', NULL),
(6, '9064389417', 'dxghn dg', 0, '2021-01-26 07:31:48', '2021-01-26 07:31:48', NULL),
(7, '9064389417', '123456', 1, '2021-01-26 07:32:22', '2021-01-26 07:32:22', NULL),
(8, 'Suprotive', '123456', 1, '2021-01-27 05:53:59', '2021-01-27 05:53:59', NULL),
(9, 'Suprotive', '123456', 1, '2021-01-27 07:44:25', '2021-01-27 07:44:25', NULL),
(10, '9064389417', '123456', 1, '2021-01-27 13:47:48', '2021-01-27 13:47:48', NULL),
(11, 'Suprotive', '123456', 1, '2021-01-28 05:06:27', '2021-01-28 05:06:27', NULL),
(12, 'Suprotive', '123456', 1, '2021-01-28 07:54:21', '2021-01-28 07:54:21', NULL),
(13, 'Suprotive', '123456', 1, '2021-01-28 10:22:32', '2021-01-28 10:22:32', NULL),
(14, '9064389417', '123456', 1, '2021-01-29 05:14:02', '2021-01-29 05:14:02', NULL),
(15, '9064389417', '123456', 1, '2021-01-29 07:22:44', '2021-01-29 07:22:44', NULL),
(16, '9064389417', '123456', 1, '2021-01-30 05:30:53', '2021-01-30 05:30:53', NULL),
(17, '9064389417', '123456', 1, '2021-01-30 06:00:07', '2021-01-30 06:00:07', NULL),
(18, 'Suprotive', '123456', 1, '2021-01-30 07:46:14', '2021-01-30 07:46:14', NULL),
(19, 'Suprotive', '12345', 0, '2021-01-31 06:23:15', '2021-01-31 06:23:15', NULL),
(20, '9064389417', '123456', 1, '2021-01-31 06:23:37', '2021-01-31 06:23:37', NULL),
(21, 'Suprotive', '123456', 1, '2021-01-31 09:05:17', '2021-01-31 09:05:17', NULL),
(22, 'Suprotive', '123456', 1, '2021-01-31 15:39:52', '2021-01-31 15:39:52', NULL),
(23, 'Suprotive', '123456', 1, '2021-02-01 05:24:45', '2021-02-01 05:24:45', NULL),
(24, 'Suprotive', '123456', 1, '2021-02-01 07:14:45', '2021-02-01 07:14:45', NULL),
(25, 'Suprotive', '123456', 1, '2021-02-01 21:51:47', '2021-02-01 21:51:47', NULL),
(26, 'hotel', '123456', 0, '2021-02-02 18:59:11', '2021-02-02 18:59:11', NULL),
(27, 'hotel', '123456', 0, '2021-02-02 19:00:02', '2021-02-02 19:00:02', NULL),
(28, 'hotel', '123456', 1, '2021-02-02 19:00:38', '2021-02-02 19:00:38', NULL),
(29, 'hotel', '123456', 1, '2021-02-02 19:07:20', '2021-02-02 19:07:20', NULL),
(30, 'hotel', '123456', 1, '2021-02-02 19:53:51', '2021-02-02 19:53:51', NULL),
(31, 'hotel', '123456', 1, '2021-02-02 21:16:32', '2021-02-02 21:16:32', NULL),
(32, 'hotel', '123456', 1, '2021-02-03 17:43:19', '2021-02-03 17:43:19', NULL),
(33, 'hotel', '123456', 1, '2021-02-03 20:31:06', '2021-02-03 20:31:06', NULL),
(34, 'hotel', '123456', 1, '2021-02-03 21:32:20', '2021-02-03 21:32:20', NULL),
(35, 'hotel', '123456', 1, '2021-02-06 06:21:06', '2021-02-06 06:21:06', NULL),
(36, 'hotel', '123456', 1, '2021-02-07 16:55:23', '2021-02-07 16:55:23', NULL),
(37, 'hotel', '123456', 1, '2021-02-07 18:04:40', '2021-02-07 18:04:40', NULL),
(38, 'hotel', '123456', 1, '2021-02-09 00:41:07', '2021-02-09 00:41:07', NULL),
(39, 'A B Sarkar', '123456', 1, '2021-02-09 01:13:17', '2021-02-09 01:13:17', NULL),
(40, 'H Haldar', '789456', 1, '2021-02-09 01:13:59', '2021-02-09 01:13:59', NULL),
(41, 'hotel', '123456', 1, '2021-02-09 01:14:44', '2021-02-09 01:14:44', NULL),
(42, 'D Das', '789456', 0, '2021-02-09 01:20:02', '2021-02-09 01:20:02', NULL),
(43, 'H Haldar', '789456', 1, '2021-02-09 01:20:21', '2021-02-09 01:20:21', NULL),
(44, 'A B Sarkar', '123456', 1, '2021-02-09 01:21:01', '2021-02-09 01:21:01', NULL),
(45, 'hotel', '123456', 1, '2021-02-09 01:23:46', '2021-02-09 01:23:46', NULL),
(46, 'hotel', '123456', 1, '2021-02-09 01:26:43', '2021-02-09 01:26:43', NULL),
(47, 'hotel', '123456', 1, '2021-02-09 01:39:37', '2021-02-09 01:39:37', NULL),
(48, 'hotel', '123456', 1, '2021-02-09 01:51:27', '2021-02-09 01:51:27', NULL),
(49, 'hotel', '123456', 1, '2021-02-09 21:49:47', '2021-02-09 21:49:47', NULL),
(50, 'hotel', '123456', 1, '2021-02-10 04:15:25', '2021-02-10 04:15:25', NULL),
(51, 'hotel', '123456', 1, '2021-02-10 19:10:04', '2021-02-10 19:10:04', NULL),
(52, 'hotel', '123456', 1, '2021-02-10 20:13:00', '2021-02-10 20:13:00', NULL),
(53, 'hotel', '123456', 1, '2021-02-11 02:25:32', '2021-02-11 02:25:32', NULL),
(54, 'hotel', '123456', 1, '2021-02-13 08:04:08', '2021-02-13 08:04:08', NULL),
(55, 'hotel', '123456', 1, '2021-02-13 22:41:25', '2021-02-13 22:41:25', NULL),
(56, 'hotel', '123456', 1, '2021-02-15 05:49:40', '2021-02-15 05:49:40', NULL),
(57, 'hotel', '123456', 1, '2021-02-15 06:28:08', '2021-02-15 06:28:08', NULL),
(58, 'hotel', '123456', 1, '2021-02-15 06:45:45', '2021-02-15 06:45:45', NULL),
(59, 'hotel', '123456', 1, '2021-02-15 07:05:06', '2021-02-15 07:05:06', NULL),
(60, 'hotel', '123456', 1, '2021-02-15 17:38:14', '2021-02-15 17:38:14', NULL),
(61, 'hotel', '123456', 1, '2021-02-18 07:09:13', '2021-02-18 07:09:13', NULL),
(62, 'hotel', '13456', 0, '2021-02-22 04:20:18', '2021-02-22 04:20:18', NULL),
(63, 'hotel', '123456', 1, '2021-02-22 04:20:33', '2021-02-22 04:20:33', NULL),
(64, 'hotel', '123456', 1, '2021-02-24 21:44:55', '2021-02-24 21:44:55', NULL),
(65, 'hotel', '123456', 1, '2021-02-25 00:17:41', '2021-02-25 00:17:41', NULL),
(66, 'hotel', '123456', 1, '2021-02-25 06:59:42', '2021-02-25 06:59:42', NULL),
(67, 'hotel', '123456', 1, '2021-02-25 16:48:46', '2021-02-25 16:48:46', NULL),
(68, 'hotel', '123456', 1, '2021-02-25 22:29:33', '2021-02-25 22:29:33', NULL),
(69, 'hotel', '123456', 1, '2021-02-25 22:31:16', '2021-02-25 22:31:16', NULL),
(70, 'hotel', '123456', 1, '2021-02-25 23:06:31', '2021-02-25 23:06:31', NULL),
(71, 'hotel', '123456', 1, '2021-02-26 07:01:47', '2021-02-26 07:01:47', NULL),
(72, 'hotel', '123456', 1, '2021-02-26 08:15:55', '2021-02-26 08:15:55', NULL),
(73, 'hotel', '123456', 1, '2021-02-26 08:56:49', '2021-02-26 08:56:49', NULL),
(74, 'hotel', '123456', 1, '2021-02-26 09:37:20', '2021-02-26 09:37:20', NULL),
(75, 'hotel', '123456', 1, '2021-02-26 19:01:29', '2021-02-26 19:01:29', NULL),
(76, 'hotel', '123456', 1, '2021-02-27 04:26:32', '2021-02-27 04:26:32', NULL),
(77, 'H Halder', '789456', 0, '2021-02-27 04:35:06', '2021-02-27 04:35:06', NULL),
(78, 'H Halder', '789456', 0, '2021-02-27 04:35:26', '2021-02-27 04:35:26', NULL),
(79, 'H Haldar', '789456', 1, '2021-02-27 04:35:48', '2021-02-27 04:35:48', NULL),
(80, 'hotel', '123456', 1, '2021-02-27 04:36:20', '2021-02-27 04:36:20', NULL),
(81, 'hotel', '123456', 1, '2021-02-27 04:41:47', '2021-02-27 04:41:47', NULL),
(82, 'hotel', '123456', 1, '2021-02-27 19:01:15', '2021-02-27 19:01:15', NULL),
(83, 'hotel', '123456', 1, '2021-02-27 19:43:55', '2021-02-27 19:43:55', NULL),
(84, 'hotel', '123456', 1, '2021-02-27 20:37:23', '2021-02-27 20:37:23', NULL),
(85, 'hotel', '123456', 1, '2021-02-28 00:02:01', '2021-02-28 00:02:01', NULL),
(86, 'hotel', '123456', 1, '2021-02-28 19:22:14', '2021-02-28 19:22:14', NULL),
(87, 'H Haldar', '789456', 1, '2021-02-28 19:50:36', '2021-02-28 19:50:36', NULL),
(88, 'hotel', '123456', 1, '2021-02-28 19:54:16', '2021-02-28 19:54:16', NULL),
(89, 'hotel', '123456', 1, '2021-03-01 05:46:33', '2021-03-01 05:46:33', NULL),
(90, 'hotel', '123456', 1, '2021-03-01 19:25:08', '2021-03-01 19:25:08', NULL),
(91, 'hotel', '123456', 1, '2021-03-01 19:27:22', '2021-03-01 19:27:22', NULL),
(92, 'hotel', '123456', 1, '2021-03-01 19:28:47', '2021-03-01 19:28:47', NULL),
(93, 'hotel', '123456', 1, '2021-03-01 19:31:51', '2021-03-01 19:31:51', NULL),
(94, 'hotel', '123456', 1, '2021-03-01 19:37:50', '2021-03-01 19:37:50', NULL),
(95, 'hotel', '123456', 1, '2021-03-01 19:38:29', '2021-03-01 19:38:29', NULL),
(96, 'hotel', '123456', 1, '2021-03-01 19:58:57', '2021-03-01 19:58:57', NULL),
(97, 'aditguru186@gmail.com', 'Aditya@12345g', 2, '2021-03-01 20:30:43', '2021-03-01 08:06:06', '2021-03-01 08:06:06'),
(98, 'hotel', '123456', 1, '2021-03-01 20:31:01', '2021-03-01 20:31:01', NULL),
(99, 'hotel', '123456', 1, '2021-03-01 20:44:17', '2021-03-01 20:44:17', NULL),
(100, 'hotel', '123456', 1, '2021-03-02 07:02:45', '2021-03-02 07:02:45', NULL),
(101, 'hotel', '123456', 1, '2021-03-02 18:34:25', '2021-03-02 18:34:25', NULL),
(102, 'hotel', '123456', 1, '2021-03-02 18:36:44', '2021-03-02 18:36:44', NULL),
(103, 'hotel', '123456', 1, '2021-03-02 19:44:28', '2021-03-02 19:44:28', NULL),
(104, 'hotel', '123456', 1, '2021-03-02 20:17:44', '2021-03-02 20:17:44', NULL),
(105, 'hotel', '123456', 1, '2021-03-02 20:30:07', '2021-03-02 20:30:07', NULL),
(106, 'hotel', '123456', 1, '2021-03-03 05:31:53', '2021-03-03 05:31:53', NULL),
(107, 'hotel', '123456', 1, '2021-03-05 05:00:11', '2021-03-05 05:00:11', NULL),
(108, 'hotel', '123456', 1, '2021-03-06 06:09:43', '2021-03-06 06:09:43', NULL),
(109, 'hotel', '123456', 1, '2021-03-09 04:04:31', '2021-03-09 04:04:31', NULL),
(110, 'hotel', '123456', 1, '2021-03-10 00:38:29', '2021-03-10 00:38:29', NULL),
(111, 'hotel', '123456', 1, '2021-03-10 00:39:58', '2021-03-10 00:39:58', NULL),
(112, 'hotel', '123456', 1, '2021-03-10 00:44:12', '2021-03-10 00:44:12', NULL),
(113, 'hotel', '123456', 1, '2021-03-11 21:50:11', '2021-03-11 21:50:11', NULL),
(114, 'hotel', '123456', 1, '2021-03-18 18:14:49', '2021-03-18 18:14:49', NULL),
(115, 'hotel', '123456', 1, '2021-03-18 19:04:16', '2021-03-18 19:04:16', NULL),
(116, 'hotel', '123456', 1, '2021-03-18 19:53:27', '2021-03-18 19:53:27', NULL),
(117, 'hotel', '123456', 1, '2021-03-19 20:13:44', '2021-03-19 20:13:44', NULL),
(118, 'hotel', '123456', 1, '2021-03-20 00:58:19', '2021-03-20 00:58:19', NULL),
(119, 'hotel', '123456', 1, '2021-03-20 01:10:27', '2021-03-20 01:10:27', NULL),
(120, 'hotel', '123456', 1, '2021-03-20 02:51:39', '2021-03-20 02:51:39', NULL),
(121, 'hotel', '123456', 1, '2021-03-20 07:06:35', '2021-03-20 07:06:35', NULL),
(122, 'hotel', '123456', 1, '2021-03-22 04:31:05', '2021-03-22 04:31:05', NULL),
(123, 'hotel', '123456', 1, '2021-03-26 01:34:07', '2021-03-26 01:34:07', NULL),
(124, 'hotel', '123456', 1, '2021-03-31 00:33:51', '2021-03-31 00:33:51', NULL),
(125, 'hotel', '123456', 1, '2021-03-31 18:47:50', '2021-03-31 18:47:50', NULL),
(126, 'hotel', '123456', 1, '2021-04-04 21:10:52', '2021-04-04 21:10:52', NULL),
(127, 'hotel', '123456', 1, '2021-04-05 09:06:50', '2021-04-05 09:06:50', NULL),
(128, 'hotel', '123456', 1, '2021-04-08 08:30:59', '2021-04-08 08:30:59', NULL),
(129, 'hotel', '123456', 1, '2021-04-08 08:44:13', '2021-04-08 08:44:13', NULL),
(130, 'hotel', '123456', 1, '2021-04-08 10:56:30', '2021-04-08 10:56:30', NULL),
(131, 'hotel', '123456', 1, '2021-04-08 11:06:54', '2021-04-08 11:06:54', NULL),
(132, 'hotel', '123456', 1, '2021-04-08 12:14:32', '2021-04-08 12:14:32', NULL),
(133, 'hotel', '123456', 1, '2021-04-09 08:42:38', '2021-04-09 08:42:38', NULL),
(134, 'hotel', '123456', 1, '2021-04-10 07:03:26', '2021-04-10 07:03:26', NULL),
(135, 'hotel', '123456', 1, '2021-04-11 06:24:10', '2021-04-11 06:24:10', NULL),
(136, 'hotel', '123456', 1, '2021-04-11 09:34:24', '2021-04-11 09:34:24', NULL),
(137, 'hotel', '123456', 1, '2021-04-14 08:30:28', '2021-04-14 08:30:28', NULL),
(138, 'hotel', '123456', 1, '2022-03-10 06:50:44', '2022-03-10 06:50:44', NULL),
(139, 'hotel', '123456', 1, '2022-03-10 06:54:01', '2022-03-10 06:54:01', NULL),
(140, 'hotel', '123456', 1, '2022-05-16 04:19:36', '2022-05-16 04:19:36', NULL),
(141, 'hotel', '123456', 1, '2022-05-17 07:02:12', '2022-05-17 07:02:12', NULL),
(142, 'hotel', '123456', 1, '2022-05-28 05:38:55', '2022-05-28 05:38:55', NULL),
(143, 'rdas2147@gmail.com', '2012india', 0, '2022-05-28 07:12:31', '2022-05-28 07:12:31', NULL),
(144, 'hotel', '123456', 1, '2022-05-28 07:12:50', '2022-05-28 07:12:50', NULL),
(145, 'hotel', '123456', 1, '2022-06-07 07:43:19', '2022-06-07 07:43:19', NULL),
(146, 'hotel', '123456', 1, '2022-06-09 20:55:47', '2022-06-09 20:55:47', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`care_id`);

--
-- Indexes for table `emp_cat`
--
ALTER TABLE `emp_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `emp_doc`
--
ALTER TABLE `emp_doc`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `doc_user_id_ref` (`doc_user_id_ref`);

--
-- Indexes for table `emp_salary`
--
ALTER TABLE `emp_salary`
  ADD PRIMARY KEY (`sal_id`),
  ADD KEY `sal_emp_id_ref` (`sal_user_id_ref`),
  ADD KEY `sal_amount` (`sal_amount`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`enq_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gal_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`mem_id`);

--
-- Indexes for table `quick_contact`
--
ALTER TABLE `quick_contact`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `reservation_list`
--
ALTER TABLE `reservation_list`
  ADD PRIMARY KEY (`res_id`),
  ADD KEY `res_mem_id_ref` (`res_mem_id_ref`);

--
-- Indexes for table `reservation_pay`
--
ALTER TABLE `reservation_pay`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `reservation_pay_ibfk_1` (`pay_res_id_ref`),
  ADD KEY `pay_by` (`pay_by`);

--
-- Indexes for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  ADD PRIMARY KEY (`resrooms_id`),
  ADD KEY `resrooms_room_id_ref` (`resrooms_room_id_ref`),
  ADD KEY `resrooms_res_id_ref` (`resrooms_res_id_ref`);

--
-- Indexes for table `room_amenities`
--
ALTER TABLE `room_amenities`
  ADD PRIMARY KEY (`am_id`);

--
-- Indexes for table `room_category`
--
ALTER TABLE `room_category`
  ADD PRIMARY KEY (`roomcat_id`);

--
-- Indexes for table `room_fac`
--
ALTER TABLE `room_fac`
  ADD PRIMARY KEY (`rfac_id`),
  ADD KEY `rfac_roomcat_id_ref` (`rfac_roomcat_id_ref`),
  ADD KEY `rfac_fac_id_ref` (`rfac_fac_id_ref`);

--
-- Indexes for table `room_facility`
--
ALTER TABLE `room_facility`
  ADD PRIMARY KEY (`fac_id`);

--
-- Indexes for table `room_gallery`
--
ALTER TABLE `room_gallery`
  ADD PRIMARY KEY (`rg_id`);

--
-- Indexes for table `room_list`
--
ALTER TABLE `room_list`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `room_roomcat_id_ref` (`room_roomcat_id_ref`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testi_id`);

--
-- Indexes for table `users_list`
--
ALTER TABLE `users_list`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_role_id_ref` (`user_role_id_ref`),
  ADD KEY `user_cat_id_ref` (`user_cat_id_ref`);

--
-- Indexes for table `users_role`
--
ALTER TABLE `users_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `career`
--
ALTER TABLE `career`
  MODIFY `care_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emp_cat`
--
ALTER TABLE `emp_cat`
  MODIFY `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `emp_doc`
--
ALTER TABLE `emp_doc`
  MODIFY `doc_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_salary`
--
ALTER TABLE `emp_salary`
  MODIFY `sal_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `enq_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gal_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `mem_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `quick_contact`
--
ALTER TABLE `quick_contact`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservation_list`
--
ALTER TABLE `reservation_list`
  MODIFY `res_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservation_pay`
--
ALTER TABLE `reservation_pay`
  MODIFY `pay_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  MODIFY `resrooms_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `room_amenities`
--
ALTER TABLE `room_amenities`
  MODIFY `am_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `room_category`
--
ALTER TABLE `room_category`
  MODIFY `roomcat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `room_fac`
--
ALTER TABLE `room_fac`
  MODIFY `rfac_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_facility`
--
ALTER TABLE `room_facility`
  MODIFY `fac_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room_gallery`
--
ALTER TABLE `room_gallery`
  MODIFY `rg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_list`
--
ALTER TABLE `room_list`
  MODIFY `room_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users_list`
--
ALTER TABLE `users_list`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_role`
--
ALTER TABLE `users_role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `activity_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emp_doc`
--
ALTER TABLE `emp_doc`
  ADD CONSTRAINT `emp_doc_ibfk_1` FOREIGN KEY (`doc_user_id_ref`) REFERENCES `users_list` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emp_salary`
--
ALTER TABLE `emp_salary`
  ADD CONSTRAINT `emp_salary_ibfk_1` FOREIGN KEY (`sal_user_id_ref`) REFERENCES `users_list` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservation_list`
--
ALTER TABLE `reservation_list`
  ADD CONSTRAINT `reservation_list_ibfk_1` FOREIGN KEY (`res_mem_id_ref`) REFERENCES `members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservation_pay`
--
ALTER TABLE `reservation_pay`
  ADD CONSTRAINT `reservation_pay_ibfk_1` FOREIGN KEY (`pay_res_id_ref`) REFERENCES `reservation_list` (`res_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_pay_ibfk_2` FOREIGN KEY (`pay_by`) REFERENCES `users_list` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  ADD CONSTRAINT `reservation_rooms_ibfk_1` FOREIGN KEY (`resrooms_room_id_ref`) REFERENCES `room_list` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_rooms_ibfk_2` FOREIGN KEY (`resrooms_res_id_ref`) REFERENCES `reservation_list` (`res_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_fac`
--
ALTER TABLE `room_fac`
  ADD CONSTRAINT `room_fac_ibfk_1` FOREIGN KEY (`rfac_roomcat_id_ref`) REFERENCES `room_category` (`roomcat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_fac_ibfk_2` FOREIGN KEY (`rfac_fac_id_ref`) REFERENCES `room_facility` (`fac_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_list`
--
ALTER TABLE `room_list`
  ADD CONSTRAINT `room_list_ibfk_1` FOREIGN KEY (`room_roomcat_id_ref`) REFERENCES `room_category` (`roomcat_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_list`
--
ALTER TABLE `users_list`
  ADD CONSTRAINT `users_list_ibfk_1` FOREIGN KEY (`user_role_id_ref`) REFERENCES `users_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_list_ibfk_2` FOREIGN KEY (`user_cat_id_ref`) REFERENCES `emp_cat` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
