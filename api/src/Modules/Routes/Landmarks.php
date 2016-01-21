<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 10.01.16
 * Time: 08:16
 */

namespace Modules\Routes;


use Modules\Basic\BasicModule;

class Landmarks extends BasicModule
{
    public function post($input) {
        $res = [];

        $q = "INSERT INTO `routes_landmarks` (`route_ID`, `value`, `landmark_order`) VALUES (:route, :val, :land_order)";

        $stmt = $this->db->prepare($q);

        foreach ($input->data as $landmark) {
            if ($stmt->execute([
                ':route' => $landmark->route_ID,
                ':val' => $landmark->value,
                ':land_order' => $landmark->landmark_order
            ])) {
                $res[] = $this->db->lastInsertId();
            }
        }

        return $res;
    }

    public function get($data)
    {
        $res = [];

        $q = "SELECT ID, value, landmark_order FROM routes_landmarks";
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

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        $q .= " ORDER BY landmark_order ASC";

        $stmt = $this->db->prepare($q);

        if ($stmt->execute($params)) {
            if ($landmarks = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                $res = $landmarks;
            }
        }

        return $res;
    }

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM routes_landmarks WHERE ID = :id");

        foreach ($data->data as $landmark) {
            if (property_exists($landmark, 'ID') && is_numeric($landmark->ID)) {
                if ($stmt->execute([':id' => $landmark->ID])) {
                    $res[] = $landmark->ID;
                }
            }
        }

        return $res;
    }


}
