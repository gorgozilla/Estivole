<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleControllerAdd extends JControllerForm
{
	public $formData = null;
	public $model = null;
	
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

		if($task=='add_member_daytime'){
			$this->add_member_daytime();
			$app->enqueueMessage('Assignation ajoutée avec succès!');
		}else if($task=='assign_member_daytime'){
			$this->assign_member_daytime();
		}else if($task=='copy_calendar'){
			$this->copy_calendar();
		}else{
			if ( $row = $this->model->saveTime($this->formData) ){
				$app->enqueueMessage('Enregistrement réussi!');
			 }else{
				$app->enqueueMessage('Erreur lors de la création!', 'error');
			 }
		 }
		 //Redirect on referer page
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}

	public function add_member_daytime()
	{
		$app      = JFactory::getApplication();

		// Required objects 
		$input = JFactory::getApplication()->input; 

		// Get the daytimes checkboxes data 
		$cid = $input->get('cid', array(), 'array');

		if (empty($cid))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			foreach($cid as $daytime_id){
				$this->formData['daytime_id']=$daytime_id;
				$this->model->saveMemberDaytime($this->formData);
			}
		}
	}
	
	public function assign_member_daytime()
	{
		$app      = JFactory::getApplication();
		// Required objects 
		$input = JFactory::getApplication()->input; 

		// Get the daytimes checkboxes data 
		$cid = $this->formData['member_id'];

		if (empty($cid))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
				$this->model->saveMemberDaytime($this->formData);
		}
	}
	
	public function copy_calendar()
	{
		$app      = JFactory::getApplication();
		$return = array("success"=>false);
		$input = $app->input; 
		$calendarId = $this->formData['calendar_id'];

        if (empty($calendarId)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
			if($this->model->copyCalendar($calendarId, $this->formData)){
				$return['success'] = true;
				$return['msg'] = 'Yes';
				$app->enqueueMessage('Calendrier(s) copié(s) avec succès!');
			}else{
				$app->enqueueMessage('Erreur!');
			}
			$app->redirect( $_SERVER['HTTP_REFERER']);
        }
	}
}
