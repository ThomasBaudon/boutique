-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 25 oct. 2022 à 15:05
-- Version du serveur :  5.7.34
-- Version de PHP : 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boutiquephp`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyé','livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_detail_commande` int(3) NOT NULL,
  `id_commande` int(3) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `quantite` int(3) NOT NULL,
  `prix` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(30) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `prenom` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` int(5) UNSIGNED ZEROFILL NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `statut` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `ville`, `code_postal`, `adresse`, `statut`) VALUES
(22, 'admin', '$2y$10$d8iq1OqIbGLO7ZUDqRfvVuB0nn.pIkE33ffrOKErFbCqjVwVdzAgO', 'Baudon', 'Thomas', 'tbaudon@yahoo.fr', 'm', 'Lose', 60190, '1 rue des pas de pots', 1),
(23, 'thomas', '$2y$10$c3XMT.1Ohdi5H02UnGLTg.9TAAqvdyu5Kkzhs1K3ajCidgubb6.42', 'Baudon', 'Thomas', 'tbaudon@yahoo.fr', 'm', 'Moyvillers', 99999, '1 rue des pas de pots', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(10) NOT NULL,
  `genre` enum('m','f','mixte') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` int(6) NOT NULL,
  `stock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `genre`, `photo`, `prix`, `stock`) VALUES
(19, 'Sweat-Bleu', 'sweat', 'SWEAT basic bleu', 'Vous porterez facilement ce sweat homme uni avec une tenue casual, que ce soit accompagné d\'un jean ou encore d\'un jogging, dans un esprit plus sportif. Très confortable, le sweat est 100 % coton. Il a une capuche avec des liens de serrage.\r\n\r\nFrench terry, ce sont des joggings légers 100% coton parfaits pour s’activer au printemps. Un mot d’ordre: respirer ! Grâce aux bouclettes intérieures, vos mouvements n’auront pas de limite. Question sueur : ça nous fait pas peur ! La matière peut absorber 27 fois son poids en eau. Augmentez la résistance sur l’élliptique, nos joggings sont prêts à ce que vous vous donniez à 1000%.', 'bleu', 'X-large', 'm', 'localhost:8080/boutique/images/1666601042_ Sweat-bleu_sweat-bleu.jpg', 25, 10),
(20, 'sweat_zip_noir', 'sweat', 'sweat zip noir', 'Les inconditionnels des looks streetwear sélectionnent sans hésiter ce sweatshirt basique comme nouveau must-have. Ce modèle se dévoile dans une coupe droite et comporte une fermeture zippée sur la longueur devant. Il est muni d\'une capuche, de manches longues et de deux poches à l\'avant. Uni, ce sweatshirt est disponible dans plusieurs coloris très tendance. Ajoutez-le à vos ensembles urbains décontractés comme un jean, un jogging ou un short, combinés à un t-shirt et des baskets.', 'noir', 'X-large', 'm', 'localhost:8080/boutique/images/1666346451_sweat_zip_noir_sweat-zipp-capuche-noir.jpeg', 99, 72),
(21, 'Sweat-jaune', 'sweat', ' sweat jaune', ' Vous porterez facilement ce sweat homme uni avec une tenue casual, que ce soit accompagné d\'un jean ou encore d\'un jogging, dans un esprit plus sportif. Très confortable, le sweat est 100 % coton. Il a une capuche avec des liens de serrage.\r\n\r\nFrench terry, ce sont des joggings légers 100% coton parfaits pour s’activer au printemps. Un mot d’ordre: respirer ! Grâce aux bouclettes intérieures, vos mouvements n’auront pas de limite. Question sueur : ça nous fait pas peur ! La matière peut absorber 27 fois son poids en eau. Augmentez la résistance sur l’élliptique, nos joggings sont prêts à ce que vous vous donniez à 1000%.', ' jaune', 'X-large', 'm', 'localhost:8080/boutique/images/1666600410_ Sweat-jaune_sweat-jaune.jpeg', 29, 10),
(22, 'Doudoune', 'manteaux', ' Doudoune Marine', 'Doudoune light, sans capuche, et sans manches. Osez la couleur grâce à cette doudoune light sans manches pour homme. Une pièce matelassée en tissu déperlant, fine et chaude à la fois, pour un porté remarqué au quotidien. Cette doudoune à fermeture zippée se dessine dans une coupe courte ajustée, est pourvue d\'un col montant, de poches latérales zippées et d\'une poche intérieure plaquée. On la porte idéalement sur une chemise en jean pour un look décontracté.', ' Bleu marine', 'X-large', 'm', 'localhost:8080/boutique/images/1666602134_ doudoune_doudoune-light-marine.jpg', 49, 10),
(23, 'jean-classic-blue', 'jean', ' Jean blue classic', ' Disponible dans 3 longueurs, ce jean C5 s\'adapte à votre morphologie. Ce classique du vestiaire masculin est parfait pour une tenue cool et confort, porté avec un t-shirt et des baskets. Le jean homme a deux poches latérales et une poche ticket à l\'avant, deux poches arrières, une fermeture éclair et est en coton stretch. Pour prolonger la durée de vie de vos jeans, lavez-les systématiquement à basse température et à l\'envers, afin de préserver la couleur et la structure du tissu.', ' bleu', 'X-large', 'm', 'localhost:8080/boutique/images/1666682571_ jean-classic-blue_jean-regular.jpg', 49, 10),
(24, 'Jean-classic-noir', 'jean', ' Jean black classic', ' Disponible dans 3 longueurs, ce jean C5 s\'adapte à votre morphologie. Ce classique du vestiaire masculin est parfait pour une tenue cool et confort, porté avec un t-shirt et des baskets. Le jean homme a deux poches latérales et une poche ticket à l\'avant, deux poches arrières, une fermeture éclair et est en coton stretch. Pour prolonger la durée de vie de vos jeans, lavez-les systématiquement à basse température et à l\'envers, afin de préserver la couleur et la structure du tissu.', ' noir', 'X-large', 'm', 'localhost:8080/boutique/images/1666682610_ jean-classic-noir_jean-regular-c5-noir.jpg', 49, 10),
(25, 'Sweat-blanc', 'sweat', 'Sweat blanc', ' Vous porterez facilement ce sweat homme uni avec une tenue casual, que ce soit accompagné d\'un jean ou encore d\'un jogging, dans un esprit plus sportif. Très confortable, le sweat est 100 % coton. Il a une capuche avec des liens de serrage.\r\n\r\nFrench terry, ce sont des joggings légers 100% coton parfaits pour s’activer au printemps. Un mot d’ordre: respirer ! Grâce aux bouclettes intérieures, vos mouvements n’auront pas de limite. Question sueur : ça nous fait pas peur ! La matière peut absorber 27 fois son poids en eau. Augmentez la résistance sur l’élliptique, nos joggings sont prêts à ce que vous vous donniez à 1000%.', ' blanc', 'X-large', 'm', 'localhost:8080/boutique/images/1666682696_ Sweat-blanc_sweat-blanc.jpeg', 29, 10),
(26, 'Polo-marine', 'Polo', ' Polo marine', ' Vous porterez ce polo homme à manches courtes avec un pantalon chino, pour créer un look casual et confortable. Le polo est 100 % coton, avec une broderie étoile en ton sur ton au niveau de la poitrine. Son col est agrémenté de deux boutons.', ' Bleu marine', 'X-large', 'm', 'localhost:8080/boutique/images/1666684401_Polo-marine_polo-coton-marine.jpg', 19, 10);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `commande_ibfk_1` (`id_membre`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_detail_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_detail_commande` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
