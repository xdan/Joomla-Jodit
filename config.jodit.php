<?php
define('_JEXEC', 1);
define('JPATH_BASE', realpath(realpath(__DIR__).'/../../../')); // replace to valid path

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

$config = array(
    'root' => JPATH_BASE.'/images/',
    'baseurl' => '/images/',
    'extensions' => array('jpg', 'png', 'gif', 'jpeg'),
    'debug' => false,
);

$app = JFactory::getApplication('site');

function JoditCheckPermissions() {
    $user = JFactory::getUser();
    if (!$user->id || !$user->authorise('core.edit', 'com_content')) {
        trigger_error('You are not authorized!', E_USER_WARNING);
    }
}