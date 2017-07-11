<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EstivoleModelDaytimes extends JModelList
{
	protected $searchInFields = array('u.name','u.email','s.service_name', 'd.daytime_day', 'd.daytime_hour_start');
	
	function __construct()
	{
		$app = JFactory::getApplication();
		$this->member_daytime_id = $app->input->get('member_daytime_id', null);
		$this->_member_id = $app->input->get('member_id', null);
		$this->_calendar_id = $app->input->get('calendar_id', null);
	
		$config['filter_fields'] = array(
			'u.name',
			'u.email',
			's.service_name',
			'd.daytime_day',
			'md.status_id'
		);
		$config['filter_fields']=array_merge($this->searchInFields,array('s.service'));
		parent::__construct($config);     
	}
  
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		
		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		//Omit double (white-)spaces and set state
		$this->setState('filter.search', preg_replace('/\s+/',' ', $search));
		
		//Filter (dropdown) calendar id
		$calendarId= $app->getUserStateFromRequest($this->context.'.filter.calendar_id', 'filter_calendar_id', '', 'int');
		$this->setState('filter.calendar_id', $calendarId);
		
		//Filter (dropdown) service
		$services= $app->getUserStateFromRequest($this->context.'.filter.services', 'filter_services', '', 'string');
		$this->setState('filter.services', $services);
		
		//Filter (dropdown) date
		$dates= $app->getUserStateFromRequest($this->context.'.filter.dates', 'filter_dates', '', 'string');
		$this->setState('filter.dates', $dates);
		
		parent::populateState('lastname', 'ASC');
	}
	
	function getData() 
	{
		// if data hasn't already been obtained, load it
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$query = $this->_buildWhere($query);
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));	
		}
		return $this->_data;
	}

  function getTotal()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_total)) {
 	    $query = $this->_buildQuery();
		$query = $this->_buildWhere($query);
 	    $this->_total = $this->_getListCount($query);	
 	}
 	return $this->_total;
  }

  function getPagination()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_pagination)) {
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
 	}
 	return $this->_pagination;
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
		$query->from('#__estivole_members as m, #__estivole_services as s, #__estivole_calendars as c, #__estivole_daytimes as d, #__estivole_members_daytimes as md, #__users as u');
		$query->where('md.member_id = m.member_id');
		$query->where('m.user_id = u.id');
		$query->where('md.service_id = s.service_id');
		$query->where('md.daytime_id=d.daytime_id');
		$query->where('d.calendar_id=c.calendar_id');
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
		$db = JFactory::getDBO();
		
		if(is_numeric($this->_member_id)) 
		{
			$query->where('md.member_id = ' . $this->_member_id);
		}
		
		$calendarId= $db->escape($this->getState('filter.calendar_id'));
		if (!empty($calendarId)) {
			$query->where('(c.calendar_id='.$calendarId.')');
		}else{
			$query_cal = $db->getQuery(TRUE);
			$query_cal->select('*');
			$query_cal->from('#__estivole_calendars as c');
			$query_cal->order('c.calendar_id DESC');
			$db->setQuery($query_cal);
			$cal_id = $db->loadObjectList();
			$cal_id = $cal_id[0]->calendar_id;
			$query->where('(c.calendar_id='.$cal_id.')');
		}
		
		$service= $db->escape($this->getState('filter.services'));
		if (!empty($service)) {
			$query->where('s.service_id='.$service);
		}
		
		$date= $db->escape($this->getState('filter.dates'));
		if (!empty($date)) {
			$query->where('d.daytime_day=\''.$date.'\'');
		}
		
		// Filter search // Extra: Search more than one fields and for multiple words
		$regex = str_replace(' ', '|', $this->getState('filter.search'));
		if (!empty($regex)) {
			$regex=' REGEXP '.$db->quote($regex);
			$query->where('('.implode($regex.' OR ',$this->searchInFields).$regex.')');
		}

		return $query;
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
		$limit = $this->getState('limit');
		$limitstart = $this->getState('limitstart');
		$db = JFactory::getDBO();
		$query->order($db->escape($this->getState('list.ordering', 'u.name')).' '.$db->escape($this->getState('list.direction', 'ASC')));
		$db->setQuery($query, $limitstart, $limit);
		$result = $db->loadObjectList();
		return $result;
	}
	
	/**
	* Build query and where for protected _getList function and return a list
	*
	* @return array An array of results.
	*/
	public function listItems()
	{
		$query = $this->_buildQuery();    
		$query = $this->_buildWhere($query);
		$list = $this->_getList($query, $this->limitstart, $this->limit);
		return $list;
	}
	
	/**
	* Build query and where for protected _getList function and return a list
	*
	* @return array An array of results.
	*/
	public function listItemsByDate()
	{
		$db = JFactory::getDBO();
		$query = $this->_buildQuery();    
		$query = $this->_buildWhere($query);
		$query->order('d.daytime_day');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getItem()
	{
		$db = JFactory::getDBO();
		$query = $this->_buildQuery();
		$this->_buildWhere($query);
		$db->setQuery($query);
		$item = $db->loadObject();
		return $item;
	}
	
	public function getDaytimesByDaytime($daytime){
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_daytimes as d');
		$query->where('d.daytime_day=\''.$daytime.'\'');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	public function getCampersByDaytime($daytime){
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_daytimes as d, #__estivole_members_daytimes as md, #__estivole_members as m, #__users as u, #__user_profiles as up');
		$query->where('md.member_id=m.member_id');
		$query->where('md.daytime_id=d.daytime_id');
		$query->where('m.user_id=u.id');
		$query->where('up.user_id=u.id');
		$query->where('(up.profile_value!=\'null\' AND up.profile_key=\'profilestivole.campingPlace\')');
		$query->where('d.daytime_day=\''.$daytime.'\'');
		$query->group('m.member_id');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}