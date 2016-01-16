<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 06.01.16
 * Time: 16:51
 */

namespace Modules\Routes;


use Modules\Basic\BasicModule;
use Modules\Bikes\Bike;
use Modules\Users\Routes;

class Route extends BasicModule
{
    public function get($data) {
        $res = [];

        $q = "SELECT ID, name, from_dst, to_dst FROM routes";
        $wheres = [];
        $params = [];
        $routesLandmarks = new Landmarks($this->db);

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
                if (property_exists($data, '_landmarks') && $data->_landmarks) {
                    foreach ($routes as $route) {
                        $route->landmarks = $routesLandmarks->get((object)['id' => $route->ID]);
                    }
                }
            }
        }

        return $res;
    }

    public function post($input) {
        $res = [];

        $q = "INSERT INTO routes (name, from_dst, to_dst, date_create, date_update) VALUES (:name, :from, :to, NOW(), NOW())";

        $usersRoutes = new Routes($this->db);
        $routesLandmarks = new Landmarks($this->db);
        $stmt = $this->db->prepare($q);

        foreach ($input->data as $route) {
            if ($stmt->execute(array(
                ':name' => $route->name,
                ':from' => $route->from_dst,
                ':to' => $route->to_dst
            ))) {
                $route->ID = $this->db->lastInsertId();

                if (!empty($this->user_ID)) {
                    $usersRoutes->post((object) ['data' => [$route]]);
                }

                foreach ($route->landmarks as $landmark) {
                    $landmark->route_ID = $route->ID;
                    $routesLandmarks->post((object) ['data' => [$landmark]]);
                }

                $res[] = $route->ID;
            }
        }

        return $res;
    }
}