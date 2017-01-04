<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EstivoleControllerCalendars extends JControllerAdmin
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
		$modelName  = $app->input->get('model', 'Calendar');

		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListCalendar'){
			$this->deleteListCalendar();
		}else{
			$this->display();
		}
	}
	
	public function deleteListCalendar()
	{
		$app      = JFactory::getApplication();
		$calendar_id  = $app->input->get('calendar_id');
		$return = array("success"=>false);
        $ids    = JRequest::getVar('cid', array(), '', 'array');
		
        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
			foreach($ids as $id){
				$this->model->deleteCalendar($id);
			}
			$app->redirect( $_SERVER['HTTP_REFERER']);
        }
	}
}