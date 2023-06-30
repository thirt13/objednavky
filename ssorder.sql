-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

-- Verze serveru: 10.4.28-MariaDB
-- Verze PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Databáze: `ssorder`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `history_login`
--

CREATE TABLE `history_login` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `finish` varchar(80) NOT NULL,
  `year` int(11) NOT NULL,
  `providers_id` int(11) NOT NULL,
  `order_item` mediumtext NOT NULL,
  `detail` mediumtext NOT NULL,
  `director` varchar(80) NOT NULL,
  `pdf` mediumtext NOT NULL,
  `price` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci ROW_FORMAT=COMPACT;


-- --------------------------------------------------------

--
-- Struktura tabulky `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `street` varchar(80) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(80) NOT NULL,
  `country` varchar(82) NOT NULL,
  `ic` int(11) NOT NULL,
  `dic` varchar(80) NOT NULL,
  `phone` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `www` varchar(80) NOT NULL,
  `director` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_number` varchar(80) NOT NULL,
  `users_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Struktura tabulky `school`
--

CREATE TABLE `school` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `street` varchar(80) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(80) NOT NULL,
  `country` varchar(82) NOT NULL,
  `ic` int(11) NOT NULL,
  `dic` varchar(80) NOT NULL,
  `phone` varchar(80) NOT NULL,
  `account_number` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `www` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `director` mediumtext NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `history_login`
--
ALTER TABLE `history_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firm` (`providers_id`),
  ADD KEY `id_customer` (`school_id`);

--
-- Indexy pro tabulku `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`);


--
-- Indexy pro tabulku `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`id`,`username`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `history_login`
--
ALTER TABLE `history_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `school`
--
ALTER TABLE `school`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `providers` FOREIGN KEY (`providers_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO `users` (`id`, `username`, `password`, `role`, `name`, `created_at`, `active`) VALUES
(8, 'admin', '$2y$10$NotgHphtJh86EQDctEfQeOzEtBgn.Sf1bYhIIf5Z6RtCcn6uxHyCK', 'admin', 'admin', '2023-06-22 15:25:04', 1);


INSERT INTO `school` (`id`, `name`, `street`, `zip`, `city`, `country`, `ic`, `dic`, `phone`, `account_number`, `email`, `www`, `created_at`, `director`, `active`) VALUES
(1, 'Ing. Tomáš Hirt', 'fadfadfadsf 125', 60200, 'asdfasdfasdfas', 'Česká republika', 73729639, '', '773191903', '', 'tomas.hirt@gmail.com', '', '2023-06-29 16:47:53', '', 1);

COMMIT;