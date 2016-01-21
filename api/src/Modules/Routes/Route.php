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
        $routesComments = new Comments($this->db);

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        if (property_exists($data, '_order')) {
            $q .= ' ORDER BY ID ';

            if ($data->_order === true) {
                $q .= 'ASC';
            } else {
                $q .= 'DESC';
            }
        }

        if (property_exists($data, '_limit') && !empty($data->_limit) && is_numeric($data->_limit)) {
            $q .= ' LIMIT 0, ' . $data->_limit;
        }

        $stmt = $this->db->prepare($q);

        if ($stmt->execute($params)) {
            if ($routes = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                if (property_exists($data, '_landmarks') && $data->_landmarks) {
                    foreach ($routes as $route) {
                        $route->_landmarks = $routesLandmarks->get((object)['route_ID' => $route->ID]);
                    }
                }

                if (property_exists($data, '_comments') && $data->_comments) {
                    foreach ($routes as $route) {
                        $route->_comments = $routesComments->get((object)['route_ID' => $route->ID]);
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

                foreach ($route->_landmarks as $landmark) {
                    $landmark->route_ID = $route->ID;
                    $routesLandmarks->post((object) ['data' => [$landmark]]);
                }

                $res[] = $route->ID;
            }
        }

        return $res;
    }

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM routes WHERE ID = :id");

        foreach ($data->data as $route) {
            if (property_exists($route, 'ID') && is_numeric($route->ID)) {
                if ($stmt->execute([':id' => $route->ID])) {
                    $res[] = $route->ID;
                }
            }
        }

        return $res;
    }

    public function put($input) {
        $res = [];

        $routesLandmarks = new Landmarks($this->db);

        if (!empty($this->user_ID)) {
            foreach ($input->data as $route) {
                $values = [':ID' => $route->ID];
                $fields = [];

                foreach ($route as $field => $value) {
                    if ($this->updateableField($field) && !in_array($field, ['date_of_ride', 'duration_of_ride', 'bike_ID'])) {
                        $fields[] = "$field = :$field";
                        $values[":$field"] = $value;
                    }
                }

                if (count($fields) > 0) {
                    $q = "UPDATE routes SET " . implode(', ', $fields) . " WHERE ID = :ID";

                    $stmt = $this->db->prepare($q);

                    if ($stmt->execute($values)) {
                        $res[] = $route->ID;
                    }
                }

                foreach ($route->_landmarks as $landmark) {
                    $landmark->route_ID = $route->ID;
                    $routesLandmarks->put((object)['data' => [$landmark]]);
                }
            }
        }

        return $res;
    }
}
