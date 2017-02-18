<?php // no direct access

defined('_JEXEC') or die;
 
 jimport('joomla.application.component.modellist');
 
class EstivoleModelDaytime extends JModelList
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
    $this->_member_daytime_id = $app->input->get('member_daytime_id', null);
    parent::__construct();       
  }
  
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		
		//Filter (dropdown) service
		$services= $app->getUserStateFromRequest($this->context.'.filter.services_daytime', 'filter_servicesdaytime', '', 'string');
		$this->setState('filter.services_daytime', $services);
		
		parent::populateState('lastname', 'ASC');
	}
  
    public function getForm($data = array(), $loadData = true)
    {
        //Get the form
        $form = $this->loadForm('com_estivole.daytime', 'daytime', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        } else {
            return $form;
        }
    }
    public function loadFormData()
    {
        //load form data
        $data = $this->getitem();
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
	
	if($this->_daytime_day==''){
		$query->select('*');
		$query->group('daytime_day');
	}else{
		$query->select('*');
	}
	
	$query->from('#__estivole_daytimes as b, #__estivole_services as s');

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
		$app = JFactory::getApplication();
		if(is_numeric($this->_calendar_id)) 
		{
			$query->where("b.calendar_id = '" . (int) $this->_calendar_id . "'");
		}

		if($this->_daytime_day) 
		{
			$query->where("b.daytime_day = '".$this->_daytime_day."'");
		}
		
		$service= $this->getState('filter.services_daytime');
		if (!empty($service)) {
			$query->where("b.service_id = '".(int) $service."'");
		}
		
		if (!empty($this->_service_id)) {
			$query->where("b.service_id = '".(int) $this->_service_id."'");
		}

		$query->where("b.service_id = s.service_id");
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
    $db->setQuery($query, $limitstart, $limit);
    $result = $db->loadObjectList();
    return $result;
  }
  
  /**
  * Build query and where for protected _getList function and return a list
  *
  * @return array An array of results.
  */
  public function listItems($calendar_id=null)
  {
	  //Build and querydatabase
    $query = $this->_buildQuery();    
    $query = $this->_buildWhere($query);
	if($calendar_id!=''){
		$query = $query->where("b.calendar_id = '".(int) $calendar_id."'");
	}
	$query->order('b.daytime_day, s.service_name, b.daytime_hour_start');
	//Get list of data
    $list = $this->_getList($query, $this->limitstart, $this->limit);
	
    return $list;
  }

  public function listItemsForExport($service_id)
  {
	$db = JFactory::getDBO();
	$query = $db->getQuery(TRUE);
	$query->select('*');
	$query->from('#__estivole_daytimes as b, #__estivole_members as m, #__estivole_members_daytimes as md, #__estivole_services as s');

	if(is_numeric($this->_calendar_id)) 
	{
		$query->where('b.calendar_id = ' . (int) $this->_calendar_id);
	}

	if($this->_daytime_day) 
	{
		$query->where("b.daytime_day = '".$this->_daytime_day."'");
	}
	
	$service= $this->getState('filter.services_daytime');
	if (!empty($service)) {
		$query->where("b.service_id = '".(int) $service."'");
	}

	$query->where("b.service_id = '".(int) $service_id."'");
	$query->where("md.daytime_id = b.daytime_id");
	$query->where("m.member_id = md.member_id");
	$query->where("b.service_id = s.service_id");
	$query->order('b.daytime_hour_start, b.daytime_hour_end');
    $db->setQuery($query);
    $result = $db->loadObjectList();
	return $result;
  }
  
  public function getDaytime($daytime_id)
  {
    $daytime = JTable::getInstance('Daytime','Table');
    $daytime->load($daytime_id);
	return $daytime;
  }
	
  public function getMemberDaytime($member_daytime)
  {
    $daytime = JTable::getInstance('MemberDaytime','Table');
    $daytime->load($member_daytime);
	return $daytime;
  }
  
	public function getMemberDaytimes($member_id, $calendar_id)
	{

		$query = $this->_buildQuery();   
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_members as m, #__estivole_services as s, #__estivole_daytimes as d, #__estivole_members_daytimes as md');
		if($member_id!=null){
			$query->where('md.member_id = ' . $member_id);
		}
		if($calendar_id!=null){
			$query->where('d.calendar_id = ' . $calendar_id);
		}
		$query->where('md.member_id = m.member_id');
		$query->where('md.service_id = s.service_id');
		$query->where('md.daytime_id = d.daytime_id');
		$query->order('d.daytime_day ASC');

		$db->setQuery($query, 0, 0);
		$result = $db->loadObjectList();

		return $result;
	}
	
	public function getMemberDaytimesForTshirt($member_id, $calendar_id)
	{

		$query = $this->_buildQuery();   
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_members as m, #__estivole_services as s, #__estivole_daytimes as d, #__estivole_members_daytimes as md');
		$query->where('md.member_id = ' . $member_id);
		if($calendar_id!=null){
			$query->where('d.calendar_id = ' . $calendar_id);
		}
		$query->where('md.service_id<>38 AND md.service_id<>36');
		$query->where('md.member_id = m.member_id');
		$query->where('md.service_id = s.service_id');
		$query->where('md.daytime_id = d.daytime_id');
		$query->order('d.daytime_day ASC');

		$db->setQuery($query, 0, 0);
		$result = $db->loadObjectList();

		return $result;
	}

	public function getMemberDaytimesForPolo($member_id, $calendar_id)
	{

		$query = $this->_buildQuery();   
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_members as m, #__estivole_services as s, #__estivole_daytimes as d, #__estivole_members_daytimes as md');
		$query->where('md.member_id = ' . $member_id);
		if($calendar_id!=null){
			$query->where('d.calendar_id = ' . $calendar_id);
		}
		$query->where('(md.service_id=38 OR md.service_id=36)');
		$query->where('md.member_id = m.member_id');
		$query->where('md.service_id = s.service_id');
		$query->where('md.daytime_id = d.daytime_id');
		$query->order('d.daytime_day ASC');

		$db->setQuery($query, 0, 0);
		$result = $db->loadObjectList();

		return $result;
	}
  
  public function getServiceDaytimes($cid)
  {
	$cids = implode( ',', $cid );
		 
    $query = $this->_buildQuery();   
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	$query->select('*');
	$query->from('#__estivole_members_daytimes as md');
	$query->where('md.service_id IN (' . $cids.')');
	
    $db->setQuery($query, 0, 0);
    $result = $db->loadObjectList();
    return $result;
  }
  
   public function getDaytimeDaytimes($daytime_id)
  {
    $query = $this->_buildQuery();   
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);

	$query->select('*');
	$query->from('#__estivole_members_daytimes as md');
	$query->where('md.daytime_id = ' . $daytime_id);

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
  
  public function getQuotasByDaytimeId($daytime_id)
  {
    $query = $this->_buildQuery();   
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_daytimes as d, #__estivole_members_daytimes as md');
	$query->where('md.daytime_id = ' . $daytime_id);
	$query->where('md.daytime_id = d.daytime_id');
	//echo $query;exit;
    $db->setQuery($query, 0, 0);
    $result = $db->loadObjectList();
 
    return $result;
  }
  
  public function getQuotasByDaytimeDay($daytime_day)
  {
    $query = $this->_buildQuery();   
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
	
	$query->select('*');
	$query->from('#__estivole_daytimes as d, #__estivole_members_daytimes as md');
	$query->where('d.daytime_day = \'' . $daytime_day.'\'');
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
	$daytime->description = $formData['description'];
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
    $id   = $id ? $id : $formData['member_daytime_id'];
    $daytime = JTable::getInstance('MemberDaytime','Table');
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
  
	public function changeStatusDaytime($member_daytime_id, $status_id)
	{
		$daytime = JTable::getInstance('MemberDaytime','Table');
		$daytime->load($member_daytime_id);
		$daytime->status_id = $status_id;

		if($daytime->store()) 
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteDaytime($daytime_id = null)
	{
		$app  = JFactory::getApplication();
		$id   = $id ? $id : $daytime_id;

		$daytime = JTable::getInstance('Daytime','Table');
		$daytime->load($id);
		
		if ($daytime->delete()) 
		{
			return true;
		}  
		return false;
	}
}