<?php

namespace Application\Models;

use Core\DbModelAbstract;

class CommentModel extends DbModelAbstract
{
    public function addComment($data)
    {
        $query = <<<SQL
            INSERT INTO `comments` (`user_id`, `picture_id`, `text`, `date_created`)
            VALUES
            (?,?,?,?)
SQL;
        $values = [
            $data['user_id'],
            $data['picture_id'],
            $data['text'],
            date("Y-m-d H:i:s", time()),
        ];
        return $this->execute($query, $values);
    }

    public function getById($commentId)
    {
        $query = <<<SQL
            SELECT
            `comments`.*,
            `pictures`.`user_id` as `picture_owner_id`
            FROM `comments`
            LEFT JOIN `pictures` ON `comments`.`picture_id` = `pictures`.`id`
            WHERE `comments`.`id` = ? 
SQL;

        return $this->getFirst($query, [$commentId]);
    }

    public function getCommentsForPicture($pictureId)
    {
        $query = <<<SQL
            SELECT
            `comments`.*,
            `users`.`username`
            FROM
            `comments`
            LEFT JOIN `users` ON `comments`.`user_id` = `users`.`id`
            WHERE `picture_id` = ?
            ORDER BY `date_created` DESC
SQL;

        return $this->getData($query, [$pictureId]);
    }

    public function getLastX($limit = 5)
    {
        $query = <<<SQL
            SELECT 
            `comments`.*,
            `users`.`username` as `author`,
            `pictures`.`path` as `picture_path`
            FROM `comments`
            LEFT JOIN `users` ON `comments`.`user_id` = `users`.`id`
            LEFT JOIN `pictures` ON `comments`.`picture_id` = `pictures`.`id`
            ORDER BY `date_created` DESC 
            LIMIT %s;
SQL;
        $query = str_replace('%s', $limit, $query);
        return $this->getData($query);
    }

    public function getNumberOfPictureComments($pictureId)
    {
        $result = $this->getFirst(
            "SELECT count(`id`) as `count` FROM `comments` WHERE `picture_id` = ?",
            [$pictureId]
        );
        return $result['count'];
    }

    public function deleteById($commentId)
    {
        $this->execute("DELETE FROM `comments` WHERE `id` = ?", [$commentId]);
    }

    public function deletePictureComments($pictureId)
    {
        $query = "DELETE FROM `comments` WHERE `picture_id` = ?";
        $this->execute($query, [$pictureId]);
    }

    public function deleteUserComments($userId)
    {
        $query = "DELETE FROM `comments` WHERE `user_id` = ?";
        $this->execute($query, [$userId]);
    }
}