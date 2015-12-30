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
        parent::get($data); // TODO: Change the autogenerated stub
    }

    public function post($input)
    {
        $res = [];

        $this->db->beginTransaction();

        try {
            if (!empty($this->userID)) {
                $q = "INSERT INTO parts (name, description) VALUES (:name, :desc)";

                $stmt = $this->db->prepare($q);

                foreach ($input->data as $part) {
                    if (!empty($part->_bike_ID) && $stmt->execute([
                        ':name' => $part->name,
                        ':desc' => $part->description
                    ])) {
                        $id = $this->db->lastInsertId();
                        $res[] = $id;

                        $bikesParts = new BikeParts();
                        $bikesParts->post([['bike_ID' => $part->_bike_ID, 'part_ID' => $id]]);
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