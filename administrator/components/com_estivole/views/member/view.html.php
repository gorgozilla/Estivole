<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/services.php';
require_once JPATH_COMPONENT . '/models/calendars.php';

class EstivoleViewMember extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();

		$model = new EstivoleModelMember();

		$this->state	= $this->get('State');
		$this->member		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->user = JFactory::getUser();
		
		$userId = $this->member->user_id; 
		$this->userProfile = JUserHelper::getProfile( $userId );
		
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

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Editer un bénévole'));

		JToolbarHelper::apply('member.apply');
		JToolbarHelper::save('member.save');

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('member.cancel');
		}
		else
		{
			JToolbarHelper::cancel('member.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}