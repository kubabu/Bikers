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

    public function __construct(\PDO $db)
    {
        $this->db = $db;
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
     * @param $data - array of assoc arrays with data to INSERT
     * @return array with new ids
     */
    public function post($data)
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
        if (!is_array($data)) {
            $data = array();
        }

        return $data;
    }
}