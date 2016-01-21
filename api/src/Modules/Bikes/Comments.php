<?php

namespace Modules\Bikes;


use Modules\Basic\BasicModule;
use Modules\Users\User;

class Comments extends BasicModule
{
    public function post($input) {
        $res = [];

        $q = "INSERT INTO `bikes_comments` (`bike_ID`, `user_ID`, `value`, `date_create`) VALUES (:bike, :user, :val, NOW() )";

        $stmt = $this->db->prepare($q);

        foreach ($input->data as $bk_comment) {
            if ($stmt->execute([
                ':route' => $bk_comment->bike_ID,
                ':user' => $this->user_ID,
                ':val' => $bk_comment->value
            ])) {
                $res[] = $this->db->lastInsertId();
            }
        }

        return $res;
    }

    public function get($data)
    {
        $res = [];

        $q = " FROM bikes_comments bc";
        $fields = ['`bc`.`bike_ID`', '`bc`.`user_ID`', '`bc`.`value`', '`bc`.`date_create`'];
        $wheres = [];
        $params = [];

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (property_exists($data, 'bike_ID') && !empty($data->bike_ID)) {
            $wheres[] = 'bike_ID = :bike';
            $params[':bike'] = $data->bike_ID;
        }

        if (property_exists($data, 'user_ID') && !empty($data->user_ID)) {
            $wheres[] = 'user_ID = :user';
            $params[':user'] = $data->route_ID;
        }

        if (property_exists($data, '_users') && !empty($data->_users)) {
            $q .= ' INNER JOIN users u ON u.ID = rc.user_ID';
            $fields[] = '`u`.`first_name`';
            $fields[] = '`u`.`last_name`';
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        $q .= " ORDER BY `bc`.date_create DESC";

        $q = "SELECT " . implode(', ', $fields) . $q;

        $stmt = $this->db->prepare($q);

        if ($stmt->execute($params)) {
            if ($comments = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                $res = $comments;
            }
        }

        return $res;
    }

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM bikes_comments WHERE ID = :id");

        foreach ($data->data as $comment) {
            if (property_exists($comment, 'ID') && is_numeric($comment->ID)) {
                if ($stmt->execute([':id' => $comment->ID])) {
                    $res[] = $comment->ID;
                }
            }
        }

        return $res;
    }
}
