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
		$modelName  = $app->input->get('model', 'Service');

		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListService'){
			$this->deleteListService();
		}else{
			$this->display();
		}
	}
	
	public function deleteListService()
	{
		$app      = JFactory::getApplication();
		$service_id  = $app->input->get('service_id');
		$return = array("success"=>false);
		
		$modelDaytime = new EstivoleModelDaytime();

		$memberDaytimes = $modelDaytime->getServiceDaytimes($service_id);
		
		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('MemberDaytime','Table');
			$daytime->load($memberDaytime->member_daytime_id);
			if (!$daytime->delete()) 
			{
				return false;
			}
		}

		if($this->model->deleteService($service_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			$app->enqueueMessage('Secteur supprimé avec succès!');
		}else{
			$app->enqueueMessage('Erreur!');
		}
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