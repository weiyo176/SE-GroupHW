-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-01-07 12:01:23
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `hw3`
--

-- --------------------------------------------------------

--
-- 資料表結構 `mer_order`
--

CREATE TABLE `mer_order` (
  `gID` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `oID` int(11) NOT NULL,
  `cID` int(11) NOT NULL,
  `goods` varchar(20) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rating` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 傾印資料表的資料 `mer_order`
--

INSERT INTO `mer_order` (`gID`, `id`, `oID`, `cID`, `goods`, `price`, `amount`, `total`, `status`, `rating`) VALUES
(34, 2, 0, 1, 'banana', 20, 1, 20, '已送達', '4'),
(35, 1, 0, 1, 'apple', 20, 6, 60, '未處理', NULL);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `mer_order`
--
ALTER TABLE `mer_order`
  ADD PRIMARY KEY (`gID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `mer_order`
--
ALTER TABLE `mer_order`
  MODIFY `gID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
