<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 require_once JPATH_COMPONENT . '/models/service.php';
 require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleControllerServices extends JControllerAdmin
{
	public $formData = null;
	public $model = null;
	
	public function __construct($config = array())
    {
        parent::__construct($config);
    }
	
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		$modelName  = $app->input->get('model', 'Service');
		
		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListService'){
			$this->deleteListService($task);
		}else if($task=='publishList' || $task=='unpublishList'){
			$this->publishList($task);
		}else{
			$this->display();
		}
	}
	
	public function deleteListService($task)
	{
		$app      = JFactory::getApplication();
		$service_id  = $app->input->get('service_id');
        $ids    = JRequest::getVar('cid', array(), '', 'array');

        $value  = JArrayHelper::getValue($task, 0, 'int');
		$return = array("success"=>false);
		$modelDaytime = new EstivoleModelDaytime();
		$memberDaytimes = $modelDaytime->getServiceDaytimes($ids);
		
		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('MemberDaytime','Table');
			$daytime->load($memberDaytime->member_daytime_id);
			if (!$daytime->delete()) 
			{
				return false;
			}
		}

		// if($this->model->deleteService($service_id)){
			// $return['success'] = true;
			// $return['msg'] = 'Yes';
			// $app->enqueueMessage('Secteur supprimé avec succès!');
		// }else{
			// $app->enqueueMessage('Erreur!');
		// }

        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
            // Get the model.
            $model = $this->getModel('service');

            // Publish the items.
            if (!$model->deleteService($ids)) {
                JError::raiseWarning(500, $model->getError());
            }
        }
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}

    /**
     * Method to toggle the featured setting of a list of articles.
     *
     * @return  void
     * @since   1.6
     */
    function publishList($task)
    {
        // Initialise variables.
		$app      = JFactory::getApplication();
        $user   = JFactory::getUser();
        $ids    = JRequest::getVar('cid', array(), '', 'array');
        $values = array('publishList' => 1, 'unpublishList' => 0);
        $value  = JArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
            // Get the model.
            $model = $this->getModel('service');

            // Publish the items.
            if (!$model->publish($ids, $value)) {
                JError::raiseWarning(500, $model->getError());
            }
        }
		$app->enqueueMessage('Publication modifiée avec succès!');
		$app->redirect( $_SERVER['HTTP_REFERER']);
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