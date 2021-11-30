-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mar. 30 nov. 2021 à 10:24
-- Version du serveur :  5.7.32
-- Version de PHP : 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blogphp`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `parent_id`) VALUES
(12, 'ActualitÃ©', 'actualit', NULL),
(14, 'High-Tech', 'high-tech', NULL),
(15, 'Tutoriel', 'tutoriel', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `validate` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `created_at`, `content`, `author_id`, `post_id`, `validate`, `status`) VALUES
(5, '2021-11-29 13:09:04', 'Je vu Ã  42 euros chez tardy et la bogrill pour le blackfriday !', 19, 29, '2021-11-29 13:09:20', 2),
(6, '2021-11-30 09:34:42', 'Vu comme Ã§a, Ã§a parait simple!', 65, 30, '2021-11-30 09:40:07', 2),
(7, '2021-11-30 09:36:40', 'C\'est une mÃ©thode Ã  tester!', 19, 30, '2021-11-30 09:40:09', 2),
(8, '2021-11-30 09:37:21', 'C\'est pas un commentaire Ã  montrer!', 19, 30, '2021-11-30 10:15:32', 3),
(9, '2021-11-30 09:40:48', 'Un commentaire pas encore modÃ©rÃ©', 65, 28, NULL, 1),
(10, '2021-11-30 09:40:58', 'Un autre commentaire pas encore modÃ©rÃ©', 65, 28, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `catch_phrase` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content_title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `display_navbar` tinyint(1) DEFAULT NULL,
  `display_footer` tinyint(1) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `published` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `page`
--

INSERT INTO `page` (`id`, `name`, `title`, `catch_phrase`, `image`, `content_title`, `content`, `display_navbar`, `display_footer`, `slug`, `published`) VALUES
(32, 'dzqdqzdqz', 'dqzdzqdqz', 'dzqdzqd', 'otter-hello-1136x758.jpg', 'dzqdqz', 'dzqdzq', 0, 0, 'dzqdqzdqz', 1),
(33, 'nom de paga', 'un titre', 'une superbe phrase', 'otter-hello-1136x758.jpg', 'Le titre du contenu', 'lorem ipsuemjdlizqjomlidzqmoidzqjiozdqjizdqo opizdjoizqdjdzqo', 0, 1, 'nom-de-paga', 1),
(34, 'Mentions LÃ©gales', 'Mentions LÃ©gales', '', '', '', 'Qui sommes-nous ?\r\nLâ€™adresse de notre site est : www.blog-geofcalvino.com\r\n\r\nCommentaires\r\nQuand vous laissez un commentaire sur notre site, les donnÃ©es inscrites dans le formulaire de commentaire, mais aussi votre adresse IP et lâ€™agent utilisateur de votre navigateur sont collectÃ©s pour nous aider Ã  la dÃ©tection des commentaires indÃ©sirables.\r\n\r\nUne chaÃ®ne anonymisÃ©e crÃ©Ã©e Ã  partir de votre adresse e-mail (Ã©galement appelÃ©e hash) peut Ãªtre envoyÃ©e au service Gravatar pour vÃ©rifier si vous utilisez ce dernier. Les clauses de confidentialitÃ© du service Gravatar sont disponibles ici : https://automattic.com/privacy/. AprÃ¨s validation de votre commentaire, votre photo de profil sera visible publiquement Ã  cotÃ© de votre commentaire.\r\n\r\nMÃ©dias\r\nSi vous tÃ©lÃ©versez des images sur le site, nous vous conseillons dâ€™Ã©viter de tÃ©lÃ©verser des images contenant des donnÃ©es EXIF de coordonnÃ©es GPS. Les personnes visitant votre site peuvent tÃ©lÃ©charger et extraire des donnÃ©es de localisation depuis ces images.\r\n\r\nCookies\r\nSi vous dÃ©posez un commentaire sur notre site, il vous sera proposÃ© dâ€™enregistrer votre nom, adresse e-mail et site dans des cookies. Câ€™est uniquement pour votre confort afin de ne pas avoir Ã  saisir ces informations si vous dÃ©posez un autre commentaire plus tard. Ces cookies expirent au bout dâ€™un an.\r\n\r\nSi vous vous rendez sur la page de connexion, un cookie temporaire sera crÃ©Ã© afin de dÃ©terminer si votre navigateur accepte les cookies. Il ne contient pas de donnÃ©es personnelles et sera supprimÃ© automatiquement Ã  la fermeture de votre navigateur.\r\n\r\nLorsque vous vous connecterez, nous mettrons en place un certain nombre de cookies pour enregistrer vos informations de connexion et vos prÃ©fÃ©rences dâ€™Ã©cran. La durÃ©e de vie dâ€™un cookie de connexion est de deux jours, celle dâ€™un cookie dâ€™option dâ€™Ã©cran est dâ€™un an. Si vous cochez Â« Se souvenir de moi Â», votre cookie de connexion sera conservÃ© pendant deux semaines. Si vous vous dÃ©connectez de votre compte, le cookie de connexion sera effacÃ©.\r\n\r\nEn modifiant ou en publiant une publication, un cookie supplÃ©mentaire sera enregistrÃ© dans votre navigateur. Ce cookie ne comprend aucune donnÃ©e personnelle. Il indique simplement lâ€™ID de la publication que vous venez de modifier. Il expire au bout dâ€™un jour.\r\n\r\nContenu embarquÃ© depuis dâ€™autres sites\r\nLes articles de ce site peuvent inclure des contenus intÃ©grÃ©s (par exemple des vidÃ©os, images, articlesâ€¦). Le contenu intÃ©grÃ© depuis dâ€™autres sites se comporte de la mÃªme maniÃ¨re que si le visiteur se rendait sur cet autre site.\r\n\r\nCes sites web pourraient collecter des donnÃ©es sur vous, utiliser des cookies, embarquer des outils de suivis tiers, suivre vos interactions avec ces contenus embarquÃ©s si vous disposez dâ€™un compte connectÃ© sur leur site web.\r\n\r\nUtilisation et transmission de vos donnÃ©es personnelles\r\nSi vous demandez une rÃ©initialisation de votre mot de passe, votre adresse IP sera incluse dans lâ€™e-mail de rÃ©initialisation.\r\n\r\nDurÃ©es de stockage de vos donnÃ©es\r\nSi vous laissez un commentaire, le commentaire et ses mÃ©tadonnÃ©es sont conservÃ©s indÃ©finiment. Cela permet de reconnaÃ®tre et approuver automatiquement les commentaires suivants au lieu de les laisser dans la file de modÃ©ration.\r\n\r\nPour les comptes qui sâ€™inscrivent sur notre site (le cas Ã©chÃ©ant), nous stockons Ã©galement les donnÃ©es personnelles indiquÃ©es dans leur profil. Tous les comptes peuvent voir, modifier ou supprimer leurs informations personnelles Ã  tout moment (Ã  lâ€™exception de leur identifiant). Les gestionnaires du site peuvent aussi voir et modifier ces informations.\r\n\r\nLes droits que vous avez sur vos donnÃ©es\r\nSi vous avez un compte ou si vous avez laissÃ© des commentaires sur le site, vous pouvez demander Ã  recevoir un fichier contenant toutes les donnÃ©es personnelles que nous possÃ©dons Ã  votre sujet, incluant celles que vous nous avez fournies. Vous pouvez Ã©galement demander la suppression des donnÃ©es personnelles vous concernant. Cela ne prend pas en compte les donnÃ©es stockÃ©es Ã  des fins administratives, lÃ©gales ou pour des raisons de sÃ©curitÃ©.\r\n\r\nTransmission de vos donnÃ©es personnelles\r\nLes commentaires des visiteurs peuvent Ãªtre vÃ©rifiÃ©s Ã  lâ€™aide dâ€™un service automatisÃ© de dÃ©tection des commentaires indÃ©sirables.', 0, 1, 'mentions-lgales', 1);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `lead` varchar(255) DEFAULT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `image`, `content`, `slug`, `lead`, `last_update`, `author_id`, `category_id`) VALUES
(22, 'Un article test', NULL, 'Nulla facilisi. Nullam sagittis purus tincidunt, ullamcorper neque vitae, dapibus ante. Pellentesque ullamcorper urna vehicula vehicula efficitur. Pellentesque sed imperdiet ante. Nulla facilisi. Donec ultricies vestibulum sem ac pretium. Morbi egestas suscipit metus, non maximus magna lobortis in. Ut in fermentum tortor.\r\n\r\nAliquam bibendum ante a odio dictum fringilla. Cras id lectus sed ex laoreet fringilla. Praesent malesuada accumsan eros sed placerat. Sed in risus et ante eleifend ultrices at vel ipsum. Vestibulum mattis nunc at est tincidunt blandit. Aliquam ornare ut nunc quis egestas. Nunc eleifend velit purus, sit amet scelerisque leo blandit nec. Nulla id condimentum justo. Proin eu varius neque, a mattis sem. Morbi nec metus et orci sollicitudin feugiat eget in nibh. Proin molestie sagittis eros, et blandit dolor sagittis quis.\r\n\r\nMorbi tempus blandit lacus fermentum blandit. Nullam diam justo, mattis sit amet sem sed, semper scelerisque orci. Praesent aliquet consectetur vestibulum. Fusce hendrerit porta ultrices. Phasellus tristique vel justo tincidunt aliquet. Vestibulum commodo pellentesque elit, sit amet luctus turpis vehicula sed. Cras in enim vel erat blandit mattis ultrices quis ligula. Cras vel ornare ex. Maecenas vestibulum tempus ex eget interdum. Donec efficitur purus fermentum, dignissim diam ultricies, accumsan massa. Maecenas diam tortor, interdum a sagittis blandit, accumsan a nibh. Sed eleifend, nulla sit amet auctor accumsan, augue lorem feugiat ligula, sit amet dignissim diam orci ornare velit. Cras non nisi ac lorem malesuada rutrum. Quisque eu commodo purus. Cras at sagittis odio. Morbi odio lectus, feugiat interdum elementum sit amet, bibendum sit amet tortor.\r\n\r\nQuisque aliquet eros nec urna commodo rhoncus. Fusce suscipit rhoncus augue, imperdiet lobortis sapien dictum vitae. Morbi dapibus elit quam, id rutrum magna elementum at. Donec scelerisque risus ac neque sagittis, eu tincidunt quam tempus. Sed at neque vel diam pharetra tincidunt eu eget leo. Nullam non tristique urna. Maecenas at enim eu nunc posuere porttitor non a massa. Aenean ornare consequat elit nec posuere. Donec et arcu sem. Aliquam erat volutpat. Sed semper lectus nec justo varius laoreet. Cras sagittis a sem quis efficitur. Mauris eget quam vitae velit tincidunt accumsan vel ut nisi. Suspendisse at dapibus enim. Donec porttitor eros vel consequat lobortis. Quisque euismod tellus nec faucibus cursus.\r\n\r\nInteger vel placerat urna, nec tempor metus. Nam eu dolor dui. Donec pulvinar, dui aliquam suscipit posuere, nunc velit fringilla urna, ullamcorper auctor felis metus ac urna. Cras nibh elit, rhoncus eget ligula eu, volutpat laoreet mi. Aenean sapien mauris, pharetra in diam eu, consectetur hendrerit justo. Vestibulum tincidunt vel turpis in lobortis. Nam pretium feugiat facilisis. Integer ut sodales purus. In sagittis nisi id eros cursus, vel cursus sapien imperdiet. Cras efficitur neque sit amet fermentum lacinia. Maecenas quis justo mauris. Nunc est quam, eleifend sit amet tortor in, mattis auctor elit. Duis quis malesuada eros.', 'un-article-test', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id mi vitae erat lacinia molestie. Nulla id tellus mollis, tempus mi quis, interdum diam.', '2021-11-17 16:40:33', 1, 12),
(28, 'Lorem Ipsum', 'newspaper-ga0af71b83_1920.jpg', 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Maecenas interdum, libero sed iaculis ultrices, felis ante iaculis odio, eget volutpat metus elit eu sem. Phasellus sed neque gravida, imperdiet arcu efficitur, feugiat ex. Nam porttitor commodo hendrerit. Sed viverra feugiat enim, sit amet faucibus ante efficitur scelerisque. Quisque sed facilisis purus. Sed in enim mauris.\r\n\r\nNunc consectetur orci a consequat blandit. Cras vitae nisi congue, egestas lacus quis, aliquam mauris. Donec commodo sem pretium elit venenatis, vel molestie leo elementum. Cras pulvinar nisi quis sagittis suscipit. Nunc efficitur pulvinar feugiat. Integer consectetur tellus dapibus arcu efficitur, nec consequat odio posuere. Cras ut auctor nibh. Phasellus in augue gravida, ornare sapien id, tempus lectus. Donec blandit eu urna et mollis. Nunc elementum posuere sapien, id elementum elit volutpat sit amet. Praesent nec feugiat risus. Curabitur ac euismod orci, at facilisis sapien. Nam sodales pellentesque nunc quis blandit. Quisque dignissim faucibus vulputate. Nam malesuada maximus augue eu mattis. Nullam eu est lacus.\r\n\r\nNullam est nulla, ullamcorper ut pellentesque cursus, tempor non mauris. Etiam vel nibh non elit venenatis sodales sit amet at ante. Phasellus posuere sapien et risus placerat ultrices. Suspendisse condimentum porta accumsan. Quisque vehicula, diam vel cursus ullamcorper, metus tellus rhoncus justo, at tincidunt quam dolor eu velit. In in ex risus. Nulla placerat eleifend sem, et viverra eros condimentum a. Proin mattis lectus erat, et ornare ex molestie ut. In gravida sed enim et consectetur. Nam non varius orci, quis elementum orci.\r\n\r\nMauris fermentum maximus ex at fringilla. Aenean molestie viverra sagittis. Donec scelerisque neque quis nunc luctus, id condimentum lorem condimentum. Etiam convallis lectus quis magna dapibus, non maximus lacus tincidunt. Nam in nisl arcu. Duis eget tortor vel nisl porta rhoncus. Sed leo neque, varius et augue in, porttitor feugiat neque. Duis feugiat bibendum suscipit. Sed interdum sem id porttitor cursus. Praesent ut tempus nisl. Ut ut risus libero. In rutrum nulla vestibulum, cursus nisi quis, dapibus libero. Sed egestas tincidunt mattis.', 'lorem-ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean faucibus purus et nibh ullamcorper tincidunt vitae quis arcu. Etiam iaculis metus velit.', '2021-11-29 13:03:12', 1, 12),
(29, 'Une nouvelle montre connectÃ©e', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus mauris, tincidunt sit amet leo sit amet, aliquam sollicitudin nisi. Sed vitae suscipit eros. Mauris et enim eget sapien ornare euismod. Nullam sit amet eros ut augue venenatis rhoncus efficitur varius enim. Aenean velit sapien, pulvinar quis efficitur vitae, commodo vel arcu. Aliquam erat volutpat. In tempor vitae nunc eget mollis. Cras eget quam sem. Vivamus mollis, ligula a posuere imperdiet, sapien felis condimentum erat, ac mollis dolor diam vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non erat quis justo laoreet aliquet sit amet vitae enim. Nam fermentum urna orci, vel viverra felis vestibulum ac. Vestibulum vel rhoncus sem. Donec dictum massa sit amet nibh condimentum, a convallis felis bibendum.', 'une-nouvelle-montre-connecte', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus mauris, tincidunt sit amet leo sit amet, aliquam sollicitudin nisi. Sed vitae suscipit eros. ', '2021-11-29 13:05:52', 1, 14),
(30, 'Comment crÃ©er un blog php', 'PHP-logo.svg.png', 'Cras eu nibh lacinia, aliquet lectus at, consectetur arcu. Praesent elementum, elit a iaculis accumsan, justo turpis iaculis lorem, et auctor velit dui et ligula. Vivamus a mauris eget tellus eleifend posuere. Nunc faucibus elit neque, ultricies facilisis dui rutrum id. Suspendisse quis justo sit amet nisi commodo ullamcorper. Nunc eros augue, porta non viverra quis, malesuada finibus sem. Duis enim velit, scelerisque vel laoreet et, sodales accumsan ipsum. Quisque eu lectus et augue ultricies vestibulum non sed lectus. Cras tincidunt, nibh a vulputate convallis, erat tellus lobortis nulla, nec porttitor risus ipsum accumsan orci. Etiam hendrerit lacinia molestie. Etiam tincidunt mi nec tellus cursus, ut sollicitudin risus tincidunt. Maecenas et tortor non felis porta tincidunt. Donec pharetra odio rutrum est convallis tempor. Sed tempus rhoncus vulputate. Donec sit amet ultrices libero, in pharetra massa.\r\n\r\nIn cursus neque id nibh rutrum laoreet. Suspendisse pellentesque enim augue, at placerat justo egestas viverra. Cras posuere eleifend egestas. Nullam mattis augue non nisi aliquet, eu tincidunt dolor ultricies. Ut at sollicitudin justo. Sed rutrum tempus justo non convallis. Etiam ac aliquet tortor.\r\n\r\nQuisque at tortor eu ipsum condimentum scelerisque. Proin ut quam eu orci bibendum semper. In luctus justo at blandit pharetra. Duis vitae aliquet odio. Duis ut cursus tortor. Nulla vitae aliquam odio. Aliquam vel justo sed nisl maximus feugiat.\r\n\r\nAenean vitae consequat dui. Etiam facilisis mi quis libero auctor ornare. Praesent non massa nisl. Sed ultricies venenatis eros sit amet faucibus. Quisque rhoncus dolor neque, non scelerisque lectus gravida nec. Nam eleifend velit urna, vitae auctor odio ultricies id. Suspendisse pulvinar purus in nisi suscipit lacinia. Etiam tempor sagittis lorem pellentesque accumsan. Praesent convallis rutrum diam, eu mattis erat facilisis in. Integer rutrum, tellus vel mollis aliquet, enim augue lobortis velit, aliquam aliquet nisi leo ac elit. Duis vel pharetra tellus, ac fringilla ex.', 'comment-crer-un-blog-php', 'Quisque at tortor eu ipsum condimentum scelerisque. Proin ut quam eu orci bibendum semper. In luctus justo at blandit pharetra. Duis vitae aliquet odio. Duis ut cursus tortor. Nulla vitae aliquam odio. Aliquam vel justo sed nisl maximus feugiat.', '2021-11-29 13:06:23', 1, 15);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `agreed_terms_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `confirm_token` varchar(60) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `reset_token` varchar(60) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `remember_token` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `created_at`, `agreed_terms_date`, `is_active`, `is_admin`, `confirm_token`, `confirmed_at`, `reset_token`, `reset_at`, `remember_token`) VALUES
(1, 'Geoffrey', 'Calvino', 'admin', 'admin@admin.admin', '$2y$10$Z4KDjxpjij7vfr/vjRfjyeqyltBLyAQ9m2MpzgznNQaY2moDsQmom', '2021-11-25 14:02:56', '2021-11-25 14:02:56', 1, 1, '', '2021-11-25 14:05:36', NULL, NULL, '197a3de08bbd9045d20f05d475905ccdc16d414c04f2c07c6d50c065de9852959ab657abba4047058b49b61a3dd233abf56aba69d1711e238dc88de4fe6218da34fa7bbf22ff8a391d95b16d2a2c224875efa575f2254bfa13b75f6ca89334ffafbe7e55b764fcf1251b8f41a0d4df00752fb21b641d2f7d6bd36dc4ca'),
(19, 'test', 'test', 'test', 'calvino.geoffrey@gmail.com', '$2y$10$qbCwrm8PYbH6/SEUQKzzE.YRpIvzNx5aqF8abDkvwouGOeutZOP1m', '2021-10-21 16:45:24', '2021-10-21 16:45:24', 1, NULL, NULL, '2021-10-21 16:46:03', 'd217c81e2b1265fe57f51e051c26ea08681d89251b374e9bb47156c6e12c', '2021-11-02 15:22:22', '728fa97a0b65e0a84b6c3dab2dab47efe936582aac2d121452ce31df494384061ef6e74abad1549fc36312a2ec1fb6026d1dbc81302f9aaec088e3f166c09e1d4534e936e6d3071e9f0e1fde15f114678541df67ea9a748aefce161acefcc593b04bcae95ce5324dfeedd85a674701871f104259a171fbedf8f8b78b82'),
(65, 'Doe', 'John', 'JohnDoe', 'johndoe@dodoit.mail', '$2y$10$1Xjsuy5u2U5UXIrH8wqhuuZmPYDXu.4agslL8urlHJmrVN6ZZS/0C', '2021-11-30 09:31:33', '2021-11-30 09:31:33', 1, NULL, NULL, '2021-11-30 09:33:56', NULL, NULL, '8d2a3dd333f04c2425037e6796190c87c612b45eaab01cbe3480e81d09f0c67a726d6bbbce6e732ae2114980c90673ee74df70b5c54636cd12a8231459105f8b9bfad1aefafe69731174fbed3adbf8e50fcb81f137763a153c11481cea7aa8240b61a34e28d522c7ec2d9591e233dd3bfa43b8933b17e43f68fac8b987');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`author_id`),
  ADD KEY `comment_ibfk_2` (`post_id`);

--
-- Index pour la table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`author_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
