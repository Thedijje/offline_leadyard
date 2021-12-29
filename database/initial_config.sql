-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 29, 2021 at 06:57 AM
-- Server version: 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `reevalo_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `admin_mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `password_token` varchar(255) NOT NULL,
  `front_user` int(11) NOT NULL COMMENT 'this will have a front end user id which would be replica of admin, used for chat purpose.',
  `last_login` varchar(15) NOT NULL,
  `admin_last_activity` varchar(15) NOT NULL,
  `added_on` varchar(15) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'site',
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='configuration settings of site';

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `type`, `name`, `value`) VALUES
(1, 'site', 'sitename', 'Reevalo - Smart Real Estate'),
(3, 'site', 'logo', 'public/front/images/logo-reevalo.webp'),
(4, 'site', 'default_user_status', '3'),
(5, 'site', 'phone', '+33 9 70 46 64 51'),
(7, 'site', 'email', 'dheeraj.jha@reevalo.com'),
(8, 'site', 'home_text', 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Proin eget tortor risus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Nulla porttitor accumsan ti'),
(10, 'site', 'sending_email', 'dheeraj.jha@reevalo.com'),
(9, 'site', 'date_format', 'D d-m-Y'),
(11, 'site', 'sending_email_name', 'Reevalo Admin'),
(13, 'site', 'time_format', 'h:i A'),
(19, 'site', 'device_auth_key', 'user_auth_key'),
(20, 'site', 'date_time', 'M/d/Y h:i A'),
(22, 'site', 'soft_delete', '1'),
(24, 'site', 'auth_key', 'User-Auth-Token'),
(35, 'site', 'logo-wide', 'static/front/images/logo-wide.png'),
(36, 'site', 'max_image_upload', '3'),
(37, 'site', 'invoice_reference_key', 'MH-'),
(38, 'site', 'grading_img_path', 'static/uploads/app/grading_img/'),
(46, 'site', 'bucket_url', ''),
(47, 'site', 'bucket_name', ''),
(54, 'site', 'favicon', 'public/front/images/favicon.png'),
(55, 'site', 'address', '81, Rue Belliard, Paris, Ile-de-France, 75018 FR'),
(56, 'site', 'website', 'https://reevalo.com/');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `country_sortname` varchar(3) NOT NULL,
  `country_name` varchar(150) NOT NULL,
  `country_flag` varchar(255) NOT NULL,
  `country_phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(11) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 0, '123123', 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` tinyint(4) NOT NULL,
  `status_name` varchar(15) NOT NULL,
  `status_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`, `status_description`) VALUES
(1, 'active', ''),
(2, 'Inactive', ''),
(3, 'suspended', ''),
(4, 'completed', 'completed'),
(9, 'deleted', 'deleted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `name` (`status_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
