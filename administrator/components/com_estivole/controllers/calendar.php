<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleControllerCalendar extends JControllerForm
{
	public $formData = null;
	public $model = null;
	
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		$modelName  = $app->input->get('model', 'Calendar');

		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListDaytime'){
			$this->deleteListDaytime();
		}elseif($task=='cancel'){
			$this->cancel();
		}else{
			$this->edit();
		}
	}
	
	public function deleteListDaytime()
	{
		$app      = JFactory::getApplication();
		$daytime_id  = $app->input->get('daytime_id');
		$return = array("success"=>false);
		
		$modelDaytime = new EstivoleModelDaytime();

		$memberDaytimes = $modelDaytime->getDaytimeDaytimes($daytime_id);

		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('MemberDaytime','Table');
			$daytime->load($memberDaytime->member_daytime_id);

			if (!$daytime->delete()) 
			{
				return false;
			}
		}


		if($modelDaytime->deleteDaytime($daytime_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			$app->enqueueMessage('Date supprimée avec succès!');
		}else{
			$app->enqueueMessage('Erreur!');
		}
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}
}