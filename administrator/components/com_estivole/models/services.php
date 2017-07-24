<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport('joomla.application.component.modellist');
 
class EstivoleModelServices extends JModelList
{

  /**
  * Protected fields
  **/
  var $_service_id     = null;
  
  function __construct()
  {
    $app = JFactory::getApplication();
    $this->_service_id = $app->input->get('service_id', null);
	
	$config['filter_fields'] = array(
		'b.service_name'
	);
    
    parent::__construct($config);       
  }
  
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		
		//Filter (dropdown) service
		$services= $app->getUserStateFromRequest($this->context.'.filter.services_daytime', 'filter_servicesdaytime', '', 'string');
		$this->setState('filter.services_daytime', $services);
		
		parent::populateState('service_name', 'ASC');
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
    $query->from('#__estivole_services as b');
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
    if(is_numeric($this->_service_id)) 
    {
      $query->where('b.service_id = ' . (int) $this->_service_id);
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
    $db = JFactory::getDBO();
	$query->order($db->escape($this->getState('list.ordering', 'b.service_name')).' '.$db->escape($this->getState('list.direction', 'ASC')));
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
  public function getServicesForExport($calendar_id)
  {
	$db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);

    $query->select('*');
    $query->from('#__estivole_services as b, #__estivole_members_daytimes as md, #__estivole_daytimes as d');
	$query->where('d.calendar_id = '.$calendar_id);
    $query->where('b.service_id = md.service_id');
    $query->where('md.daytime_id = d.daytime_id');
	$query->order('b.service_name');
	
	$service= $this->getState('filter.services_daytime');
	if (!empty($service)) {
		$query->where("b.service_id = '".(int) $service."'");
	}
	
    $query->group('md.service_id');
    $db->setQuery($query);
    $list = $db->loadObjectList();
	
    return $list;
  }
}