<?php
/**
 * @copyright  Copyright (C) 2012 Mark Dexter & Louis Landry. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
jimport('joomla.plugin.plugin');

/**
 * This is our custom registration plugin class.  It verifies that the user
 *  checked the boxes indicating that he/she agrees to the terms of service
 *  and is old enough to use the site.
 */
class plgUserEstivole extends JPlugin
{
    /**
     * Method to handle the "onUserBeforeSave" event and determine
     * whether we are happy with the input enough that we will allow
     * the save to happen.  Specifically we are checking to make sure that
     * this is saving a new user (user registration), and that the
     * user has checked the boxes that indicate agreement to the terms of
     * service and that he/she is old enough to use the site.
     *
     * @param   array  $previousData  The currently saved data for the user.
     * @param   bool   $isNew         True if the user to be saved is new.
     * @param   array  $futureData    The new data to save for the user.
     *
     * @return  bool   True to allow the save process to continue,
     *                   false to stop it.
     *
     * @since   1.0
     */

    function onUserAfterSave($user, $isNew, $success, $msg)
    {
        if($isNew && $success){
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_estivole/tables');
			$member = JTable::getInstance('Member','Table');
			$member->user_id = $user['id'];
			$member->email = $user['email'];
			$member->member_type_id=1;
			$member->published = 1;
			$member->created = date("Y-m-d H:i:s");
			$member->modified = date("Y-m-d H:i:s");
			$member->store();
			return true;
        }else{
			return false;
		}
    }
}