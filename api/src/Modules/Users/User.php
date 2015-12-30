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

        $q = "SELECT * FROM `users`";
        $params = [];
        $wheres = [];

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        if ($stmt = $this->db->prepare($q)) {
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return []; //pdo cannot successfuly prepare stmt
        }
    }

    public function del($data)
    {
        // TODO: Implement del() method.
    }

    public function post($data)
    {
        // TODO: Implement post() method.
    }

    public function put($data)
    {
        // TODO: Implement put() method.
    }

}