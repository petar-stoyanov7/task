<?php

namespace Application\Models;

use Core\DbModelAbstract;

class UserModel extends DbModelAbstract
{
    public function listUsers()
    {
        return $this->getData("SELECT * FROM `users`");
    }

    public function getPaginatedData($page, $items = 10)
    {
        $query = <<<___SQL
            SELECT * FROM `users`
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

    public function getLastX($limit = 5)
    {
        $query = <<<SQL
            SELECT * FROM `users`
            ORDER BY `date_created` DESC 
            LIMIT %s;
SQL;
        $query = str_replace('%s', $limit, $query);
        return $this->getData($query);
    }

    public function getNumberOfPages($itemsPerPage)
    {
        $allPages = $this->getFirst('SELECT COUNT(`id`) as `count` FROM `users`');
        return (int)ceil($allPages['count']/$itemsPerPage);
    }

    public function register($postValues)
    {
        $query = <<<___SQL
            INSERT INTO `users` (`username`,`password`,`email`,`date_created`, `firstname`, `lastname`)
            VALUES
            (?,?,?,?,?,?)
___SQL;
        $values = [
            $postValues['username'],
            password_hash($postValues['password'], PASSWORD_DEFAULT),
            $postValues['email'],
            date("Y-m-d H:i:s", time()),
            $postValues['firstname'],
            $postValues['lastname']
        ];

        return $this->execute($query, $values);
    }

    public function updatePictureCount($userId)
    {
        $currentCount = $this->getUserLimit($userId);
        $currentCount = empty($currentCount) ? 0 : $currentCount;
        $query = "UPDATE `users` SET `pictures_count` = ? WHERE `id` = ?";
        $this->execute($query, [$currentCount, $userId]);
        if ($_SESSION['user']['id'] === $userId) {
            $user = $this->getById($userId);
            $this->_startSession($user);
        }
    }

    public function setProfilePicture($userId, $path)
    {
        $query = "UPDATE `users` SET `profile_picture` = ? WHERE `id` = ?";
        $this->execute($query, [$path, $userId]);
    }

    public function updateUser($userId, $data)
    {
        $query = <<<___SQL
            UPDATE `users`
            SET 
            `firstname` = ?,
            `lastname` = ?,
            `email` = ?
___SQL;
        $values = [
            $data['firstname'],
            $data['lastname'],
            $data['email']
        ];
        if (!empty($data['password'])) {
            $query .= ", `password` = ?";
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $query .= " WHERE `id` = ?";
        $values[] = $userId;

        return $this->execute($query, $values);
    }

    public function deleteUser($userId) {
        $this->execute("DELETE FROM `users` WHERE `id` = ?",[$userId]);
    }

    public function login($username,$password)
    {
        $userList = $this->listUsers();
        foreach ($userList as $user) {
            if (
                $user['username'] === $username &&
                password_verify($password, $user['password'])
            ) {
                $this->_startSession($user);
                return true;
            }
        }
        return false;
    }

    public function getById($userId)
    {
        $result = $this->getData("SELECT * FROM `users` WHERE `id` = ?", [$userId]);
        if (!empty($result)) {
            return $result[0];
        }
        return $result;
    }

    public function getUserLimit($userId)
    {
        $query = "SELECT COUNT(`id`) as `count` FROM `pictures` WHERE `user_id` = ?";
        $result = $this->getData($query, [$userId]);
        if (!empty($result)) {
            return $result[0]['count'];
        }
        return $result;
    }

    private function _startSession($userArray)
    {
        $array = $userArray;
        $sessionArray = $userArray;
        unset($sessionArray['password']);
        unset($sessionArray['token']);
        /** used switch in case we add other types in future */
        switch($sessionArray['group_id']) {
            case '1':
                $sessionArray['group'] = 'users';
                break;
            case '2':
                $sessionArray['group'] = 'admins';
                break;
            default:
                $sessionArray['group'] = 'users';
                break;
        }
        unset($sessionArray['group_id']);
        $_SESSION['user'] = $sessionArray;
    }
}