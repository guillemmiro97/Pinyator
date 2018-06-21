-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-06-2018 a las 22:11:02
-- Versión del servidor: 5.6.39-log
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `marrecs_Pinyator`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CASTELL`
--

CREATE TABLE `CASTELL` (
  `Castell_ID` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Event_ID` int(11) NOT NULL,
  `Data_Creacio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `H` int(11) NOT NULL DEFAULT '800',
  `W` int(11) NOT NULL DEFAULT '1000',
  `Pestanya_1` varchar(50) NOT NULL,
  `Pestanya_2` varchar(50) NOT NULL,
  `Pestanya_3` varchar(50) NOT NULL,
  `Pestanya_4` varchar(50) NOT NULL,
  `ORDRE` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CASTELLER`
--

CREATE TABLE `CASTELLER` (
  `Casteller_ID` int(11) NOT NULL,
  `MalNom` varchar(50) NOT NULL,
  `Altura` int(11) NOT NULL,
  `Forca` int(11) NOT NULL,
  `POSICIO_PINYA_ID` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Cognom_1` varchar(50) NOT NULL,
  `Cognom_2` varchar(50) NOT NULL,
  `Codi` varchar(50) NOT NULL,
  `Familia_ID` int(11) NOT NULL,
  `Estat` int(11) NOT NULL,
  `Lesionat` bit(1) NOT NULL DEFAULT b'0',
  `Portar_Peu` bit(1) NOT NULL DEFAULT b'1',
  `FAMILIA2_ID` int(11) DEFAULT NULL,
  `POSICIO_TRONC_ID` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura Stand-in para la vista `CASTELLER_INSCRITS`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `CASTELLER_INSCRITS` (
`EVENT_ID` int(11)
,`ESTAT` int(11)
,`CODI` varchar(50)
,`CASTELLER_ID` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CASTELL_POSICIO`
--

CREATE TABLE `CASTELL_POSICIO` (
  `CASELLA_ID` int(11) NOT NULL,
  `Castell_ID` int(11) NOT NULL,
  `Casteller_ID` int(11) NOT NULL,
  `Posicio_ID` int(11) NOT NULL,
  `Cordo` int(11) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL,
  `W` int(11) NOT NULL,
  `H` int(11) NOT NULL,
  `Angle` int(11) NOT NULL,
  `Forma` int(11) NOT NULL,
  `text` varchar(250) NOT NULL,
  `Pestanya` int(11) NOT NULL,
  `Linkat` int(11) DEFAULT NULL,
  `Seguent` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `EVENT`
--

CREATE TABLE `EVENT` (
  `Event_ID` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Data` datetime NOT NULL,
  `Tipus` int(11) NOT NULL,
  `Estat` int(11) NOT NULL DEFAULT '1',
  `EVENT_PARE_ID` int(11) NOT NULL DEFAULT '0',
  `ESPLANTILLA` bit(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `EVENT_COMENTARIS`
--

CREATE TABLE `EVENT_COMENTARIS` (
  `ID` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `usuari` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `INSCRITS`
--

CREATE TABLE `INSCRITS` (
  `Event_ID` int(11) NOT NULL,
  `Casteller_ID` int(11) NOT NULL,
  `Estat` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `PLANTILLA`
--

CREATE TABLE `PLANTILLA` (
  `Plantilla_ID` int(11) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Estat` int(11) NOT NULL,
  `W` int(11) NOT NULL DEFAULT '1000',
  `H` int(11) NOT NULL DEFAULT '700',
  `Pestanya_1` varchar(50) NOT NULL,
  `Pestanya_2` varchar(50) NOT NULL,
  `Pestanya_3` varchar(50) NOT NULL,
  `Pestanya_4` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Estructura de tabla para la tabla `PLANTILLA_POSICIO`
--

CREATE TABLE `PLANTILLA_POSICIO` (
  `CASELLA_ID` int(11) NOT NULL,
  `Plantilla_ID` int(11) NOT NULL,
  `Posicio_ID` int(11) NOT NULL,
  `Cordo` int(11) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL,
  `W` int(11) NOT NULL,
  `H` int(11) NOT NULL,
  `Angle` int(11) NOT NULL,
  `Forma` int(11) NOT NULL,
  `text` varchar(250) NOT NULL,
  `Pestanya` int(11) NOT NULL,
  `Linkat` int(11) DEFAULT NULL,
  `Seguent` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `POSICIO`
--

CREATE TABLE `POSICIO` (
  `Posicio_ID` int(11) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `EsNucli` tinyint(1) NOT NULL,
  `EsCordo` tinyint(1) NOT NULL,
  `EsTronc` tinyint(1) NOT NULL,
  `COLORFONS` char(7) NOT NULL,
  `COLORTEXT` char(7) NOT NULL,
  `ESFOLRE` bit(1) DEFAULT NULL,
  `ESCANALLA` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `POSICIO`
--

INSERT INTO `POSICIO` (`Posicio_ID`, `Nom`, `EsNucli`, `EsCordo`, `EsTronc`, `COLORFONS`, `COLORTEXT`, `ESFOLRE`, `ESCANALLA`) VALUES
(2, 'Primers', 0, 1, 0, '#ffd1a3', '#000000', b'0', NULL),
(1, 'Tonqueti', 0, 1, 1, '#cfcfcf', '#000000', b'0', NULL),
(3, 'Vent', 0, 1, 0, '#ffaeae', '#000000', b'0', NULL),
(4, 'Lateral', 0, 1, 0, '#a3d5ff', '#000000', b'0', NULL),
(5, 'Crosa', 1, 0, 0, '#9cff9c', '#000000', b'0', NULL),
(6, 'Contrafort', 1, 0, 0, '#a448ff', '#ffffff', b'0', NULL),
(7, 'Agulla', 1, 0, 0, '#ffff00', '#000000', NULL, NULL),
(9, '1Baixos', 1, 0, 1, '#8080ff', '#ffffff', b'0', NULL),
(10, 'Altres', 0, 0, 0, '#c0c0c0', '#000000', NULL, NULL),
(11, 'Gralles', 0, 0, 0, '#c0c0c0', '#000000', NULL, NULL),
(12, 'Canalla', 0, 0, 0, '#c0c0c0', '#000000', b'0', 1),
(13, 'Tap', 0, 1, 0, '#c1ffc1', '#000000', b'0', NULL),
(14, 'Crosa Folre', 0, 0, 0, '#80ff00', '#000000', b'1', NULL),
(15, 'Contrafort Folre', 0, 0, 0, '#ff00ff', '#000000', b'1', NULL),
(16, 'Agulla Folre', 0, 0, 0, '#ffff00', '#000000', b'1', NULL),
(17, 'Portacrosses', 0, 1, 0, '#ff80ff', '#ffffff', b'0', NULL),
(18, '3TerÃ§os', 0, 0, 1, '#cfcfcf', '#000000', b'0', NULL),
(19, '4Quarts', 0, 0, 1, '#cfcfcf', '#000000', b'0', NULL),
(20, '5Quints', 0, 0, 1, '#cfcfcf', '#000000', b'0', NULL),
(21, '2Segons', 0, 0, 1, '#cfcfcf', '#000000', b'0', NULL),
(22, '6Sisens', 0, 0, 1, '#d1d1d1', '#000000', b'0', NULL),
(23, 'Soca', 0, 1, 0, '#e1e1e1', '#000000', b'0', 0),
(24, 'Crossanet', 0, 1, 0, '#b4ddfe', '#000000', b'0', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIS`
--

CREATE TABLE `USUARIS` (
  `idusuari` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL,
  `Seguretat` int(11) NOT NULL DEFAULT '0',
  `CARREC` int(11) NOT NULL,
  `SEGADMIN` int(11) DEFAULT NULL,
  `SEGCASTELLER` int(11) DEFAULT NULL,
  `SEGBOSS` int(11) DEFAULT NULL,
  `SEGCASTELL` int(11) DEFAULT NULL,
  `SEGEVENT` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `USUARIS`
--

INSERT INTO `USUARIS` (`idusuari`, `nom`, `password`, `Seguretat`, `CARREC`, `SEGADMIN`, `SEGCASTELLER`, `SEGBOSS`, `SEGCASTELL`, `SEGEVENT`) VALUES
(1, 'Admin', 'Admin', 1, 1, 1, 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura para la vista `CASTELLER_INSCRITS`
--
DROP TABLE IF EXISTS `CASTELLER_INSCRITS`;

CREATE ALGORITHM=UNDEFINED DEFINER=`marrecs`@`localhost` SQL SECURITY DEFINER VIEW `CASTELLER_INSCRITS`  AS  select `i`.`Event_ID` AS `EVENT_ID`,`i`.`Estat` AS `ESTAT`,`c`.`Codi` AS `CODI`,`i`.`Casteller_ID` AS `CASTELLER_ID` from (`INSCRITS` `i` join `CASTELLER` `c` on((`c`.`Casteller_ID` = `i`.`Casteller_ID`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CASTELL`
--
ALTER TABLE `CASTELL`
  ADD PRIMARY KEY (`Castell_ID`);

--
-- Indices de la tabla `CASTELLER`
--
ALTER TABLE `CASTELLER`
  ADD PRIMARY KEY (`Casteller_ID`),
  ADD UNIQUE KEY `MalNom` (`MalNom`);

--
-- Indices de la tabla `CASTELL_POSICIO`
--
ALTER TABLE `CASTELL_POSICIO`
  ADD PRIMARY KEY (`CASELLA_ID`,`Castell_ID`);

--
-- Indices de la tabla `EVENT`
--
ALTER TABLE `EVENT`
  ADD PRIMARY KEY (`Event_ID`);

--
-- Indices de la tabla `EVENT_COMENTARIS`
--
ALTER TABLE `EVENT_COMENTARIS`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `INSCRITS`
--
ALTER TABLE `INSCRITS`
  ADD PRIMARY KEY (`Event_ID`,`Casteller_ID`);

--
-- Indices de la tabla `PLANTILLA`
--
ALTER TABLE `PLANTILLA`
  ADD PRIMARY KEY (`Plantilla_ID`);

--
-- Indices de la tabla `PLANTILLA_POSICIO`
--
ALTER TABLE `PLANTILLA_POSICIO`
  ADD PRIMARY KEY (`CASELLA_ID`,`Plantilla_ID`);

--
-- Indices de la tabla `POSICIO`
--
ALTER TABLE `POSICIO`
  ADD PRIMARY KEY (`Posicio_ID`);

--
-- Indices de la tabla `USUARIS`
--
ALTER TABLE `USUARIS`
  ADD PRIMARY KEY (`idusuari`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CASTELL`
--
ALTER TABLE `CASTELL`
  MODIFY `Castell_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=889;

--
-- AUTO_INCREMENT de la tabla `CASTELLER`
--
ALTER TABLE `CASTELLER`
  MODIFY `Casteller_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=482;

--
-- AUTO_INCREMENT de la tabla `EVENT`
--
ALTER TABLE `EVENT`
  MODIFY `Event_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de la tabla `EVENT_COMENTARIS`
--
ALTER TABLE `EVENT_COMENTARIS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;

--
-- AUTO_INCREMENT de la tabla `PLANTILLA`
--
ALTER TABLE `PLANTILLA`
  MODIFY `Plantilla_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `POSICIO`
--
ALTER TABLE `POSICIO`
  MODIFY `Posicio_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `USUARIS`
--
ALTER TABLE `USUARIS`
  MODIFY `idusuari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
