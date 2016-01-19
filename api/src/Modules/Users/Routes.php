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
use Modules\Routes\Landmarks;
use Modules\Routes\Comments;
use Modules\Routes\Route;

class Routes extends BasicModule
{
    public function post($input) {
        $res = [];

        $q = "INSERT INTO users_routes (user_ID, route_ID, bike_ID, date_of_ride, duration_of_ride, date_create)
        VALUES (:user, :route, :bike, :ride_date, :ride_duration, NOW())";
        $routes = new Route($this->db);

        if (!empty($this->user_ID)) {
            $stmt = $this->db->prepare($q);

            foreach ($input->data as $route) {
                if (!empty($route->ID) && !empty($route->bike_ID)) {
                    if ($stmt->execute([
                        ':user' => $this->user_ID,
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

        $bikesBike = new Bike($this->db);
        $routesLandmarks = new Landmarks($this->db);
        $routesComments = new Comments($this->db);

        if (!empty($this->user_ID)) {
            $q = "SELECT r.ID, r.name, r.from_dst, r.to_dst, ur.date_of_ride, ur.duration_of_ride, ur.bike_ID FROM routes r INNER JOIN users_routes ur ON r.ID = ur.route_ID ";
            $wheres = ['user_ID = :user_id'];
            $params = [':user_id' => $this->user_ID];

            if (property_exists($data, 'id') && !empty($data->id)) {
                $wheres[] = 'ur.route_ID = :id';
                $params[':id'] = $data->id;
            }

            if (property_exists($data, 'bike_ID') && !empty($data->bike_ID)) {
                $wheres[] = 'ur.bike_ID = :bike_id';
                $params[':bike_id'] = $data->bike_ID;
            }

            if (!property_exists($data, '_comments_users') || empty($data->_comments_users)) {
                $data->_comments_users = false;
            }

            if (count($wheres) > 0) {
                $q .= " WHERE " . implode(' AND ', $wheres);
            }

            $stmt = $this->db->prepare($q);

            if ($stmt->execute($params)) {
                if ($routes = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                    foreach ($routes as $route) {
                        $bikes = $bikesBike->get((object) ['id' => $route->bike_ID]);
                        if (count($bikes) > 0) {
                            $route->bike = $bikes[0];
                        }
                        unset($data->bike_ID);

                        if (property_exists($data, '_landmarks') && $data->_landmarks) {
                            $route->landmarks = $routesLandmarks->get((object) ['route_ID' => $route->ID]);
                        }

                        if (property_exists($data, '_comments') && $data->_comments) {
                            $route->comments = $routesComments->get((object) ['route_ID' => $route->ID, '_users'=> $data->_comments_users]);
                        }

                        $res[] = $route;
                    }
                }
            }
        }

        return $res;
    }
}
