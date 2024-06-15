-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/06/2024 às 16:52
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
-- Banco de dados: `registerpet`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `donos`
--

CREATE TABLE `donos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `contato` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `donos`
--

INSERT INTO `donos` (`id`, `nome`, `contato`) VALUES
(1, 'gabriel', '65165165161'),
(2, 'alice', '646651651');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `raca_id` int(11) DEFAULT NULL,
  `dono_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pets`
--

INSERT INTO `pets` (`id`, `nome`, `raca_id`, `dono_id`) VALUES
(1, 'beto', 1, 1),
(2, 'beto ', 1, 2),
(3, 'beto', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `racas`
--

CREATE TABLE `racas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `racas`
--

INSERT INTO `racas` (`id`, `nome`) VALUES
(1, 'pug');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitas`
--

CREATE TABLE `visitaspet` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `dataVisita` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `visitas`
--

INSERT INTO `visitaspet` (`id`, `pet_id`, `servico_id`, `dataVisita`) VALUES
(1, 1, NULL, '2024-06-12'),
(2, 2, NULL, '2024-06-12'),
(3, 3, NULL, '2024-06-12');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `donos`
--
ALTER TABLE `donos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raca_id` (`raca_id`),
  ADD KEY `dono_id` (`dono_id`);

--
-- Índices de tabela `racas`
--
ALTER TABLE `racas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `visitas`
--
ALTER TABLE `visitaspet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `donos`
--
ALTER TABLE `donos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `racas`
--
ALTER TABLE `racas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitas`
--
ALTER TABLE `visitaspet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`raca_id`) REFERENCES `racas` (`id`),
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`dono_id`) REFERENCES `donos` (`id`);

--
-- Restrições para tabelas `visitas`
--
ALTER TABLE `visitaspet`
  ADD CONSTRAINT `visitaspet_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`),
  ADD CONSTRAINT `visitaspet_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
