<?php
/**
 * Created by PhpStorm.
 * User: waldek
 * Date: 25.11.15
 * Time: 18:53
 */

namespace Modules;


interface ModuleInterface
{
    public function __construct(\PDO $db);

    /**
     * @param $data - assoc array of parameters to filter
     * @return array with fetched data
     */
    public function get($data);

    /**
     * @param $data - array of ids
     * @return empty array - only status matters
     */
    public function del($data);

    /**
     * @param $data - array of assoc arrays with data to INSERT
     * @return array with new ids
     */
    public function post($data);

    /**
     * @param $data - array of assoc arrays with data to UPDATE or INSERT if no id found
     * @return array with modified/new ids
     */
    public function put($data);
}