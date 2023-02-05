DROP TABLE IF EXISTS `fwlink`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `fwlink` (
    `id` INT(10) UNSIGNED PRIMARY KEY,
    `url` TEXT NOT NULL
);

CREATE TABLE `user` (
    `username` VARCHAR(16) PRIMARY KEY,
    `password` VARCHAR(32) NOT NULL
);