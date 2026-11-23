

CREATE DATABASE IF NOT EXISTS `user_system`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `user_system`;


CREATE TABLE IF NOT EXISTS `users` (
  `id`       INT          NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email`    VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `users` ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `solicitacoes` (
  `id`                 INT          NOT NULL AUTO_INCREMENT,
  `user_id`            INT          NULL DEFAULT NULL,
  `nome`               VARCHAR(100) NOT NULL,
  `empresa`            VARCHAR(150) NULL DEFAULT NULL,
  `telefone`           VARCHAR(30)  NOT NULL,
  `email`              VARCHAR(255) NOT NULL,
  `veiculo_disponivel` ENUM('sim','nao') NOT NULL,
  `marca`              VARCHAR(80)  NULL DEFAULT NULL,
  `modelo`             VARCHAR(80)  NULL DEFAULT NULL,
  `ano`                CHAR(4)      NULL DEFAULT NULL,
  `cidade_estado`      VARCHAR(100) NULL DEFAULT NULL,
  `servico`            VARCHAR(255) NOT NULL,
  `finalidade`         VARCHAR(50)  NOT NULL,
  `descricao`          TEXT         NULL DEFAULT NULL,
  `created_at`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_solicitacoes_user` (`user_id`, `created_at`),
  CONSTRAINT `fk_solicitacoes_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
