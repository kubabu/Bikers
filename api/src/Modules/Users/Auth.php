<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 05.12.15
 * Time: 14:28
 */

namespace Modules\Users;

use Modules\Basic\BasicModule;

class Auth extends BasicModule
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    /**
     * Checks if user is logged in
     * @param $data
     * @return array
     */
    public function get($data)
    {
        $data = $this->sanitizeInput($data);

        $headers = apache_request_headers();

        if (array_key_exists('Token', $headers) && !empty($data->username)) {
            $q = "SELECT token FROM users_auth WHERE IP = :ip AND token = :token AND username = :username";

            if ($stmt = $this->db->prepare($q)) {
                $stmt->execute([
                    ':ip' => ip2long($this->getRequestIp()),
                    ':token' => $headers['Token'],
                    ':username' => $data->username
                ]);

                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (count($data) == 1) { //there is user with specific login, token and ip address, so this user is logged in
                    return [true];
                }
            } else {
                return [false]; //pdo cannot successfuly prepare stmt
            }
        } else {
            return [false]; //there is no user with this login, token and ip addres that is logged in
        }
    }

    /**
     * Binds new token for user if login is present in database otherwise register new user and return him token
     * @param $data
     * @return array
     */
    public function post($data)
    {
        $q = "UPDATE users_auth SET IP = :ip, token = :token WHERE username = :username AND password = :password";

        $token = md5($data->password . $data->username . mt_rand());

        if ($stmt = $this->db->prepare($q)) {
            $input = [
                ':ip' => ip2long($this->getRequestIp()),
                ':token' => $token,
                ':username' => $data->username,
                ':password' => md5($data->password)
            ];

            $stmt->execute($input);

            if ($stmt->rowCount() > 0) {
                return [$token];
            } else if (!empty($data->username) && !empty($data->password) && !empty($data->register)) {
                $q = "SELECT user_ID FROM users_auth WHERE username = :username";
                $stmt = $this->db->prepare($q);
                $stmt->execute([':username' => $data->username]);

                if (!count($stmt->fetchAll())) {
                    $q = "INSERT INTO users (first_name, last_name, date_create, date_update) VALUES ('', '', NOW(), NOW())";
                    $stmt = $this->db->prepare($q);

                    if ($stmt->execute()) {
                        $input[':user_id'] = $this->db->lastInsertId();

                        $q = "INSERT INTO users_auth (user_ID, IP, token, username, password) VALUES (:user_id, :ip, :token, :username, :password)";
                        $stmt = $this->db->prepare($q);

                        if ($stmt->execute($input)) {
                            return [$token];
                        }
                    }
                }
            }
        }

        return [false];
    }

    public function del($data)
    {
        return [false];
    }

    public function put($input)
    {
        $res = [];

        if (!empty($this->user_ID)) {
            foreach ($input->data as $user) {
                if (property_exists($user, 'ID') && $user->ID == $this->user_ID) {
                    $values = [':user_ID' => $this->user_ID];
                    $fields = [];

                    foreach($user as $field => $value) {
                        if ($this->updateableField($field) && !in_array($field, ['token', 'IP', 'password'])) {
                            $fields[] = "$field = :$field";
                            $values[":$field"] = $value;
                        } elseif (strcmp($field, 'password') === 0) {
                            $fields[] = "password = :password";
                            $values[":password"] = md5($value);
                        }
                    }

                    if (count($fields) > 0) {
                        $q = "UPDATE users_auth SET " . implode(', ', $fields) . " WHERE ID = :ID AND user_ID = :user_ID";

                        $stmt = $this->db->prepare($q);

                        if ($stmt->execute($values)) {
                            $res[] = $user->ID;
                        }
                    }
                }
            }
        }

        return $res;
    }

    private function getRequestIp()
    {
        return '127.0.0.1';
//        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//            return $_SERVER['HTTP_CLIENT_IP'];
//        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            return $_SERVER['HTTP_X_FORWARDED_FOR'];
//        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
//            return $_SERVER['REMOTE_ADDR'];
//        } else {
//            return '127.0.0.1';
//        }
    }
}