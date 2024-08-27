CREATE DATABASE reifeitorio;

USE reifeitorio;

CREATE TABLE `user`
(
    `user_id`        int(11)      NOT NULL,
    `name`           varchar(100) NOT NULL,
    `email`          varchar(50)  NOT NULL,
    `password`       varchar(75)  NOT NULL,
    `created_at`     datetime     NOT NULL,
    `updated_at`     datetime     NOT NULL,
    `status_account` int(11)      NOT NULL DEFAULT 1 COMMENT '1 = Ativo\n0 = Inativo',
    PRIMARY KEY (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `category`
(
    `category_id` int(11)     NOT NULL AUTO_INCREMENT,
    `name`        varchar(50) NOT NULL,
    `status`      int(11)     NOT NULL DEFAULT 1 COMMENT '1 = Ativo\n0 = Inativo',
    PRIMARY KEY (`category_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `seller`
(
    `seller_id` int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`   int(11)      NOT NULL,
    `name`      varchar(100) NOT NULL,
    PRIMARY KEY (`seller_id`),
    KEY `seller_user_idx` (`user_id`),
    CONSTRAINT `seller_user`
        FOREIGN KEY (`user_id`)
            REFERENCES `user` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


CREATE TABLE `product`
(
    `product_id`  int(11)        NOT NULL AUTO_INCREMENT,
    `name`        varchar(100)   NOT NULL,
    `category_id` int(11)        NOT NULL,
    `price`       decimal(10, 2) NOT NULL,
    `qtt_stock`   int(11)        NOT NULL,
    `seller_id`   int(11)        NOT NULL,
    PRIMARY KEY (`product_id`),
    KEY `product_category_idx` (`category_id`),
    KEY `product_seller_idx` (`seller_id`),
    CONSTRAINT `product_category`
        FOREIGN KEY (`category_id`)
            REFERENCES `category` (`category_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
    CONSTRAINT `product_seller`
        FOREIGN KEY (`seller_id`)
            REFERENCES `seller` (`seller_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;