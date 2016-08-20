<?php
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', realpath(realpath(__DIR__).'/../../../')); // replace to valid path

$saveconfig = array_merge(array(), $config); //save $config because in framework.php it will overwride

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php';

$config = $saveconfig;

$plugin = JPluginHelper::getPlugin('editors', 'jodit');
$params = new JRegistry($plugin->params);

$config['root'] = JPATH_BASE.'/images/';
$config['baseurl'] = '/images/';
$config['extensions'] = array('jpg', 'png', 'gif', 'jpeg');
$config['debug'] = $params->get('debug', $config['debug']);
$config['createThumb'] = $params->get('create_thumb', $config['createThumb']);
$config['thumbFolderName'] = $params->get('thumb_folder_name', $config['thumbFolderName']);

function JoditCheckPermissions() {
    $app = JFactory::getApplication(isset($_REQUEST['isadmin']) && (int)$_REQUEST['isadmin'] ? 'administrator' : 'site');
    $user = JFactory::getUser();
    
    $access = $user->id && $user->authorise('core.edit', 'com_content');

    if (!$access) {
        trigger_error('You are not authorized!', E_USER_WARNING);
    }
}