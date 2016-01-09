<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 30.12.15
 * Time: 22:04
 */

namespace Modules\Parts;


use Modules\Basic\BasicModule;
use Modules\Bikes\Parts as BikeParts;

class Part extends BasicModule
{
    public function get($data)
    {
        $res = [];

        $q = "SELECT ID, name, description FROM parts";
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
            if ($data = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                $res = $data;
            }
        }

        return $res;
    }

    public function post($input)
    {
        $res = [];

        $this->db->beginTransaction();
        $bikesParts = new BikeParts($this->db);

        try {
            if (!empty($this->user_ID)) {
                $q = "INSERT INTO parts (name, description) VALUES (:name, :desc)";

                $stmt = $this->db->prepare($q);

                foreach ($input->data as $part) {
                    if (!empty($part->_bike_ID) && $stmt->execute([
                        ':name' => $part->name,
                        ':desc' => $part->description
                    ])) {
                        $id = $this->db->lastInsertId();
                        $res[] = $id;

                        $bikesParts->post((object) ['data' => [(object) ['bike_ID' => $part->_bike_ID, 'part_ID' => $id]]]);
                    }
                }
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        return $res;
    }
}