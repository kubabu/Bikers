<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 25.11.15
 * Time: 19:00
 */

namespace Modules\Bikes;


use Modules\Basic\BasicModule;

class Bike extends BasicModule
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    public function get($data)
    {
        $res = [];

        $q = "SELECT ID, name, description FROM bikes";
        $wheres = ['user_ID = :user'];
        $params = [':user' => $this->user_ID];
        $bikeParts = new Parts($this->db);

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'ID = :id';
            $params[':id'] = $data->id;
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        if (!empty($this->user_ID)) {
            $stmt = $this->db->prepare($q);

            if ($stmt->execute($params)) {
                if ($data = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                    foreach ($data as $bike) {
                        if (!empty($parts = $bikeParts->get((object) ['bike_ID' => $bike->ID]))) {
                            $bike->parts = $parts;
                        }

                        $res[] = $bike;
                    }
                }
            }
        }

        return $res;
    }

    public function del($data)
    {
        $res = [];
        $stmt = $this->db->prepare("DELETE FROM bikes WHERE ID = :id");

        foreach ($data->data as $bike) {
            if (property_exists($bike, 'ID') && is_numeric($bike->ID)) {
                if ($stmt->execute([':id' => $bike->ID])) {
                    $res[] = $bike->ID;
                }
            }
        }

        return $res;
    }

    public function post($input)
    {
        $res = [];

        if (!empty($this->user_ID)) {
            $q = "INSERT INTO bikes (name, description, user_ID, date_create, date_update) VALUES (:name, :desc, :user, NOW(), NOW())";

            $stmt = $this->db->prepare($q);

            foreach ($input->data as $bike) {
                if ($stmt->execute(array(
                    ':name' => $bike->name,
                    ':desc' => $bike->description,
                    ':user' => $this->user_ID
                ))) {
                    $res[] = $this->db->lastInsertId();
                }
            }
        }

        return $res;
    }

    public function put($data)
    {
        // TODO: Implement put() method.
    }
}