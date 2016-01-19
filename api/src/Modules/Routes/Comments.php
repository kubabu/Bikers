<?php

namespace Modules\Routes;


use Modules\Basic\BasicModule;
use Modules\Users\User;

class Comments extends BasicModule
{
    public function post($input) {
        $res = [];

        $q = "INSERT INTO `routes_comments` (`route_ID`, `user_ID`, `value`, `date_create`) VALUES (:route, :user, :val, NOW() )";

        $stmt = $this->db->prepare($q);

        foreach ($input->data as $rt_comment) {
            if ($stmt->execute([
                ':route' => $rt_comment->route_ID,
                ':user' => $this->user_ID,
                ':val' => $rt_comment->value
            ])) {
                $res[] = $this->db->lastInsertId();
            }
        }

        return $res;
    }

    public function get($data)
    {
        $res = [];

        $q = " FROM routes_comments rc";
        $fields = ['`rc`.`route_ID`', '`rc`.`user_ID`', '`rc`.`value`', '`rc`.`date_create`'];
        $wheres = [];
        $params = [];

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (property_exists($data, 'route_ID') && !empty($data->route_ID)) {
            $wheres[] = 'route_ID = :route';
            $params[':route'] = $data->route_ID;
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

        $q .= " ORDER BY `rc`.date_create DESC";

        $q = "SELECT " . implode(', ', $fields) . $q;

        $stmt = $this->db->prepare($q);

        if ($stmt->execute($params)) {
            if ($comments = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                $res = $comments;
            }
        }

        return $res;
    }
}
