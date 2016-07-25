<?php // no direct access

defined('_JEXEC') or die;
 
class EstivoleModelService extends JModelAdmin
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
  
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form
        $form = $this->loadForm('com_estivole.service', 'service', array('control' => 'jform', 'load_data' => $loadData));
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

    // $query->where('b.published = ' . (int) $this->_published);
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
	* Delete a member
	* @param int      ID of the member to delete
	* @return boolean True if successfully deleted
	*/
	public function deleteService($cid = null)
	{
		$app  = JFactory::getApplication();
		$id   = $id ? $id : $this->_service_id;
		
		if (count( $cid ))
		{
		 JArrayHelper::toInteger($cid);
		 $cids = implode( ',', $cid );
		 $query = 'DELETE FROM #__estivole_services'
			   . ' WHERE service_id IN ( '.$cids.' )';
			  $this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			 }
		}
		return true;
	}

	function publish($cid, $publish) 
	{
		if (count( $cid ))
		{
		 JArrayHelper::toInteger($cid);
		 $cids = implode( ',', $cid );
		 $query = 'UPDATE #__estivole_services'
			   . ' SET published = '.(int) $publish
			   . ' WHERE service_id IN ( '.$cids.' )';
			  $this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			 }
		}
		return true;
	 }
}