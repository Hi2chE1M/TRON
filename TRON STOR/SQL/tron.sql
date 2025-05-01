
CREATE TABLE `achats` (
  `id_achat` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_jeu` int(11) NOT NULL,
  `date_achat` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant` decimal(10,2) NOT NULL,
  `adresse_livraison` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `statut` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `achats` (`id_achat`, `id_utilisateur`, `id_jeu`, `date_achat`, `montant`, `adresse_livraison`, `telephone`, `statut`) VALUES
(9, 5, 3, '2025-03-22 15:03:21', 5000.00, 'سيدي عون', '0678787', 'complet'),
(10, 7, 3, '2025-03-22 15:35:23', 5000.00, 'سيدي عون', '0678787', 'pending');



CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(5, 'Racing'),
(3, 'RPG'),
(4, 'Sports');



CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `statut` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `numero_carte` varchar(20) NOT NULL,
  `date_expiration` varchar(7) NOT NULL,
  `code_cvv` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `commandes` (`id_commande`, `id_utilisateur`, `montant`, `statut`, `date_commande`, `numero_carte`, `date_expiration`, `code_cvv`) VALUES
(9, 7, 2222.00, 'Pending', '2025-03-22 15:27:08', '1232132321232123', '21/21', '123');



CREATE TABLE `jeux` (
  `id_jeu` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `jeux` (`id_jeu`, `titre`, `description`, `prix`, `id_categorie`, `id_admin`, `date_ajout`, `image`) VALUES
(1, 'Call of Duty', NULL, 4500.00, 1, 7, '2025-03-22 08:10:21', ''),
(3, 'FIFA 23', NULL, 5000.00, 4, 7, '2025-03-22 08:10:21', ''),
(4, 'Need for Speed Heat', NULL, 5500.00, 5, 7, '2025-03-22 08:10:21', ''),
(5, 'Assassin\'s Creed Valhalla', NULL, 7000.00, 2, 7, '2025-03-22 08:10:21', ''),
(6, 'Cyberpunk 2077', NULL, 6500.00, 3, 7, '2025-03-22 08:10:21', ''),
(7, 'Gran Turismo 7', NULL, 7500.00, 5, 7, '2025-03-22 08:10:21', ''),
(8, 'Red Dead Redemption 2', NULL, 6800.00, 2, 7, '2025-03-22 08:10:21', ''),
(101, 'Forza Horizon 5', NULL, 7500.00, 5, 7, '2025-03-22 08:51:05', ''),
(102, 'GTA V', NULL, 5000.00, 1, 7, '2025-03-22 08:51:05', ''),
(103, 'Black Myth: Wukong', NULL, 8000.00, 3, 7, '2025-03-22 08:51:05', ''),
(104, 'The Last of Us', NULL, 6000.00, 2, 7, '2025-03-22 08:51:05', ''),
(111, 'NBA 2K23', NULL, 4800.00, 4, 7, '2025-03-22 08:10:21', '');



CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `solde` decimal(10,2) DEFAULT 0.00,
  `adresse` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `email`, `mot_de_passe`, `role`, `date_inscription`, `solde`, `adresse`) VALUES
(5, 'mosbah', 'mos@lkh.com', '$2y$10$4YD/8aVASvww8kbtpdAsQeeyypnbmtFxvVFoEvr6nL2bvF9T2Xmqy', 'user', '2025-03-21 15:35:01', 0.00, ''),
(7, 'hichem', 'hichem@lekhouimes.com', '$2y$10$78xtd//e.6firovnaTbZauSXWIWa5bp82Sg.NCK2Hocs10OG/kEc6', 'admin', '2025-03-21 21:48:14', 6111.00, '');


ALTER TABLE `achats`
  ADD PRIMARY KEY (`id_achat`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_jeu` (`id_jeu`);


ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `nom_categorie` (`nom_categorie`);


ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);


ALTER TABLE `jeux`
  ADD PRIMARY KEY (`id_jeu`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `jeux` (`id_categorie`);


ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);



ALTER TABLE `achats`
  MODIFY `id_achat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;


ALTER TABLE `jeux`
  MODIFY `id_jeu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;


ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;


ALTER TABLE `achats`
  ADD CONSTRAINT `achats_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `achats_ibfk_2` FOREIGN KEY (`id_jeu`) REFERENCES `jeux` (`id_jeu`) ON DELETE CASCADE;


ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

ALTER TABLE `jeux`
  ADD CONSTRAINT `jeux` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`),
  ADD CONSTRAINT `jeux_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE CASCADE,
  ADD CONSTRAINT `jeux_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

