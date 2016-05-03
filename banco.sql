-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.5.28 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura do banco de dados para pp
CREATE DATABASE IF NOT EXISTS `pp` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;
USE `pp`;


-- Copiando estrutura para tabela pp.caixa
CREATE TABLE IF NOT EXISTS `caixa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entrada` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.cliente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `end` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `tel` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `obs` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.entrada
CREATE TABLE IF NOT EXISTS `entrada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mat` int(11) NOT NULL,
  `id_cli` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `peso` decimal(10,3) NOT NULL DEFAULT '0.000',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_mat_entrada` (`id_mat`),
  KEY `id_cli_entrada` (`id_cli`),
  CONSTRAINT `id_cli_entrada` FOREIGN KEY (`id_cli`) REFERENCES `cliente` (`id`),
  CONSTRAINT `id_mat_entrada` FOREIGN KEY (`id_mat`) REFERENCES `material` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `end` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `tel` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `obs` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.material
CREATE TABLE IF NOT EXISTS `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `tipo` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.saida
CREATE TABLE IF NOT EXISTS `saida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mat` int(11) NOT NULL,
  `id_for` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `peso` decimal(10,3) NOT NULL DEFAULT '0.000',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_forn_saida` (`id_for`),
  KEY `id_mat_saida` (`id_mat`),
  CONSTRAINT `id_forn_saida` FOREIGN KEY (`id_for`) REFERENCES `fornecedor` (`id`),
  CONSTRAINT `id_mat_saida` FOREIGN KEY (`id_mat`) REFERENCES `material` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela pp.tabela
CREATE TABLE IF NOT EXISTS `tabela` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cli` int(11) NOT NULL,
  `id_mat` int(11) NOT NULL,
  `valor` double(10,2) DEFAULT NULL,
  `dt_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_cli_tabela` (`id_cli`),
  KEY `id_mat_tabela` (`id_mat`),
  CONSTRAINT `id_cli_tabela` FOREIGN KEY (`id_cli`) REFERENCES `cliente` (`id`),
  CONSTRAINT `id_mat_tabela` FOREIGN KEY (`id_mat`) REFERENCES `material` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
