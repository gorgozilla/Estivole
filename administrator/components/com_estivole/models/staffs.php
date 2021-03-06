<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport('joomla.application.component.modellist');
 
class EstivoleModelStaffs extends JModelList
{
	//Add this handy array with database fields to search in
	protected $searchInFields = array('u.name', 'u.email');
	
	function __construct()
	{   
		$config['filter_fields'] = array(
			'u.name',
			'u.email',
		);
		$config['filter_fields']=array_merge($this->searchInFields,array('b.member'));
		parent::__construct($config);  
		
		
		$app = JFactory::getApplication();
		$this->_member_id = $app->input->get('member_id', null);
	}
  
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		//Omit double (white-)spaces and set state
		$this->setState('filter.search', preg_replace('/\s+/',' ', $search));
		
		//Filter (dropdown) service
		$services= $app->getUserStateFromRequest($this->context.'.filter.services_members', 'filter_services_members', '', 'string');
		$this->setState('filter.services_members', $services);
		
		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'string');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		parent::populateState('lastname', 'ASC');
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
		$query->from('#__estivole_members as b');
		$query->from('#__users as u');
		$query->from('#__user_profiles as p');

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
		$query->where('b.member_type_id=2');
		$query->where('b.user_id=p.user_id');
		$query->where('b.user_id=u.id');
		
		if(is_numeric($this->_member_id)) 
		{
			$query->where('b.member_id = ' . (int) $this->_member_id);
		}
		
		// Filter search // Extra: Search more than one fields and for multiple words
		$regex = str_replace(' ', '|', $this->getState('filter.search'));
		if (!empty($regex)) {
			$regex=' REGEXP '.$db->quote($regex);
			$query->where('('.implode($regex.' OR ',$this->searchInFields).$regex.')');
		}
		
		$service= $this->getState('filter.services_members');
		if (!empty($service)){
			$query->from('#__estivole_members_daytimes as md');
			$query->where('b.member_id=md.member_id');
			$query->where("md.service_id = '".(int) $service."'");
		}
		
		$query->group('b.user_id');
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
		$list = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		return $list;
	}
	
	/**
	* Build query and where for protected _getList function and return a list
	*
	* @return array An array of results.
	*/
	public function getTotalItemsForExport()
	{
		$query = $this->_buildQuery();    
		$query = $this->_buildWhere($query);
		$query->from('#__estivole_members_daytimes as md');
		$query->where('b.member_id=md.member_id');
		$list = $this->_getList($query);
		return $list;
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
	
	/**
	* Build query and where for protected _getList function and return a list
	*
	* @return array An array of results.
	*/
	public function getTotalItems($sex = null)
	{
		$query = $this->_buildQuery();  
		$query = $this->_buildWhere($query);
		if($sex != null){
			$query->where('b.user_id IN (SELECT b.user_id FROM pt5z3_estivole_members as b,pt5z3_users as u,pt5z3_user_profiles as p WHERE b.user_id=p.user_id AND b.user_id=u.id AND (p.profile_value=\'"'.$sex.'"\' AND p.profile_key=\'profilestivole.sex\') group by b.user_id)');
		}
		$list = $this->_getList($query);
		return $list;
	}

	/**
	* Delete a member
	* @param int      ID of the member to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app  = JFactory::getApplication();
		$id   = $id ? $id : $app->input->get('member_id');

		$member = JTable::getInstance('Member','Table');
		$member->load($id);

		$member->published = 0;

		if($member->store()) 
		{
			return true;
		} else {
			return false;
		}
	}
}