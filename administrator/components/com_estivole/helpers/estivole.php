<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_estivole
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Estivole component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_estivole
 * @since       1.6
 */
class EstivoleHelpersEstivole extends JHelperContent
{
	public static $extension = 'com_estivole';

	/**
	 * @return  JObject
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_estivole';
		$level = 'component';

		$actions = JAccess::getActions('com_estivole', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}
	
	public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            'Bénévoles',
            'index.php?option=com_estivole&view=members',
            $vName == 'members'
        );
        JHtmlSidebar::addEntry(
            'Staffs',
            'index.php?option=com_estivole&view=staffs',
            $vName == 'staffs'
        );
		JHtmlSidebar::addEntry(
            'Secteurs',
            'index.php?option=com_estivole&view=services',
            $vName == 'services'
        );
		JHtmlSidebar::addEntry(
            'Calendriers',
            'index.php?option=com_estivole&view=calendar&layout=edit&calendar_id=5',
            $vName == 'calendars'
        );
		JHtmlSidebar::addEntry(
            'Inscriptions',
            'index.php?option=com_estivole&view=daytimes',
            $vName == 'daytimes'
        );
    }
}