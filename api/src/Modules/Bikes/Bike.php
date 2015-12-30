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
        // TODO: Implement get() method.
    }

    public function del($data)
    {
        // TODO: Implement del() method.
    }

    public function post($data)
    {
        return [$this->userID];
    }

    public function put($data)
    {
        // TODO: Implement put() method.
    }
}