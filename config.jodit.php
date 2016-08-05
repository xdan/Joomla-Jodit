<?php
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', realpath(realpath(__DIR__).'/../../../')); // replace to valid path

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php';

$config = array(
    'root' => JPATH_BASE.'/images/',
    'baseurl' => '/images/',
    'extensions' => array('jpg', 'png', 'gif', 'jpeg'),
    'debug' => false,
);

function JoditCheckPermissions() {
    $app = JFactory::getApplication('administrator');
    $user = JFactory::getUser();
    //$app->dispatch();
    //unset($user);
    //print_r(get_class_methods($user));
    //$access = $user->id && $user->authorise('core.edit', 'com_content');

    if (!$access) {
        $app = JFactory::getApplication('site');
        //$app->initialise();
        $user = JFactory::getUser();
        echo $user->id;
        $access = $user->id && $user->authorise('core.edit', 'com_content');
        exit;
    }

    if (!$access) {
        trigger_error('You are not authorized!', E_USER_WARNING);
    }
}