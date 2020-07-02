<?php

namespace Application\Models;

use Core\DbModelAbstract;

class ContactModel extends DbModelAbstract
{
    public function addMessage($data)
    {
        $query = <<<SQL
        INSERT INTO `messages` (`user_id`,`name`,`email`,`message`,`date`)
        VALUES (?,?,?,?,?)
SQL;
        $values = [
            $data['user_id'],
            $data['name'],
            $data['email'],
            $data['message'],
            date("Y-m-d H:i:s", time()),
        ];
        $this->execute($query, $values);
    }

    public function getPaginatedData($page, $items = 10)
    {
        $query = <<<___SQL
            SELECT 
            `messages`.*,
            `users`.`username`,
            `users`.`email` as `real_email`
            FROM `messages`
            LEFT JOIN `users` ON `messages`.`user_id` = `users`.`id`
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

    public function getLastX($limit = 5) {
        $query = <<<___SQL
            SELECT 
            `messages`.*,
            `users`.`username`,
            `users`.`email` as `real_email`
            FROM `messages`
            LEFT JOIN `users` ON `messages`.`user_id` = `users`.`id`
            ORDER BY `date_created` DESC
            LIMIT %s
___SQL;
        $query = str_replace('%s', $limit, $query);
        return $this->getData($query);
    }

    public function getNumberOfPages($itemsPerPage)
    {
        $allPages = $this->getData('SELECT COUNT(`id`) as `count` FROM `messages`');
        $allPages = !empty($allPages) ? $allPages[0]['count'] : 0;
        return (int)ceil($allPages/$itemsPerPage);
    }

    public function deleteMessage($messageId)
    {
        $this->execute("DELETE FROM `messages` WHERE `id` = ?", [$messageId]);
    }
}