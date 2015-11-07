<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/services.php';

class EstivoleViewMember extends JViewLegacy
{
    function display($tpl = null)
	{
		// Get the document object.
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		
		// Get the model for the view.
		$modelDaytime = new EstivoleModelDaytime();
		$this->member_id = $app->input->get('member_id', null);
		
		$this->daytimes = $modelDaytime->listItems();
		for($i=0; $i<count($this->daytimes); $i++){
			$this->daytimes[$i]->filledQuota = count($modelDaytime->getQuotasByDaytime($this->daytimes[$i]->daytime_id));
			$this->daytimes[$i]->isAvailable = $modelDaytime->isDaytimeAvailableForMember($this->member_id, $this->daytimes[$i]->daytime_id);
			$this->daytimes[$i]->isComplete = $modelDaytime->isDaytimeComplete($this->daytimes[$i]->daytime_id, $this->daytimes[$i]->filledQuota);
		}

        // Call parent
        parent::display($tpl);
    }
}