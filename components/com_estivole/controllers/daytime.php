<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
  require_once JPATH_COMPONENT . '/models/daytime.php';
 
class EstivoleControllerDaytime extends JControllerLegacy
{
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->service_id = $input->get('service_id'); 
		$this->calendar_id = $input->get('calendar_id'); 

		if($task=='getDaytimesByService'){
			$this->getDaytimesByService($this->calendar_id, $this->service_id);
		}
	}
	
	public function getDaytimesByService($calendar_id, $service_id)
	{
		$modeldaytime = new estivolemodeldaytime();
		$this->daytimes = $modeldaytime->listitems();
		
		echo json_encode($this->daytimes);
		exit;
	}
}