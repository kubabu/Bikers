<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 30.12.15
 * Time: 22:26
 */

namespace Modules\Bikes;


class Parts
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
                    $res[] = $this->db->lastInsertId();
                }
            }
        }

        return $res;
    }
}