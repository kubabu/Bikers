<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 30.12.15
 * Time: 22:26
 */

namespace Modules\Bikes;


use Modules\Basic\BasicModule;

class Parts extends BasicModule
{
    public function post($input) {
        $res = [];

        if (!empty($this->userID)) {
            $q = "INSERT INTO bikes_parts (bike_ID, part_ID) VALUES (:bike, :part)";

            $stmt = $this->db->prepare($q);

            foreach ($input->data as $data) {
                if ($stmt->execute(array(
                    ':bike' => $data->bike_ID,
                    ':part' => $data->part_ID
                ))) {
                    $res[] = true;
                } else {
                    $res[] = false;
                }
            }
        }

        return $res;
    }

    public function get($data) {
        $res = [];

        if (!empty($this->userID)) {
            $q = "SELECT p.* FROM parts p INNER JOIN bikes_parts bp ON p.ID = bp.part_ID AND bp.bike_id = :id";

            $stmt = $this->db->prepare($q);

            if ($stmt->execute(array(
                ':id' => $data->bike_ID
            ))) {
                $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
            }
        }

        return $res;
    }
}