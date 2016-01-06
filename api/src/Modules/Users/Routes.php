<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 06.01.16
 * Time: 16:58
 */

namespace Modules\Users;


use Modules\Basic\BasicModule;
use Modules\Bikes\Bike;
use Modules\Routes\Route;

class Routes extends BasicModule
{
    public function post($input) {
        $res = [];

        $q = "INSERT INTO users_routes (user_ID, route_ID, bike_ID, date_of_ride, duration_of_ride, date_create)
        VALUES (:user, :route, :bike, :ride_date, :ride_duration, NOW())";
        $routes = new Route($this->db);

        if (!empty($this->userID)) {
            $stmt = $this->db->prepare($q);

            foreach ($input->data as $route) {
                if (!empty($route->ID) && !empty($route->bike_ID)) {
                    if ($stmt->execute([
                        ':user' => $this->userID,
                        ':route' => $route->ID,
                        ':bike' => $route->bike_ID,
                        ':ride_date' => $route->date_of_ride,
                        ':ride_duration' => $route->duration_of_ride
                    ])) {
                        $res[] = $this->db->lastInsertId();
                    }
                } elseif (!empty($route->bike_ID)) {
                    $routes->post($route);
                }
            }
        }

        return $res;
    }

    public function get($data) {
        $res = [];

        $bikes = new Bike($this->db);

        if (!empty($this->userID)) {
            $q = "SELECT r.ID, r.name, r.from_dst, r.to_dst, ur.date_of_ride, ur.duration_of_ride, ur.bike_ID FROM routes r INNER JOIN users_routes ur ON r.ID = ur.route_ID ";
            $wheres = ['user_ID = :user_id'];
            $params = [':user_id' => $this->userID];

            if (property_exists($data, 'id') && !empty($data->id)) {
                $wheres[] = 'ur.route_ID = :id';
                $params[':id'] = $data->id;
            }

            if (property_exists($data, 'bike_ID') && !empty($data->bike_ID)) {
                $wheres[] = 'ur.bike_ID = :bike_id';
                $params[':bike_id'] = $data->bike_ID;
            }

            if (count($wheres) > 0) {
                $q .= " WHERE " . implode(' AND ', $wheres);
            }

            $stmt = $this->db->prepare($q);

            if ($stmt->execute($params)) {
                if ($routes = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                    foreach ($routes as $route) {
                        $data->bike = $bikes->get((object) ['id' => $route->bike_ID]);
                        unset($data->bike_ID);

                        $res[] = $data->bike;
                    }
                }
            }
        }

        return $res;
    }
}