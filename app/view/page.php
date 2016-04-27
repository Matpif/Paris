<?php
session_start();

// DEBUG MODE ON
error_reporting(E_ALL);
ini_set('display_errors', 'on');
// DEBUG MODE ON

function __autoload($class_name) {
    if (strpos($class_name, 'Model') !== false) {
        include_once "../model/{$class_name}.php";
    } else if (strpos($class_name, 'Collection') !== false) {
        include_once "../collection/{$class_name}.php";
    } else if (strpos($class_name, 'Controller') !== false) {
        include_once "../controller/{$class_name}.php";
    } else {
        include_once "../tools/{$class_name}.php";
    }
}

$_page = 'accueil';
if (isset($_GET['page'])) {
    $_page = $_GET['page'];
}
Access::getInstance()->controlAccess($_page);
$controllerName = ucfirst(strtolower($_page)).'Controller';
/**
 * @var $_controller Controller
 */
$_controller = new $controllerName;

if ($_controller && isset($_GET['action'])) {
    $_action = $_GET['action'].'Action';
    call_user_func(array($_controller, $_action));
}

$messageManager = new MessageManager();
$messages = $messageManager->getMessages();
?>
<html>
    <head>
        <title><?php echo $_controller->getTitle(); ?></title>
        <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php $_controller->getHeader(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <?php foreach ($messages as $message): ?>
                        <?php echo $message->getMessageHtml(); ?><br/>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php echo $_controller->getHtml(); ?>
        </div>
        <?php $_controller->getFooter(); ?>
    </body>
</html>