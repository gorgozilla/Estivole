<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EstivoleModelServices extends JModelLegacy
{

  /**
  * Protected fields
  **/
  var $_service_id     = null;
  
  function __construct()
  {
    $app = JFactory::getApplication();
    $this->_service_id = $app->input->get('service_id', null);
	
    
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
  protected function _buildWhere(&$query, $onlyPublished)
  {

    if(is_numeric($this->_service_id)) 
    {
      $query->where('b.service_id = ' . (int) $this->_service_id);
    }
	
	if($onlyPublished){
		$query->where('b.published = 1');
	}

    return $query;
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
  public function listItems($onlyPublished = true)
  {
    $query = $this->_buildQuery();    
    $query = $this->_buildWhere($query, $onlyPublished);
	$query->order('b.service_name ASC');
    $list = $this->_getList($query, $this->limitstart, $this->limit);

    return $list;
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