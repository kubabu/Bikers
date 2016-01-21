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
        $commenter = new User($this->db);

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
            $fields[] = '`u`.`first_name` _first_name';
            $fields[] = '`u`.`last_name` _last_name';
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

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM routes_comments WHERE ID = :id");

        foreach ($data->data as $comment) {
            if (property_exists($comment, 'ID') && is_numeric($comment->ID)) {
                if ($stmt->execute([':id' => $comment->ID])) {
                    $res[] = $comment->ID;
                }
            }
        }

        return $res;
    }

    public function put($input)
    {
        $res = [];

        if (!empty($this->user_ID)) {
            $stmt = $this->db->prepare("UPDATE `routes_comments` SET `value` = :value WHERE `ID` = :ID");

            foreach ($input->data as $comment) {
                if (property_exists($comment, 'ID') && !empty($comment->ID) && property_exists($comment, 'value') && !empty($comment->value)) {
                    if ($stmt->execute([':ID' => $comment->ID, ':value' => $comment->value])) {
                        $res[] = $comment->ID;
                    }
                } else {
                    $post = $this->post((object) ['data' => [$comment]]);

                    if (!empty($post)) {
                        $res[] = $post[0];
                    }
                }
            }
        }

        return $res;
    }
}
