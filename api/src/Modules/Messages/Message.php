<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 19.01.16
 * Time: 17:48
 */

namespace Modules\Messages;


use Modules\Basic\BasicModule;

class Message extends BasicModule
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    public function post($input)
    {
        $res = [];

        if (!empty($this->user_ID)) {
            $q = "INSERT INTO messages (from_user, to_user, value, date_create) VALUES (:from, :to, :value, NOW())";

            $stmt = $this->db->prepare($q);

            foreach ($input->data as $message) {
                if ($stmt->execute(array(
                    ':from' => $this->user_ID,
                    ':to' => $message->to_user,
                    ':value' => $message->value
                ))) {
                    $res[] = $this->db->lastInsertId();
                }
            }
        }

        return $res;
    }

    public function get($data)
    {
        $res = [];

        $q = "SELECT m.ID, m.from_user, uf.first_name from_first, uf.last_name from_last, m.to_user, ut.first_name to_first, ut.last_name to_last, m.value, m.date_create, m.date_read FROM messages m INNER JOIN users uf ON uf.ID = m.from_user INNER JOIN users ut ON ut.ID = m.to_user";
        $wheres = ['(m.from_user = :user OR m.to_user = :user)'];
        $params = [':user' => $this->user_ID];

        if (property_exists($data, 'id') && !empty($data->id)) {
            $wheres[] = 'm.ID = :id';
            $params[':id'] = $data->id;
        }

        if (property_exists($data, '_receiver') && !empty($data->_receiver)) {
            $wheres[] = '(m.from_user = :receiver OR m.to_user = :receiver)';
            $params[':receiver'] = $data->_receiver;
        }

        if (property_exists($data, '_group') && !empty($data->_group)) {
            $q .= ' INNER JOIN (SELECT MAX(ID) ID FROM messages GROUP BY from_user, to_user) AS msg ON m.ID = msg.ID ';
        }

        if (count($wheres) > 0) {
            $q .= " WHERE " . implode(' AND ', $wheres);
        }

        if (property_exists($data, '_order')) {
            $q .= ' ORDER BY m.ID ';

            if ($data->_order === true) {
                $q .= 'ASC';
            } else {
                $q .= 'DESC';
            }
        }

        if (property_exists($data, '_limit') && !empty($data->_limit) && is_numeric($data->_limit)) {
            $q .= ' LIMIT 0, ' . $data->_limit;
        }

        if (!empty($this->user_ID)) {
            $stmt = $this->db->prepare($q);

            if ($stmt->execute($params)) {
                if ($data = $stmt->fetchAll(\PDO::FETCH_OBJ)) {
                    foreach ($data as $message) {
                        if ($message->to_user == $this->user_ID) {
                            $message->_receiver = $message->from_user;
                        } else {
                            $message->_receiver = $message->to_user;
                        }

                        $res[] = $message;
                    }
                }
            }
        }

        return $res;
    }
}