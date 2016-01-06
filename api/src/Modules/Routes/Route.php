<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 06.01.16
 * Time: 16:51
 */

namespace Modules\Routes;


use Modules\Basic\BasicModule;
use Modules\Users\Routes;

class Route extends BasicModule
{
    public function get($data) {
        $res = [];

        $q = "SELECT ID, name, from_dst, to_dst FROM routes";
        $wheres = [];
        $params = [];

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        $stmt = $this->db->prepare($q);

        if ($stmt->execute($params)) {
            if ($routes = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                $res = $routes;
            }
        }

        return $res;
    }

    public function post($input) {
        $res = [];

        $q = "INSERT INTO routes (name, from_dst, to_dst, date_create, date_update) VALUES (:name, :from, :to, NOW(), NOW())";

        $usersRoutes = new Routes($this->db);
        $stmt = $this->db->prepare($q);

        var_dump($input);
        var_dump($input->data);

        foreach ($input->data as $route) {
            if ($stmt->execute(array(
                ':name' => $route->name,
                ':from' => $route->from,
                ':to' => $this->to
            ))) {
                $route->ID = $this->db->lastInsertId();

                if (!empty($this->userID)) {
                    $usersRoutes->post((object) ['data' => [$route]]);
                }

                $res[] = $route->ID;
            }
        }

        return $res;
    }
}