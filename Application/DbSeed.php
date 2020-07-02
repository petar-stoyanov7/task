<?php
use Core\DbModelAbstract;

class DbSeed extends DbModelAbstract
{
    public function setupDb()
    {
        $sql = <<<___SQL
            CREATE TABLE `user_groups` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              PRIMARY KEY (`id`))
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            INSERT INTO `user_groups` (`name`) VALUES ('users');
            INSERT INTO `user_groups` (`name`) VALUES ('admins');
___SQL;
        $this->execute($sql);


        $sql = <<<___SQL
            CREATE TABLE `users` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `group_id` INT NOT NULL DEFAULT 1,
              `username` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `password` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `firstname` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `lastname` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `token` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL DEFAULT NULL,
              `profile_picture` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL DEFAULT NULL,
              `pictures_count` INT NULL DEFAULT 0,
              `comments_count` INT NULL DEFAULT 0,
              `date_created` DATETIME NOT NULL,
              `date_modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `grp_id_idx` (`group_id` ASC) VISIBLE,
              CONSTRAINT `grp_id`
                FOREIGN KEY (`group_id`)
                REFERENCES `user_groups` (`id`)
                ON DELETE RESTRICT
                ON UPDATE RESTRICT)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE `pictures` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `user_id` INT NOT NULL,
              `title` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `path` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `date_created` DATETIME NOT NULL,
              `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `usr_id_idx` (`user_id` ASC) VISIBLE,
              CONSTRAINT `usr_id`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;            
___SQL;
        $this->execute($sql);


        $sql = <<<___SQL
            CREATE TABLE `comments` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `picture_id` INT NOT NULL,
              `user_id` INT NOT NULL,
              `text` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `date_created` DATETIME NOT NULL,
              `date_updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `fk_cm_usr_id_idx` (`user_id` ASC) VISIBLE,
              INDEX `fk_cm_pic_id_idx` (`picture_id` ASC) VISIBLE,
              CONSTRAINT `fk_cm_usr_id`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_cm_pic_id`
                FOREIGN KEY (`picture_id`)
                REFERENCES `pictures` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;

___SQL;
        $this->execute($sql);



        $sql = <<<___SQL
            CREATE TABLE `messages` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `user_id` INT NULL DEFAULT NULL,
              `name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `message` MEDIUMTEXT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              `date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `msg_usr_id_idx` (`user_id` ASC) VISIBLE,
              CONSTRAINT `msg_usr_id`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;
___SQL;
        $this->execute($sql);


        $sql = <<<___SQL
            CREATE TABLE `tags` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
              PRIMARY KEY (`id`))
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;
___SQL;
        $this->execute($sql);

        $sql = <<<___SQL
            CREATE TABLE `picture_tag` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `picture_id` INT NOT NULL,
              `tag_id` INT NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `pt_pic_id_idx` (`picture_id` ASC) VISIBLE,
              INDEX `pt_tag_id_idx` (`tag_id` ASC) VISIBLE,
              CONSTRAINT `pt_pic_id`
                FOREIGN KEY (`picture_id`)
                REFERENCES `pictures` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
              CONSTRAINT `pt_tag_id`
                FOREIGN KEY (`tag_id`)
                REFERENCES `tags` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8;
___SQL;
        $this->execute($sql);

    }
}