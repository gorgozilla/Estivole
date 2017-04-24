<?php // no direct access

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/daytime.php';

class EstivoleModelCalendar extends JModelAdmin
{
	/**
	* Protected fields
	**/
	var $_calendar_id     = null;

	function __construct()
	{
		$app = JFactory::getApplication();
		$this->_calendar_id = $app->input->get('calendar_id', null);
		parent::__construct();       
	}
  
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form
        $form = $this->loadForm('com_estivole.calendar', 'calendar', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        } else {
            return $form;
        }
    }
	
    public function loadFormData()
    {
        // Load form data
        $data = $this->getItem();
        return $data;
    }
 
	/**
	* Builds the query to be used by the member model
	* @return   object  Query object
	*
	*
	*/
	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_calendars as b');

		return $query;
	}

	/**
	* Builds the filter for the query
	* @param    object  Query object
	* @return   object  Query object
	*
	*/
	protected function _buildWhere(&$query)
	{
		if(is_numeric($this->_calendar_id)) 
		{
		  $query->where('b.calendar_id = ' . (int) $this->_calendar_id);
		}
		return $query;
	}
  
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		return $item;
	}
  
	/**
	* Gets an array of objects from the results of database query.
	*
	* @param   string   $query       The query.
	* @param   integer  $limitstart  Offset.
	* @param   integer  $limit       The number of records.
	*
	* @return  array  An array of results.
	*
	* @since   11.1
	*/
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$db = JFactory::getDBO();
		$db->setQuery($query, $limitstart, $limit);
		$result = $db->loadObjectList();
		return $result;
	}

	/**
	* Copy a calendar
	* @param int      ID of the calendar to copy
	* @return boolean True if successfully copied
	*/
	public function copyCalendar($id = null, $formData)
	{
		$calToCopy = $this->getItem($id);
		
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$db->setQuery($query);
		// Insert columns.
		$calendarColumns = array('name', 'description', 'year', 'published', 'created', 'modified');
		// Insert values.
		$valuesCalendar = array('"'.$formData['name'].'"', '"'.$formData['description'].'"', $formData['year'], 0, '"'.date('Y-m-d H:i:s').'"', '"'.date('Y-m-d H:i:s').'"');
		 
		// Prepare the insert query.
		$query
			->insert($db->quoteName('#__estivole_calendars'))
			->columns($db->quoteName($calendarColumns))
			->values(implode(',', $valuesCalendar));
			
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		$last_calendar_id = $db->insertid();
		
		$modelDaytime = new EstivoleModelDaytime();
		if($formData['withDaytimes']){
			$daytimes = $modelDaytime->listItemsCopyCalWithDaytimes($formData['calendar_id']);
		}else{
			$daytimes = $modelDaytime->listItems($formData['calendar_id']);
		}
		
		$daytimeColumns = array('calendar_id', 'service_id', 'daytime_day', 'daytime_hour_start', 'daytime_hour_end', 'quota', 'description', 'created', 'modified', 'published');
		foreach($daytimes as $daytime){
			$newDaytime = $formData['year'].'-'.substr($daytime->daytime_day, 5,2).'-'.substr($daytime->daytime_day, 8);
			$query = $db->getQuery(true);
			$valuesDaytime = array($last_calendar_id, $daytime->service_id, '"'.$newDaytime.'"', '"'.$daytime->daytime_hour_start.'"', '"'.$daytime->daytime_hour_end.'"', $daytime->quota, '"'.$daytime->description.'"', '"'.date('Y-m-d H:i:s').'"', '"'.date('Y-m-d H:i:s').'"', 1);
			$query
				->insert($db->quoteName('#__estivole_daytimes'))
				->columns($db->quoteName($daytimeColumns))
				->values(implode(',', $valuesDaytime));
			$db->setQuery($query);
			$db->execute();
		}
		
		return true;
	}
	
	/**
	* Delete a calendar with dates and daytimes
	* @param int      ID of the calendar to delete
	* @return boolean True if successfully deleted
	*/
	public function deleteCalendar($calendar_id = null)
	{
		$app  = JFactory::getApplication();
		$id   = $id ? $id : $calendar_id;
		$calendar = JTable::getInstance('Calendar','Table');
		$calendar->load($id);
		
		$modelDaytime = new EstivoleModelDaytime();
		$memberDaytimes = $modelDaytime->listItems($id);
		
		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('Daytime','Table');
			$daytime->load($memberDaytime->daytime_id);
			if (!$daytime->delete()) 
			{
				return false;
			}
		}

		if ($calendar->delete()) 
		{
			return true;
		}  
		return false;
	}
}