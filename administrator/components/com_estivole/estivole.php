<?php // No direct access

defined('_JEXEC') or die;
//load classes
JLoader::registerPrefix('Estivole', JPATH_COMPONENT);
//Load styles and javascripts
EstivoleHelpersStyle::load();

$controller	= JControllerLegacy::getInstance('Estivole');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();