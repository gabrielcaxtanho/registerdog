-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 26/08/2024 às 21:45
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
-- Estrutura para tabela `dono`
--

CREATE TABLE `dono` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `pet`
--

CREATE TABLE `pet` (
  `id` int(11) NOT NULL,
  `idDono` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `idade` int(11) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `idusers` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `idStatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `raca`
--

CREATE TABLE `raca` (
  `id` int(11) NOT NULL,
  `idpet` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `idusers` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL,
  `descricao` enum('banho','tosa','banho e tosa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nomeSobrenome` varchar(255) NOT NULL,
  `nomePetshop` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) NOT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `dataInicio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `dono`
--
ALTER TABLE `dono`
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
-- Índices de tabela `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDono` (`idDono`),
  ADD KEY `idusers` (`idusers`),
  ADD KEY `idservico` (`idservico`),
  ADD KEY `idStatus` (`idStatus`);

--
-- Índices de tabela `raca`
--
ALTER TABLE `raca`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idpet` (`idpet`),
  ADD KEY `idusers` (`idusers`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dono`
--
ALTER TABLE `dono`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `frequencia`
--
ALTER TABLE `frequencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `permissao_usuarios`
--
ALTER TABLE `permissao_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pet`
--
ALTER TABLE `pet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `raca`
--
ALTER TABLE `raca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `dono`
--
ALTER TABLE `dono`
  ADD CONSTRAINT `dono_ibfk_1` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `frequencia`
--
ALTER TABLE `frequencia`
  ADD CONSTRAINT `frequencia_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `dono` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencia_ibfk_2` FOREIGN KEY (`idPet`) REFERENCES `pet` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencia_ibfk_3` FOREIGN KEY (`idServico`) REFERENCES `servico` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`idDono`) REFERENCES `dono` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pet_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pet_ibfk_3` FOREIGN KEY (`idservico`) REFERENCES `servico` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pet_ibfk_4` FOREIGN KEY (`idStatus`) REFERENCES `status` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `raca`
--
ALTER TABLE `raca`
  ADD CONSTRAINT `raca_ibfk_1` FOREIGN KEY (`idpet`) REFERENCES `pet` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `raca_ibfk_2` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
