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
		}else{
			if ( $row = $this->model->saveTime($this->formData) ){
				$app->enqueueMessage('Date ajoutée avec succès!');
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

				if($this->model->saveMemberDaytime($this->formData)){
					EstivoleHelpersMail::sendMemberDaytimeToAdmin($this->formData['member_id'], $this->formData['service_id'], $this->formData['daytime_id']);
					$app->enqueueMessage('Date ajoutée avec succès!<br />Un email a été envoyé à notre responsable bénévoles afin de valider votre inscription. Ce dernier vous recontactera dans les plus brefs délais.');
				}else{
					$app->enqueueMessage('Vous êtes déjà inscrit pour ce secteur et cette tranche horaire!', 'error');
				}
			}
		}
	}
}
