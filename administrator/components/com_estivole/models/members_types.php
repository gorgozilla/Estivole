<?php // no direct access

defined('_JEXEC') or die;
 
class EstivoleModelMembersTypes extends JModelLegacy
{
  
  function __construct()
  {
    $app = JFactory::getApplication();
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
    $query->from('#__estivole_members_types as mt');
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
    if(is_numeric($this->mt_id)) 
    {
      $query->where('mt.member_type_id = ' . (int) $this->mt_id);
    }
    return $query;
  }
  
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		return $item;
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
    $id   = $id ? $id : $app->input->get('member_type_id');

    $member_type = JTable::getInstance('MemberType','Table');
    $member_type->load($id);
    if($member_type->delete()) 
    {
      return true;
    } else {
      return false;
    }
  }
}