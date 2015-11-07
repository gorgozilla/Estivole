<?php // no direct access

defined('_JEXEC') or die;
 
class EstivoleModelDaytime extends JModelItem
{

  /**
  * Protected fields
  **/
  var $_daytime_id     = null;
  
  function __construct()
  {
    $app = JFactory::getApplication();
    $this->_daytime_id = $app->input->get('daytime_id', null);
	$this->_calendar_id = $app->input->get('calendar_id', null);
	$this->_service_id = $app->input->get('service_id', null);
	$this->_daytime_day = $app->input->get('daytime', null);
    
    parent::__construct();       
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
	
	if($this->_daytime_day==''){
		$query->select('distinct daytime_day');

	}else{
		$query->select('*');
	}
	
	$query->from('#__estivole_daytimes as b');

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

    if($this->_daytime_day) 
    {
      $query->where("b.daytime_day = '".$this->_daytime_day."'");
    }
	
    if($this->_service_id) 
    {
      $query->where("b.service_id = '".(int) $this->_service_id."'");
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
  * Build query and where for protected _getList function and return a list
  *
  * @return array An array of results.
  */
  public function listItems()
  {
	  //Build and querydatabase
    $query = $this->_buildQuery();    
    $query = $this->_buildWhere($query);
	$query->order('b.daytime_day');
	//Get list of data
    $list = $this->_getList($query, $this->limitstart, $this->limit);
	
    return $list;
  }
  
  public function getMemberDaytimes($member_id, $calendar_id)
  {
    $query = $this->_buildQuery();   
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_members as m, #__estivole_services as s, #__estivole_daytimes as d, #__estivole_members_daytimes as md');
	$query->where('md.member_id = ' . $member_id);
	$query->where('d.calendar_id = ' . $calendar_id);
	$query->where('md.member_id = m.member_id');
	$query->where('md.service_id = s.service_id');
	$query->where('md.daytime_id = d.daytime_id');
	$query->order('d.daytime_day ASC');
    $db->setQuery($query, 0, 0);
    $result = $db->loadObjectList();
 
    return $result;
  }

  public function isDaytimeAvailableForMember($member_id, $daytime_id)
  {
    $query = $this->_buildquery();   
    $db = jfactory::getdbo();
    $query = $db->getquery(true);
	
	$query->select('*');
	$query->from('#__estivole_members_daytimes as md');
	$query->where('md.member_id = ' . $member_id);
	$query->where('md.daytime_id = ' . $daytime_id);
    $db->setquery($query, 0, 0);
    $result = $db->loadObject();
 
    return $result;
  }
  
  public function isDaytimeComplete($daytime_id, $filledQuota)
  {

    $query = $this->_buildquery();   
    $db = jfactory::getdbo();
    $query = $db->getquery(true);
	
	$query->select('*');
	$query->from('#__estivole_daytimes as md');
	$query->where('md.daytime_id = ' . (int) $daytime_id);

    $db->setquery($query, 0, 0);
    $result = $db->loadObject();

	if($filledQuota==$result->quota){
		return true;
	}else{

		return false;
	}
  }
  
  public function getQuotasByDaytime($daytime_id)
  {  
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_daytimes as d, #__estivole_members_daytimes as md');
	$query->where('md.daytime_id = ' . $daytime_id);
	$query->where('md.daytime_id = d.daytime_id');
    $db->setQuery($query, 0, 0);
    $result = $db->loadObjectList();
 
    return $result;
  }
  
  public function getDaytimesByService($calendar_id, $service_id)
  {  
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_daytimes as d, #__estivole_members_daytimes as md');
	$query->where('md.daytime_id = ' . $daytime_id);
	$query->where('md.daytime_id = d.daytime_id');
    $db->setQuery($query, 0, 0);
    $result = $db->loadObjectList();
 
    return $result;
  }
  
  /**
  * Delete a member
  * @param int      ID of the member to delete
  * @return boolean True if successfully deleted
  */
  public function saveTime($formData = null)
  {
	$app  = JFactory::getApplication();
	$id   = $id ? $id : $formData['daytime_id'];

	$daytime = JTable::getInstance('Daytime','Table');
	$daytime->load($id);

	$daytime->daytime_day = $formData['daytime_day'];
	$daytime->daytime_hour_start = $formData['daytime_hour_start'];
	$daytime->daytime_hour_end = $formData['daytime_hour_end'];
	$daytime->calendar_id = $formData['calendar_id'];
	$daytime->quota = $formData['quota'];
	$daytime->service_id = $formData['service_id'];
	
	if($daytime->store()) 
	{
	  return true;
	} else {
	  return false;
	}
  }
  
  public function saveMemberDaytime($formData = null)
  {
    $app  = JFactory::getApplication();
    $id   = $id ? $id : $formData['member_daytime_id'];

    $daytime = JTable::getInstance('MemberDaytime','Table');
	
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_members_daytimes as md');
	$query->where('md.daytime_id = ' . $formData['daytime_id']);
	$query->where('md.member_id = ' . $formData['member_id']);
    $db->setQuery($query, 0, 0);
    $result = $db->loadObject();
	
	if($result){
		return false;
	}

    $daytime->load($id);
	
    $daytime->member_id = $formData['member_id'];
	$daytime->service_id = $formData['service_id'];
	$daytime->daytime_id = $formData['daytime_id'];

	if($daytime->store()) 
	{
		return true;
	} else {
		return false;
	}
  }
  

}