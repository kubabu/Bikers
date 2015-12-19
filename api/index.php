<?php

define('DEBUG', true);
define('API_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require API_ROOT . 'src/' . $fileName;
}

function fail($data) {
    if (!DEBUG) {
        $data = 'Runtime error';
    }

    http_response_code(520);

    echo json_encode(array('status' => false, 'data' => $data));
}

function success($data) {
    if (empty($data)) {
        $data = []; //always respond an array if success
    }

    echo json_encode(array('status' => true, 'data' => $data));
}

spl_autoload_register('autoload'); //register psr-0 class autoloader

preg_match("/\\/api\\/v([0-9]+)\\/(\\w+)\\/(\\w+)?\\/?/", $_SERVER['REQUEST_URI'], $matches); // regex: \/api\/v([0-9]+)\/(\w+)\/(\w+)?\/?

/**
* matches - array
*	0 - whole uri
* 	1 - api version
*	2 - module
*	3 - submodule
*
*/

$matches = array_map('ucfirst', $matches); //First letter in each match capital

$input = json_decode(file_get_contents("php://input")); //get JSON input

if (!empty($matches[2])) {
    $className = '\Modules\\' . $matches[2] . '\\';

    if (!empty($matches[3])) {
        $className .=  $matches[3];
    } else {
        $className .= substr($matches[2], 0, strlen($matches[2]) - 1);
    }

    $method = strtolower($_SERVER['REQUEST_METHOD']);

    if ($method == 'options') {
        exit;
    } elseif ($method == 'delete') {
        $method = 'del';
    }

    $db = null;

    try {
        $db = new PDO('mysql:host=localhost;dbname=bikers', 'bikers', '7fm8QyCFxdcjxg4LqMGs');
    } catch (Exception $e) {
        fail("Error: " . $e->getMessage());
        exit;
    }

    try {
        $module = new $className($db);

        if (method_exists($module, $method)) {
            success(call_user_func(array($module, $method), $input));
        } else {
            fail("Error: Method not implemented");
        }
    } catch (Exception $e) {
        fail("Error:" . $e->getMessage());
    }
} else {
    fail("Error: Invalid api path");
}


?>
