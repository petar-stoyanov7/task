<?php

namespace Application\Models;

use Core\DbModelAbstract;

class PictureModel extends DbModelAbstract
{
    public function add($postValues)
    {
        $query = <<<___SQL
            INSERT INTO `pictures` (`user_id`, `title`, `path`, `date_created`) 
            VALUES (?,?,?,?)
___SQL;
        $values = [
            $postValues['user_id'],
            $postValues['picture_title'],
            $postValues['file'],
            date("Y-m-d H:i:s", time()),
        ];
        $this->execute($query, $values);
    }

    public function getLast($number = 10)
    {
        $query = <<<___SQL
            SELECT 
            `pictures`.*,
            `users`.`username`
            FROM `pictures`
            LEFT JOIN `users` ON `pictures`.`user_id` = `users`.`id`            
            ORDER BY `date_created` DESC
            LIMIT 10
___SQL;
        return $this->getData($query);

    }

    public function getById($pictureId)
    {
        $query = <<<___SQL
            SELECT
            `pictures`.*,
            `users`.`username`
            FROM `pictures`
            LEFT JOIN `users` on `pictures`.`user_id` = `users`.`id`
            WHERE `pictures`.`id` = ?
___SQL;

        $result = $this->getData($query, [$pictureId]);
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }

    public function getPaginatedData($page, $items = 10)
    {
        $query = <<<___SQL
            SELECT 
            `pictures`.*,
            `users`.`username`
            FROM `pictures`
            LEFT JOIN `users` ON `pictures`.`user_id` = `users`.`id`            
            ORDER BY `date_created` DESC
___SQL;
        if ($page > 1) {
            $offset = ($page - 1) * $items;
            $query .= " LIMIT {$items} OFFSET {$offset}";
            return $this->getData($query);
        } else {
            $query .= " LIMIT 10";
            return $this->getData($query);
        }
    }

    public function editPicture($values)
    {
        $query = <<<SQL
            UPDATE `pictures`
            SET
            `title` = ?
SQL;
        $data[] = $values['picture_title'];
        if (!empty($values['new_path'])) {
            $query .= ", `path` = ?";
            $data[] = $values['new_path'];
        }
        $query .= " WHERE `id` = ?";
        $data[] = $values['picture_id'];

        $this->execute($query, $data);
    }

    public function deletePicture($pictureId)
    {
        $query = "DELETE FROM `pictures` WHERE `id` = ?";
        $this->execute($query, [$pictureId]);
    }

    public function getNumberOfPages($itemsPerPage)
    {
        $allPages = $this->getData('SELECT COUNT(`id`) as `count` FROM `pictures`');
        $allPages = !empty($allPages) ? $allPages[0]['count'] : 0;
        return (int)ceil($allPages/$itemsPerPage);
    }

    public function getLastX($limit = 5)
    {
        $query = <<<SQL
            SELECT 
            `pictures`.*,
            `users`.`username` as `author`
            FROM `pictures`
            LEFT JOIN `users` ON `pictures`.`user_id` = `users`.`id`
            ORDER BY `date_created` DESC 
            LIMIT %s;
SQL;
        $query = str_replace('%s', $limit, $query);
        return $this->getData($query);
    }

    public function getUserPictures($userId)
    {
        $query = "SELECT * FROM `pictures` WHERE `user_id` = ?";
        return $this->getData($query, [$userId]);
    }
}