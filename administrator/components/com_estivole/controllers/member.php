<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
  require_once JPATH_COMPONENT . '/models/member.php';
  
class EstivoleControllerMember extends JControllerForm
{
	public $formData = null;
	public $model = null;
	public $member_id = null;
	
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

		if($task=='deleteAvailibility'){
			$member_daytime_id = $input->get('member_daytime_id'); 
			$this->deleteAvailibility($member_daytime_id);
		}else if($task=='delete'){
			$member_id = $input->get('member_id');
			$this->delete($member_id);
		}else if($task=='add'){
			parent::add();
		}else if($task=='edit'){
			parent::edit();
		}else if($task=='apply'){
			if($this->formData['member_id']==null){
				$result = EstivoleHelpersUser::registerUser($this->formData['profilestivole.firstname'].' '.$this->formData['profilestivole.lastname'], $this->formData['username'], $this->formData['email'], 'est1val3', null, $this->formData);
				if($result->success){
					$app->enqueueMessage('Profil enregistré avec succès!');
					$app->redirect('index.php?option=com_estivole&view=member&layout=edit&member_id='.$result->member_id);
				}else{
					$app->enqueueMessage($result->message, 'error');
					$app->redirect($_SERVER['HTTP_REFERER']);
				}
			}else{
				$result = EstivoleHelpersUser::updateUser($this->formData['profilestivole.firstname'].' '.$this->formData['profilestivole.lastname'], $this->formData['username'], $this->formData['email'], $this->formData);
				if($result->success){
					$app->enqueueMessage('Profil enregistré avec succès!');
					$app->redirect('index.php?option=com_estivole&view=member&layout=edit&task=member.edit&controller=member&member_id='.$result->member_id);
				}else{
					$app->enqueueMessage($result->message, 'error');
					$app->redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}else if($task=='save'){
			if($this->formData['member_id']==null){
				$result = EstivoleHelpersUser::registerUser($this->formData['profilestivole.firstname'].' '.$this->formData['profilestivole.lastname'], $this->formData['username'], $this->formData['email'], 'est1val3', null, $this->formData);
				if($result->success){
					$app->enqueueMessage('Profil enregistré avec succès!');
					$app->redirect('index.php?option=com_estivole&view=members');
				}else{
					$app->enqueueMessage($result->message, 'error');
					$app->redirect($_SERVER['HTTP_REFERER']);
				}
			}else{
				parent::save();
			}
		}else if($task=='cancel'){
			parent::cancel();
		}else{
			$this->display();
		}
	}
	
	public function display($cachable = false, $urlparams = false)
	{	
		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->get('view', 'members');
		$vFormat = $document->getType();
		$lName   = $this->input->get('layout', 'default', 'string');
		$this->model = $this->getModel($vName);

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat))
		{
			// Get the model for the view.

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);


			// Push document object into the view.
			$view->document = $document;

			$view->display();
		}

		return $this;
	}
	
	public function delete($member_id)
	{
		$app      = JFactory::getApplication();
		$return = array("success"=>false);

		if($this->model->deleteMember($member_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			$app->enqueueMessage('Bénévole supprimée avec succès!');
		}else{
			$app->enqueueMessage('Erreur!');
		}
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}

	public function deleteAvailibility($member_daytime_id)
	{
		$app      = JFactory::getApplication();
		$return = array("success"=>false);
		if($this->model->deleteAvailibility($member_daytime_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			$return['calendar_dates'] = $this->daytimes;
			$app->enqueueMessage('Date supprimée avec succès!');
		}else{
			$app->enqueueMessage('Erreur!');
		}
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}
}