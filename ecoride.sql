-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 21 juil. 2025 à 19:13
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `trip_id` int NOT NULL,
  `reviewer_id` int NOT NULL,
  `passenger_id` int DEFAULT NULL,
  `driver_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pending'
) ;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `trip_id`, `reviewer_id`, `passenger_id`, `driver_id`, `rating`, `content`, `created_at`, `status`) VALUES
(1, 4, 6, 6, 5, 3, 'passable', '2025-07-13 21:55:13', 'approved'),
(2, 14, 5, 6, NULL, 3, 'BLABLA', '2025-07-14 16:52:49', 'rejected'),
(3, 11, 5, 5, NULL, 3, 'bla bla bloublou', '2025-07-15 22:44:42', 'approved'),
(4, 15, 5, 5, NULL, 1, 'nul', '2025-07-16 21:32:25', 'rejected'),
(5, 3, 6, 6, 5, 3, 'C\'était bien mais sans plus', '2025-07-19 22:29:38', 'rejected');

-- --------------------------------------------------------

--
-- Structure de la table `trips`
--

CREATE TABLE `trips` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `departure_city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arrival_city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `departure_datetime` datetime DEFAULT NULL,
  `arrival_datetime` datetime DEFAULT NULL,
  `seats_available` int DEFAULT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_started` tinyint(1) DEFAULT '0',
  `is_completed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trips`
--

INSERT INTO `trips` (`id`, `user_id`, `vehicle_id`, `departure_city`, `arrival_city`, `departure_datetime`, `arrival_datetime`, `seats_available`, `price`, `created_at`, `is_started`, `is_completed`) VALUES
(3, 5, 1, 'lamballe', 'paris', '2025-07-11 12:30:00', '2025-07-11 14:30:00', 0, 45.00, '2025-07-11 19:20:37', 0, 0),
(4, 5, 1, 'lamballe', 'paris', '2025-07-11 13:30:00', '2025-07-11 14:30:00', 1, 45.00, '2025-07-11 19:40:34', 0, 0),
(5, 5, 1, 'lamballe', 'paris', '2025-07-11 21:40:00', '2025-07-11 21:45:00', 0, 45.00, '2025-07-11 19:51:15', 0, 0),
(8, 5, 1, 'lamballe', 'paris', '2025-07-22 12:30:00', '2025-07-22 12:45:00', 2, 45.00, '2025-07-11 20:24:00', 0, 0),
(9, 5, 1, 'paris', 'houston', '2025-07-22 12:45:00', '2025-07-22 13:30:00', 2, 50.00, '2025-07-11 20:27:15', 0, 0),
(10, 5, 1, 'lamballe', 'paris', '2025-07-22 12:30:00', '2025-07-22 12:31:00', 2, 45.00, '2025-07-11 20:35:38', 1, 1),
(11, 5, 1, 'lamballe', 'paris', '2025-07-11 12:30:00', '2025-07-11 13:30:00', 1, 45.00, '2025-07-11 20:40:08', 0, 0),
(12, 5, 1, 'lamballe', 'paris', '2025-07-22 12:03:00', '2025-07-22 12:45:00', 2, 45.00, '2025-07-11 20:48:55', 0, 0),
(13, 5, 1, 'Lamballe', 'Rennes', '2025-07-11 12:30:00', '2025-07-11 13:30:00', 0, 50.00, '2025-07-13 18:59:27', 0, 0),
(14, 5, 1, 'Lamballe', 'Tombouctou', '2025-07-11 12:30:00', '2025-07-11 13:00:00', 1, 60.00, '2025-07-14 11:41:29', 0, 0),
(15, 5, 1, 'Lamballe', 'Paris', '2025-07-16 21:30:00', '2025-07-16 21:45:00', 2, 45.00, '2025-07-16 19:30:53', 0, 0),
(16, 5, 1, 'Tombouctou', 'Seniorita', '2025-07-16 22:30:00', '2025-07-16 22:45:00', 2, 45.00, '2025-07-16 20:19:21', 0, 0),
(17, 5, 1, 'Matignon', 'Lamballe', '2025-07-17 12:30:00', '2025-07-17 13:30:00', 3, 45.00, '2025-07-17 20:48:47', 1, 1),
(18, 5, 1, 'Matignon', 'zoubida', '2025-07-17 12:30:00', '2025-07-17 12:30:00', 3, 45.00, '2025-07-17 20:56:01', 1, 1),
(19, 5, 1, 'Matignon', 'zoubidou', '2025-07-17 12:30:00', '2025-12-17 13:30:00', 3, 45.00, '2025-07-17 20:56:29', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `trip_participants`
--

CREATE TABLE `trip_participants` (
  `id` int NOT NULL,
  `trip_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_confirmed` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trip_participants`
