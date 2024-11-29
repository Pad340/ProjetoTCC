CREATE
    DATABASE reifeitorio;

USE
    reifeitorio;

CREATE TABLE `user`
(
    `user_id`        int(11)      NOT NULL AUTO_INCREMENT,
    `name`           varchar(100) NOT NULL,
    `email`          varchar(50)  NOT NULL,
    `password`       varchar(75)  NOT NULL,
    `created_at`     datetime     NOT NULL,
    `updated_at`     datetime     NOT NULL,
    `status_account` tinyint(4)   NOT NULL DEFAULT 1 COMMENT '1 = Ativo',
    PRIMARY KEY (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


CREATE TABLE `category`
(
    `category_id` int(11)     NOT NULL AUTO_INCREMENT,
    `name`        varchar(50) NOT NULL,
    `status`      tinyint(4)  NOT NULL DEFAULT 1 COMMENT '1 = Ativo\\n0 = Inativo',
    PRIMARY KEY (`category_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

INSERT INTO `category` (`name`)
VALUES ('Salgados'),
       ('Bebidas'),
       ('Doces'),
       ('Chips'),
       ('Balas');

CREATE TABLE `seller`
(
    `seller_id`      int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`        int(11)      NOT NULL,
    `name`           varchar(100) NOT NULL,
    `cpf`            varchar(12)  NOT NULL,
    `phone_number`   varchar(13)  NOT NULL,
    `created_at`     datetime     NOT NULL,
    `updated_at`     datetime     NOT NULL,
    `status_account` tinyint(4)   NOT NULL DEFAULT 0 COMMENT '1 = Ativo',
    `licensed`       tinyint(4)   NOT NULL DEFAULT 0,
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
    `product_id`     int(11)        NOT NULL AUTO_INCREMENT,
    `name`           varchar(100)   NOT NULL,
    `category_id`    int(11)        NOT NULL,
    `price`          decimal(10, 2) NOT NULL,
    `qtt_stock`      int(11)        NOT NULL,
    `status_product` tinyint(4)     NOT NULL DEFAULT 1 COMMENT '1 = Ativo',
    `seller_id`      int(11)        NOT NULL,
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

CREATE TABLE `reserve`
(
    `reserve_id`  int(11)        NOT NULL AUTO_INCREMENT,
    `user_id`     int(11)        NOT NULL,
    `reserved_at` datetime       NOT NULL,
    `total_value` decimal(10, 2) NOT NULL,
    `redeemed`    tinyint(4)     NOT NULL DEFAULT 0,
    PRIMARY KEY (`reserve_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `product_reserve`
(
    `product_reserve_id` int(11)        NOT NULL AUTO_INCREMENT,
    `product_id`         int(11)        NOT NULL,
    `reserve_id`         int(11)        NOT NULL,
    `quantity`           int(11)        NOT NULL,
    `total_value`        decimal(10, 2) NOT NULL,
    PRIMARY KEY (`product_reserve_id`),
    KEY `reseved_product_idx` (`product_id`),
    KEY `reseve_idx` (`reserve_id`),
    CONSTRAINT `reserve` FOREIGN KEY (`reserve_id`) REFERENCES `reserve` (`reserve_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `reserved_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;