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

        if (!empty($this->userID)) {
            $stmt = $this->db->prepare("SELECT ID, name, description FROM bikes WHERE user_ID = :user");

            if ($stmt->execute(array(':user' => $this->userID))) {
                $res[] = $stmt->fetch(\PDO::FETCH_OBJ);
            }
        }

        return $res;
    }

    public function del($data)
    {
        // TODO: Implement del() method.
    }

    public function post($input)
    {
        $res = [];

        if (!empty($this->userID)) {
            $q = "INSERT INTO bikes (name, description, user_ID, date_create, date_update) VALUES (:name, :desc, :user, NOW(), NOW())";

            $stmt = $this->db->prepare($q);

            foreach ($input->data as $bike) {
                if ($stmt->execute(array(
                    ':name' => $bike->name,
                    ':desc' => $bike->description,
                    ':user' => $this->userID
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