<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 26.11.15
 * Time: 20:10
 */

namespace Modules\Basic;


use Modules\ModuleInterface;

class BasicModule implements ModuleInterface
{
    protected $db;
    protected $userID;

    public function __construct(\PDO $db)
    {
        $this->db = $db;

        $headers = apache_request_headers();

        if (array_key_exists('Token', $headers)) {
            $this->token = $headers['Token'];
        }

        if (!empty($this->token)) {
            $q = "SELECT user_ID FROM users_auth WHERE token = :token";
            $stmt = $this->db->prepare($q);

            if ($stmt->execute(array(':token' => $this->token))) {
                $data = $stmt->fetch(\PDO::FETCH_OBJ);

                if (property_exists($data, 'user_ID')) {
                    $this->userID = $data->user_ID;
                }
            }
        }
    }

    /**
     * @param $data - assoc array of parameters to filter
     * @return array with fetched data
     */
    public function get($data)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param $data - array of ids
     * @return empty array - only status matters
     */
    public function del($data)
    {
        // TODO: Implement del() method.
    }

    /**
     * @param $input - array of assoc arrays with data to INSERT
     * @return array with new ids
     */
    public function post($input)
    {
        // TODO: Implement post() method.
    }

    /**
     * @param $data - array of assoc arrays with data to UPDATE or INSERT if no id found
     * @return array with modified/new ids
     */
    public function put($data)
    {
        // TODO: Implement put() method.
    }

    public function sanitizeInput($data) {
        if (!is_object($data)) {
            $data = new \stdClass();
        }

        return $data;
    }
}