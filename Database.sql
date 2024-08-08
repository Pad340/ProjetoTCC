CREATE DATABASE reifeitorio;

CREATE TABLE `user`
(
    `user_id`        int(11)     NOT NULL AUTO_INCREMENT,
    `name`           varchar(50) NOT NULL,
    `email`          varchar(50) NOT NULL,
    `password`       varchar(75) NOT NULL,
    `created_at`     datetime    NOT NULL,
    `updated_at`     datetime    NOT NULL,
    `status_account` int(1)      NOT NULL DEFAULT 1 COMMENT '1 = Ativo\\n0 = Inativo',
    PRIMARY KEY (`user_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;