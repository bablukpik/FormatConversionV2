<?php
session_start();

define('DIR', dirname(__FILE__));
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'App');
define('LIB_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'Library');

set_include_path(ROOT_PATH . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . APP_PATH);

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    include $class_name . '.php';
});

// Define message
$message = array(
    'MISSING_FILE' => 'ファイルを選択してください',
    'MISSING_SERVER_FILE' => '',
    'MISSING_CLIENT_FILE' => ''
);

require_once '../Library/Presentation.php';

// Start app
$app = new \Actions\App();

// Get action
//$action = 'home';
$action = 'page';
if (isset($_GET['action']) && !empty($_GET['action'])) {
    // Check function exist:
    if (method_exists($app, $_GET['action'])) {
        $action = trim($_GET['action']);
    }
}

echo $app->{$action}();