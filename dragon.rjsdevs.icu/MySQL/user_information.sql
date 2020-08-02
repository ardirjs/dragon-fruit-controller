-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Jul 2020 pada 10.42
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dragon_fruit`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_information`
--

CREATE TABLE `user_information` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `product` varchar(225) NOT NULL,
  `re_product` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `re_password` varchar(225) NOT NULL,
  `lamp_a` varchar(225) NOT NULL,
  `lamp_b` varchar(225) NOT NULL,
  `lamp_a_data` varchar(225) NOT NULL,
  `lamp_b_data` varchar(225) NOT NULL,
  `volt` varchar(225) NOT NULL,
  `ampere` varchar(225) NOT NULL,
  `statistics` varchar(225) NOT NULL,
  `statistics_data` varchar(225) NOT NULL,
  `times` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_information`
--

INSERT INTO `user_information` (`id`, `username`, `email`, `product`, `re_product`, `password`, `re_password`, `lamp_a`, `lamp_b`, `lamp_a_data`, `lamp_b_data`, `volt`, `ampere`, `statistics`, `statistics_data`, `times`) VALUES
(1, 'ardyengineer', 'ardyengineer@gmail.com', '$2y$12$/hhDt51sgJSmTicemaZhGeMn4StHvud/l2rN7qsvx2JFvNtWSEtX2', '12345678', '$2y$12$TGWScmxF39zMElma8mOOLeICbHO8MpU5s04Ppt3OhfBFzywtYDQLC', '12345678', '0', '0', '{\"hour_on\":\"00\",\"hour_off\":\"00\",\"minute_on\":\"00\",\"minute_off\":\"00\",\"date\":\"15:41:38 29/07/2020\"}', '{\"hour_on\":\"00\",\"hour_off\":\"00\",\"minute_on\":\"00\",\"minute_off\":\"00\",\"date\":\"15:41:38 29/07/2020\"}', '0', '0', '{\"data\":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],\"date\":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}', '15:41:38 29/07/2020', '15:41:38 29/07/2020');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `user_information`
--
ALTER TABLE `user_information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
