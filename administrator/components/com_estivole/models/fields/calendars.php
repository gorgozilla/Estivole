<?php
 
defined('JPATH_BASE') or die;
 
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Custom Field class for the Joomla Framework.
 *
 * @package		Joomla.Administrator
 * @subpackage	        com_my
 * @since		1.6
 */
class JFormFieldCalendars extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Calendars';
 
	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();
 
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
 		// Get the options.
		$query->select('calendar_id As value, name As text');
		$query->from('#__estivole_calendars AS c');
		$query->order('c.calendar_id DESC');
		$db->setQuery($query);
		$options = $db->loadObjectList();
 
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
 
		return $options;
	}
}