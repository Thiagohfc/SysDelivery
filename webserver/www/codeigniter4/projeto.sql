-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Tempo de geração: 16/04/2025 às 21:29
-- Versão do servidor: 8.0.41
-- Versão do PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `categorias_id` int NOT NULL,
  `categorias_nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`categorias_id`, `categorias_nome`) VALUES
(1, 'Sanduíches2'),
(2, 'Pizzas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidades`
--

CREATE TABLE `cidades` (
  `cidades_id` int NOT NULL,
  `cidades_nome` varchar(255) NOT NULL,
  `cidades_uf` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `cidades`
--

INSERT INTO `cidades` (`cidades_id`, `cidades_nome`, `cidades_uf`) VALUES
(1, 'Ceres', 'GO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `imgprodutos`
--

CREATE TABLE `imgprodutos` (
  `imgprodutos_id` int NOT NULL,
  `imgprodutos_link` varchar(255) NOT NULL,
  `imgprodutos_descricao` text NOT NULL,
  `imgprodutos_produtos_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `imgprodutos`
--

INSERT INTO `imgprodutos` (`imgprodutos_id`, `imgprodutos_link`, `imgprodutos_descricao`, `imgprodutos_produtos_id`) VALUES
(1, 'uploads/20250416/1744801962_9165428592f42702f939.jpg', 'Pizza1', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `produtos_id` int NOT NULL,
  `produtos_nome` varchar(255) NOT NULL,
  `produtos_descricao` text NOT NULL,
  `produtos_preco_custo` float(9,2) NOT NULL,
  `produtos_preco_venda` float(9,2) NOT NULL,
  `produtos_categorias_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`produtos_id`, `produtos_nome`, `produtos_descricao`, `produtos_preco_custo`, `produtos_preco_venda`, `produtos_categorias_id`) VALUES
(1, 'Pizza Calabresa', 'Pizza Calabresa', 35.00, 60.00, 2),
(2, 'X-Tudo', 'X-Tudo', 15.50, 24.99, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuarios_id` int NOT NULL,
  `usuarios_nome` varchar(255) NOT NULL,
  `usuarios_sobrenome` varchar(255) NOT NULL,
  `usuarios_email` varchar(255) NOT NULL,
  `usuarios_cpf` varchar(14) NOT NULL,
  `usuarios_data_nasc` date NOT NULL,
  `usuarios_nivel` int NOT NULL,
  `usuarios_fone` varchar(15) NOT NULL,
  `usuarios_senha` varchar(32) NOT NULL,
  `usuarios_data_cadastro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuarios_id`, `usuarios_nome`, `usuarios_sobrenome`, `usuarios_email`, `usuarios_cpf`, `usuarios_data_nasc`, `usuarios_nivel`, `usuarios_fone`, `usuarios_senha`, `usuarios_data_cadastro`) VALUES
(1, 'Vilson', 'Soares de Siqueira', 'vilsonsoares@gmail.com', '999.999.999-99', '1981-12-03', 1, '6398474-3380', 'e10adc3949ba59abbe56e057f20f883e', '0000-00-00 00:00:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categorias_id`);

--
-- Índices de tabela `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`cidades_id`);

--
-- Índices de tabela `imgprodutos`
--
ALTER TABLE `imgprodutos`
  ADD PRIMARY KEY (`imgprodutos_id`),
  ADD KEY `fk_imagens_produtos` (`imgprodutos_produtos_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`produtos_id`),
  ADD KEY `fk_categorias_produto` (`produtos_categorias_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuarios_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categorias_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cidades`
--
ALTER TABLE `cidades`
  MODIFY `cidades_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `imgprodutos`
--
ALTER TABLE `imgprodutos`
  MODIFY `imgprodutos_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `produtos_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuarios_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `imgprodutos`
--
ALTER TABLE `imgprodutos`
  ADD CONSTRAINT `fk_imagens_produtos` FOREIGN KEY (`imgprodutos_produtos_id`) REFERENCES `produtos` (`produtos_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_categorias_produto` FOREIGN KEY (`produtos_categorias_id`) REFERENCES `categorias` (`categorias_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
