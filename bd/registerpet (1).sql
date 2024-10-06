-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 06/10/2024 às 14:01
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `registros`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `donos`
--

CREATE TABLE `donos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `idusers` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `donos`
--

INSERT INTO `donos` (`id`, `nome`, `telefone`, `idusers`, `data`) VALUES
(27, 'Gabriel Vieira', '6199584578', NULL, NULL),
(31, 'ze', '6195581583', NULL, NULL),
(33, 'bruna gomes', '454', 5, '2024-10-06 08:54:40'),
(34, 'bruna gomes', '45448484848654', 5, '2024-10-06 08:54:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `frequencia`
--

CREATE TABLE `frequencia` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idPet` int(11) NOT NULL,
  `idServico` int(11) DEFAULT NULL,
  `dataVisita` date DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `frequencia`
--

INSERT INTO `frequencia` (`id`, `idCliente`, `idPet`, `idServico`, `dataVisita`, `observacao`) VALUES
(1, 27, 10, 1, '2024-09-14', 'nenhums'),
(2, 31, 11, 2, '2024-09-14', 'esta com problema na pata esquerda');

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissao_usuarios`
--

CREATE TABLE `permissao_usuarios` (
  `id` int(11) NOT NULL,
  `nome` enum('1ADM','3COL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `dono` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `idade` int(11) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `idRaca` int(11) DEFAULT NULL,
  `contato` int(11) NOT NULL,
  `hora` time NOT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pets`
--

INSERT INTO `pets` (`id`, `dono`, `nome`, `idade`, `observacao`, `idusers`, `idRaca`, `contato`, `hora`, `data`) VALUES
(10, 27, 'Beto', NULL, NULL, NULL, 26, 0, '00:00:00', NULL),
(11, 31, 'kallew', NULL, NULL, NULL, 30, 0, '00:00:00', NULL),
(30, 1, 'Rex', 5, 'Cachorro muito ativo', NULL, 1, 1234567890, '10:00:00', '2024-10-05'),
(32, 0, 'kallew', 8, 'lçclm,sd', NULL, 0, 589563224, '16:59:14', '2024-10-05'),
(33, 0, 'kallew', 8, 'lçclm,sd', NULL, 0, 589563224, '17:02:03', '2024-10-05'),
(36, 31, 'kallew', 5, 'nehuma', 5, 0, 2147483647, '08:47:57', '2024-10-06'),
(37, 31, 'beto', 4, 'nehuma', 5, NULL, 2147483647, '08:51:23', '2024-10-06'),
(38, 33, 'beto', 4, 'nehuma', 5, NULL, 454, '08:54:40', '2024-10-06'),
(39, 34, 'beto', 4, 'nehuma', 5, NULL, 2147483647, '08:54:47', '2024-10-06'),
(40, 34, 'bb', 4, 'nehuma', 5, 26, 2147483647, '09:00:49', '2024-10-06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `racas`
--

CREATE TABLE `racas` (
  `id` int(11) NOT NULL,
  `idpet` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `idusers` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `racas`
--

INSERT INTO `racas` (`id`, `idpet`, `nome`, `idusers`) VALUES
(26, NULL, 'Pug', NULL),
(30, NULL, 'pastor alemão', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `idServico` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`idServico`, `descricao`) VALUES
(1, 'banho'),
(2, 'tosa'),
(3, 'banho e tosa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `nome` enum('esperando','banho','tosa','secando','pronto') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `userperm`
--

CREATE TABLE `userperm` (
  `id` int(11) NOT NULL,
  `codCad` varchar(10) NOT NULL,
  `cadNome` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `userperm`
--

INSERT INTO `userperm` (`id`, `codCad`, `cadNome`) VALUES
(1, '1ADM', 'Administra'),
(2, '2GES', 'Gestor(a)'),
(3, '3COL', 'Colaborado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `nomePetshop` varchar(255) DEFAULT NULL,
  `celular` varchar(255) NOT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `dataInicio` datetime DEFAULT NULL,
  `usersAprov` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userperm` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`userid`, `userName`, `nomePetshop`, `celular`, `cidade`, `estado`, `password`, `dataInicio`, `usersAprov`, `userEmail`, `userperm`) VALUES
(1, 'Bruna Coelho', 'Bruna Coelho', '6195688106', 'CIDADE OCIDENTAL', 'GO', '$2y$10$LnEZyf0IuLAkpdOL3WPpo.aYU.2BPXu.x0BbVxuhIFWXmfk/A3pc.', '2024-08-28 00:00:00', 'APROV', 'bruna@teste', '3COL'),
(2, 'BRUNA GOMES COELHO', 'RegisterPet', '61995688106', 'Cidade Ocidental', 'GO', '$2y$10$f0kKnph3aoWnmoVnvxP83eVqAxFwAVTJLkIOyPm3LovF/jlwJ0LQ6', '2024-08-28 00:00:00', 'APROV', 'bruna@teste.com', '3COL'),
(3, 'Gabriel Vieira', 'RegisterPet', '4191010550', 'Brasília, DF', 'DF', '$2y$10$ayWRvIM47PUQhrAkJoXxmeXpsSE/vP7lG1uux3wXp24qNdlfmhw3C', '2024-08-28 00:00:00', 'AGRDD', 'bruna@teste', '3COL'),
(4, '', '', '', '', '', '$2y$10$C6soFKngos0tdS2aJZD1gOiq6MDDNYbGOKKxFw/pjY2wLZ8ihzpCu', '2024-09-16 00:00:00', 'AGRDD', '', '3COL'),
(5, 'bruna gomes', 'odin <3', '6199568810564', 'Cidade Ocidental', 'GO', '$2y$10$lBfpWT26y/rKfXgCtyNTbuEIjoigntcaMGIGrmCXLcH4wnOwEc1SK', '2024-10-05 15:25:01', 'APROV', 'bruhbg55@gmail.com', '3COL');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `donos`
--
ALTER TABLE `donos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD KEY `idusers` (`idusers`);

--
-- Índices de tabela `frequencia`
--
ALTER TABLE `frequencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idPet` (`idPet`),
  ADD KEY `idServico` (`idServico`);

--
-- Índices de tabela `permissao_usuarios`
--
ALTER TABLE `permissao_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idusers` (`idusers`);

--
-- Índices de tabela `racas`
--
ALTER TABLE `racas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idusers` (`idusers`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`idServico`);

--
-- Índices de tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `userperm`
--
ALTER TABLE `userperm`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `donos`
--
ALTER TABLE `donos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `frequencia`
--
ALTER TABLE `frequencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `permissao_usuarios`
--
ALTER TABLE `permissao_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `racas`
--
ALTER TABLE `racas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `idServico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `userperm`
--
ALTER TABLE `userperm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `donos`
--
ALTER TABLE `donos`
  ADD CONSTRAINT `donos_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`userid`) ON DELETE SET NULL;

--
-- Restrições para tabelas `frequencia`
--
ALTER TABLE `frequencia`
  ADD CONSTRAINT `frequencia_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `donos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencia_ibfk_2` FOREIGN KEY (`idPet`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencia_ibfk_3` FOREIGN KEY (`idServico`) REFERENCES `servico` (`idServico`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`userid`) ON DELETE SET NULL;

--
-- Restrições para tabelas `racas`
--
ALTER TABLE `racas`
  ADD CONSTRAINT `racas_ibfk_1` FOREIGN KEY (`idpet`) REFERENCES `pets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `racas_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`userid`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
