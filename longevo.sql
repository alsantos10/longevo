-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 29-Jan-2017 às 15:32
-- Versão do servidor: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `longevo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `chamados`
--

CREATE TABLE IF NOT EXISTS `chamados` (
`id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `observacao` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `chamados`
--

INSERT INTO `chamados` (`id`, `pedido_id`, `cliente_id`, `observacao`) VALUES
(1, 3, 2, ''),
(2, 4, 1, 'dasdas'),
(3, 4, 3, 'dadsa'),
(4, 4, 8, 'dasdas dasdasdas'),
(5, 9, 1, 'Modificações necessarias'),
(6, 5, 3, 'dsadas'),
(7, 9, 15, 'Providenciando nova senha'),
(8, 10, 15, 'Providenciando nova senha');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
`id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`) VALUES
(1, 'Fulano', 'fulano@email.com'),
(2, 'Sicrano', 'sicrano@email.com'),
(3, 'andre', 'joao@email.com'),
(4, 'Teste', 'test@teste.com'),
(5, 'Novo Nome', 'teste@email.com'),
(8, 'Fulano de Tal', 'fulanodetal@email.com'),
(9, 'Maria de Castro', ''),
(10, 'Kleber Jose', 'kleber@email.com'),
(11, 'Marcos Paulo', 'marcos@email.com'),
(15, 'Gustavo Silva', 'gustavo@email.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
`id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `titulo`) VALUES
(3, 'Problema com impressora'),
(4, 'Internet lenta'),
(5, 'Novo Pedido'),
(6, 'Novo Pedido'),
(7, 'Erro de Senha'),
(8, 'Sem permissão de acesso no sistema'),
(9, 'Sistema demora para carregar  a tela'),
(10, 'Não consigo logar no sistema'),
(11, 'Sistema operacional com problema'),
(12, 'Monitor não liga');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chamados`
--
ALTER TABLE `chamados`
 ADD PRIMARY KEY (`id`), ADD KEY `pedido_id` (`pedido_id`), ADD KEY `cliente_id` (`cliente_id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chamados`
--
ALTER TABLE `chamados`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `chamados`
--
ALTER TABLE `chamados`
ADD CONSTRAINT `chamados_clifk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
ADD CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