--

INSERT INTO `trip_participants` (`id`, `trip_id`, `user_id`, `created_at`, `is_confirmed`) VALUES
(1, 3, 5, '2025-07-13 12:23:13', 0),
(2, 11, 5, '2025-07-13 12:23:31', 0),
(4, 4, 5, '2025-07-13 16:48:19', 0),
(5, 13, 5, '2025-07-13 19:00:08', 0),
(6, 13, 6, '2025-07-13 19:02:34', 0),
(7, 4, 6, '2025-07-13 19:04:44', 0),
(8, 5, 6, '2025-07-14 11:39:40', 0),
(9, 14, 6, '2025-07-14 11:42:02', 0),
(10, 15, 5, '2025-07-16 19:32:13', 0),
(12, 3, 7, '2025-07-16 20:16:54', 0),
(13, 16, 7, '2025-07-16 20:19:53', 0),
(14, 17, 7, '2025-07-17 20:50:40', 1),
(15, 18, 7, '2025-07-17 20:57:01', 1),
(16, 19, 7, '2025-07-17 20:57:15', -1),
(17, 3, 6, '2025-07-19 20:29:23', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `credits` int DEFAULT '20',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_driver` tinyint(1) DEFAULT '0',
  `is_passenger` tinyint(1) DEFAULT '0',
  `is_employee` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) DEFAULT '0',
  `is_suspended` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `credits`, `created_at`, `is_driver`, `is_passenger`, `is_employee`, `is_admin`, `is_suspended`) VALUES
(1, 'pascala', 'leclerre', 'aerty@gmail.com', '$2y$10$UMjbcO4opBZviaEJCFPNbe3Wj2iWfNPluH8OMFZbOz/4QxzZQd5qu', 20, '2025-06-25 17:45:35', 0, 0, 0, 0, 0),
(2, 'bonjour', 'loulou', 'loulou@gmail.com', '$2y$10$bSE7yujUpefD.cjcZ4vF/Ogo6wKHeCCmkVq3JGpOv9T6EgZMF0w2O', 20, '2025-06-25 17:47:31', 0, 0, 0, 0, 0),
(3, 'pascal', 'loulou', 'leloulou@gmail.com', '$2y$10$rnyP4H/2Zv.jVhnmLuWwauVE43sGDBahr86y/2p.IIGlgq6dGvjsu', 20, '2025-06-25 17:49:41', 0, 0, 0, 0, 0),
(4, 'toto', 'letesteur', 'totoletesteur@gmail.com', '$2y$10$F5dWJvATBkJIbnrEbq67zO8Gf7Uv7ZGh35M5CC/iju8UEnJwCI0Im', 20, '2025-06-25 19:31:47', 0, 0, 0, 0, 0),
(5, 'pascal', 'leclerre', 'madinfopc@gmail.com', '$2y$10$r5pjkW7sPVHfV9syIBHXsuEf.eY6ZgkXohFJ9Vu0eXYRT4uJIVyi2', 77, '2025-07-02 11:07:10', 1, 0, 1, 1, 0),
(6, 'letesteur', 'titi', 'letesteurtiti@gmail.com', '$2y$10$qlDenbXTeJ1yOngA7vH9BO3Lh88KPR3NFOv9jsW.PrsCHlJUOJhv.', 35, '2025-07-13 19:01:32', 0, 1, 0, 0, 0),
(7, 'france', 'mazeline', 'france.mazeline@hotmail.com', '$2y$10$i.kzJalOkObJQddiX9YPReT1Y7ieh7jCDElNndcyEOSXrfgAd.0sO', 14, '2025-07-16 19:33:23', 0, 1, 0, 0, 0),
(8, 'pascal', 'doudou', 'doudouplanet@youpla.com', '$2y$10$xoio1BJJZxfEpm5yI7rJCu3h8EFvyiIVIY7mBVsVwWe4yrtFwaqKS', 20, '2025-07-20 11:08:24', 0, 0, 1, 0, 0),
(9, 'Jean', 'Dupont', 'user@ecoride.fr', '$2y$10$kqfWEGuR7cNKo9Vvab9nTOOYoN3RpeZQGAVMN9qtaEjwiZ3yIO5dC', 20, '2025-07-21 18:25:45', 1, 1, 0, 0, 0),
(10, 'Aurelie', 'Suspension', 'employee@ecoride.fr', '$2y$10$3VGBGZbEYH75q6P4A3n5nOGEHL5PyLdXKILq0/1Gm3Kcy/nc3j3xO', 20, '2025-07-21 18:27:13', 0, 0, 1, 0, 0),
(11, 'Pascal', 'LeRide', 'admin@ecoride.fr', '$2y$10$jvIxKlj5xg2Afz2HLpiJxevCB5etU9kWdTzWsnpVuDQGOQYMYlh3C', 20, '2025-07-21 18:28:13', 0, 0, 0, 1, 0),
(12, 'Alice', 'Durand', 'alice@ecoride.fr', '$2y$10$6ka48TcT7sD1P3vGV2YIVukQQPSIN/cWXF2HPLZdF6n3Cy9RMV0XW', 20, '2025-07-21 19:07:02', 1, 1, 0, 0, 0),
(13, 'Bob', 'Martin', 'bob@ecoride.fr', '$2y$10$vu/NVxTuF0nAsG8CgUg6He/dtsOf/RI6h8ZEnjpl/sCwLddvJ0nGy', 0, '2025-07-21 19:07:02', 0, 0, 1, 0, 0),
(14, 'Charlie', 'Lemoine', 'charlie@ecoride.fr', '$2y$10$ieZ.zQnTxolxns6f7PC41.hG9efgYboYKfNBaIs29BKeGzE1PF1He', 0, '2025-07-21 19:07:02', 0, 0, 0, 1, 0),
(15, 'alice', 'durand', 'alicedurand@ecoride.fr', '$2y$10$5jxjReCeCNstzEsvIHat1udlbPes2qLItu.pSlRbKKonZK3UgYeBO', 20, '2025-07-21 19:08:20', 1, 1, 0, 0, 0),
(16, 'bob', 'dylan', 'bobdylan@ecoride.fr', '$2y$10$2CMXmOg.tF53QbtSTcDKkeXmy85HXGSoIUno.9xZ5ortu3GR05iz2', 20, '2025-07-21 19:08:42', 0, 0, 1, 0, 0),
(17, 'charlie', 'lachocolaterie', 'charlielachocolaterie@ecoride.fr', '$2y$10$eT71ef.XR7/dGM.IDJDxc.SSud9oxkKyzcZxflTwAiUixminI6tKy', 20, '2025-07-21 19:09:12', 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `energy` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `plate_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `seats` int DEFAULT NULL,
  `preferences` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `brand`, `model`, `color`, `energy`, `plate_number`, `registration_date`, `seats`, `preferences`) VALUES
(1, 5, 'peugeot', '308', 'grise', 'diesel', 'BV-675-AA', '2022-10-01', 3, 'non fumeur, pas d\'animaux autorisés');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trip_id` (`trip_id`,`passenger_id`),
  ADD KEY `passenger_id` (`passenger_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `fk_reviewer` (`reviewer_id`);

--
-- Index pour la table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Index pour la table `trip_participants`
--
ALTER TABLE `trip_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `trip_participants`
--
ALTER TABLE `trip_participants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trip_participants`
--
ALTER TABLE `trip_participants`
  ADD CONSTRAINT `trip_participants_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`),
  ADD CONSTRAINT `trip_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
