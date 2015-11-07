<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/calendars.php';
require_once JPATH_COMPONENT . '/models/member.php';

class EstivoleViewMember extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$model = new EstivoleModelMember();
		$this->user = JFactory::getUser();
		$userId = $this->user->id; 
		$this->userProfile = JUserHelper::getProfile( $userId );
		
		if(!$this->user->guest){
			$this->state	= $this->get('State');
			$this->member	= $model->getItem($this->user->id);
			$this->form		= $this->get('Form');
						
			$modelcalendars = new estivolemodelcalendars();
			$modeldaytime = new estivolemodeldaytime();
			$this->calendars = $modelcalendars->listitems();

			for($i=0; $i<count($this->calendars); $i++){
				$this->calendars[$i]->member_daytimes = $modeldaytime->getmemberdaytimes($this->member->member_id, $this->calendars[$i]->calendar_id);
			}
		}

		//display
		return parent::display($tpl);
	}
}