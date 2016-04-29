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

$_page = ReadIni::getInstance()->getAttribute('general', 'index_page');
if (isset($_GET['page'])) {
    $_page = $_GET['page'];
}
Access::getInstance()->controlAccess($_page);
$controllerName = $_page.'Controller';
/**
 * @var $_controller Controller
 */
$_controller = Controller::getController($controllerName);

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
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php echo $_controller->getHeader(); ?>
        <div class="container-fluid body">
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <?php foreach ($messages as $message): ?>
                        <?php echo $message->getMessageHtml(); ?><br/>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php echo $_controller->getHtml(); ?>
        </div>
        <?php echo $_controller->getFooter(); ?>
    </body>
</html>