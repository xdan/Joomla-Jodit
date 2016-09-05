<?php

/**
 * @copyright	Copyright (c) 2016 editors. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access defined( '_JEXEC' ) or die( 'Restricted access' ); 

if (!class_exists('JoditFileBrowser')) {
    die('Access denied!');
}

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', realpath(realpath(__DIR__).'/../../../')); // replace to valid path

$saveconfig = array_merge(array(), $config); //save $config because in framework.php it will overwride

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php';

$config = $saveconfig;

$app = JFactory::getApplication(isset($_REQUEST['isadmin']) && (int)$_REQUEST['isadmin'] ? 'administrator' : 'site');
$user = JFactory::getUser();
$plugin = JPluginHelper::getPlugin('editors', 'jodit');
$params = new JRegistry($plugin->params);

$config['root'] = JPATH_BASE.'/images/';
$config['baseurl'] = '/images/';
$config['extensions'] = array('jpg', 'png', 'gif', 'jpeg');
$config['debug'] = (boolean)$params->get('debug', $config['debug']);
$config['createThumb'] = (boolean)$params->get('create_thumb', $config['createThumb']);
$config['thumbFolderName'] = $params->get('thumb_folder_name', $config['thumbFolderName']);

function JoditCheckPermissions() {
    $user = JFactory::getUser();

    $access = $user->id && $user->authorise('core.edit', 'com_content');

    if (!$access) {
        trigger_error('You are not authorized!', E_USER_WARNING);
    }
}