<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/services.php';
require_once JPATH_COMPONENT . '/models/calendars.php';
require_once JPATH_COMPONENT . '/models/member.php';

class EstivoleViewStaff extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();

		$model = new EstivoleModelMember();

		$this->state	= $this->get('State');
		$this->member		= $this->get('Item');
		$this->form		= $this->get('Form');
		$jinput = $app->input;
		$this->limitstart=$jinput->get('limitstart');
		
		$userId = $this->member->user_id; 
		if($userId!=''){
			$this->user = JFactory::getUser($userId);
		}else{
			$this->user=null;
		}
		$this->userProfile = JUserHelper::getProfile( $userId );
		$this->userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $userId );
		
		if($this->member->member_id!=null){
			$modelCalendars = new EstivoleModelCalendars();
			$modelDaytime = new EstivoleModelDaytime();
			$this->calendars = $modelCalendars->listItems();
			for($i=0; $i<count($this->calendars); $i++){
				$this->calendars[$i]->member_daytimes = $modelDaytime->getMemberDaytimes($this->member->member_id, $this->calendars[$i]->calendar_id);
			}
		}

		$this->addToolbar();

		//display
		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		JToolbarHelper::title(JText::_('Gestion des staffs : Editer un staff'));

		JToolbarHelper::apply('staff.apply');
		JToolbarHelper::save('staff.save');

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('staff.cancel');
		}
		else
		{
			JToolbarHelper::cancel('staff.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}