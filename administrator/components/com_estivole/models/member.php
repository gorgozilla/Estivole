<?php 
// no direct access
defined('_JEXEC') or die;
 
require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleModelMember extends JModelAdmin
{

	/**
	* Protected fields
	**/
	var $_member_id     = null;

	function __construct()
	{
		$app = JFactory::getApplication();
		$this->_member_id = $app->input->get('member_id', null);


		parent::__construct();       
	}
  
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form
        $form = $this->loadForm('com_estivole.member', 'member', array('control' => 'jform', 'load_data' => $loadData));
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
		$query->from('#__estivole_members as b');

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
		if(is_numeric($this->_member_id)) 
		{
		  $query->where('b.member_id = ' . (int) $this->_member_id);
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
	* Delete a member daytime
	* @param int      ID of the member to delete
	* @return boolean True if successfully deleted
	*/
	public function deleteMember($member_id = null)
	{

		$app  = JFactory::getApplication();
		$id   = $id ? $id : $member_id;

		$member = JTable::getInstance('Member','Table');
		$member->load($id);
		
		$modelDaytime = new EstivoleModelDaytime();
		$memberDaytimes = $modelDaytime->getMemberDaytimes($member_id, null);
		
		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('Daytime','Table');
			$daytime->load($memberDaytime->daytime_id);
			if ($daytime->delete()) 
			{
				return false;
			}
		}

		$userid = JRequest::getInt('id'); // getting user id from url
		$user = JUser::getInstance($member->user_id);
		if($user->delete())
		{
			if ($member->delete()) 
			{
				return true;
			}  
		}
		return false;
	}

	/**
	* Delete a member daytime
	* @param int      ID of the member to delete
	* @return boolean True if successfully deleted
	*/
	public function deleteAvailibility($member_daytime_id = null)
	{
		$app  = JFactory::getApplication();
		$id   = $id ? $id : $app->input->get('member_daytime_id');

		$daytime = JTable::getInstance('MemberDaytime','Table');

		$daytime->load($id);

		if ($daytime->delete()) 
		{
			return true;
		}      
	}
}