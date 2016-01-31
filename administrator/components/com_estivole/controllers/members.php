<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 require_once JPATH_COMPONENT . '/models/service.php';
 require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleControllerMembers extends JControllerAdmin
{
	public $formData = null;
	public $model = null;
	public $search_text = null;
	
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		$modelName  = $app->input->get('model', 'Member');

		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListMember'){
			$this->deleteListMember();
		}else{
			$this->display();
		}
	}
	
	public function deleteListMember()
	{
		$app      = JFactory::getApplication();
		$member_id  = $app->input->get('member_id');
		$return = array("success"=>false);
        $ids    = JRequest::getVar('cid', array(), '', 'array');
		
        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
			foreach($ids as $id){
				$this->model->deleteMember($id);
			}
			$app->redirect( $_SERVER['HTTP_REFERER']);
        }
	}
	/**
	 * Method to provide child classes the opportunity to process after the delete task.
	 *
	 * @param   JModelLegacy  $model  The model for the component
	 * @param   mixed         $ids    array of ids deleted.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}
}