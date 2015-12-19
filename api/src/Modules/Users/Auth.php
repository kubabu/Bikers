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

        if (array_key_exists('Token', $headers) && array_key_exists('login', $data)) {
            $q = "SELECT token FROM users_auth WHERE ip_addr = :ip AND token = :token AND login = :login";

            if ($stmt = $this->db->prepare($q)) {
                $stmt->execute([
                    ':ip' => ip2long($this->getRequestIp()),
                    ':token' => $headers['Token'],
                    ':login' => $data['login']
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
        $q = "UPDATE users_auth SET ip_addr = :ip, token = :token WHERE login = :login AND password = :password";

        $token = md5($data['password'] . $data['login'] . mt_rand());

        if ($stmt = $this->db->prepare($q)) {
            $stmt->execute([
                ':ip' => ip2long($this->getRequestIp()),
                ':token' => $token,
                ':login' => $data['login'],
                ':password' => md5($data['password'])
            ]);

            if ($stmt->rowCount() > 0) {
                return [$token];
            } else if (!empty($data['login']) && !empty($data['password']) && !empty($data['register'])) {
                $q = "SELECT id FROM users_auth WHERE login = :login";
                $stmt = $this->db->prepare($q);
                $stmt->execute([':login' => $data['login']]);

                if (!count($stmt->fetch(\PDO::FETCH_ASSOC))) {
                    $q = "INSERT INTO users_auth (ip_addr, token, login, password) VALUES (:ip, :token, :login, :password)";

                    if ($stmt->execute([
                        ':ip' => ip2long($this->getRequestIp()),
                        ':token' => $token,
                        ':login' => $data['login'],
                        ':password' => md5($data['password'])
                    ])) {
                        return [true];
                    }
                }
            }
        } else {
            return [false]; //pdo cannot successfuly prepare stmt
        }
    }

    private function getRequestIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}