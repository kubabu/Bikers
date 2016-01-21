<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 25.11.15
 * Time: 18:55
 */

namespace Modules\Users;

use Modules\Basic\BasicModule;

class User extends BasicModule
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    public function get($data)
    {
        $data = $this->sanitizeInput($data);

        $q = " FROM users u";
        $fields = ['u.*'];
        $params = [];
        $wheres = [];

        if (property_exists($data, '_not_me') && !empty($data->_not_me)) {
            $wheres[] = 'ID != :user';
            $params[':user'] = $this->user_ID;
        } elseif (property_exists($data, '_me') && !empty($data->_me)) {
            $wheres[] = 'ID = :user';
            $params[':user'] = $this->user_ID;
        }

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (property_exists($data, '_auth') && !empty($data->_auth) && $data->id == $this->user_ID) {
            $fields[] = 'ua.username _auth_username';
            $q .= " INNER JOIN users_auth ua ON ua.user_ID = u.ID";
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        $q = "SELECT " . implode(', ', $fields) . $q;

        if ($stmt = $this->db->prepare($q)) {
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return []; //pdo cannot successfuly prepare stmt
        }
    }

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM users WHERE ID = :id");

        foreach ($data->data as $user) {
            if (property_exists($user, 'ID') && is_numeric($user->ID)) {
                if ($stmt->execute([':id' => $user->ID])) {
                    $res[] = $user->ID;
                }
            }
        }

        return $res;
    }

    public function post($data)
    {
        return [];
    }

    public function put($input)
    {
        $res = [];

        foreach ($input->data as $user) {
            if (property_exists($user, 'ID') && !empty($user->ID) && $user->ID == $this->user_ID) {
                $q = "UPDATE users SET ";
                $fields = [];
                $values = [':ID' => $this->user_ID];

                foreach($user as $key => $value) {
                    if (strpos($key, '_') !== 0 && strpos($key, 'ID') !== 0 && strpos($key, 'date') === false) {
                        $fields[] = "$key = :$key";
                        $values[":$key"] = $value;
                    }
                }

                if (count($fields) > 0) {
                    $q .= implode(', ', $fields) . " WHERE ID = :ID";

                    $stmt = $this->db->prepare($q);

                    if ($stmt->execute($values)) {
                        $res[] = $user->ID;
                    }
                }
            }
        }

        return $res;
    }

}