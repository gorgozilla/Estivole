<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/services.php';
require_once JPATH_COMPONENT . '/models/calendars.php';
require_once JPATH_COMPONENT .'/helpers/job.php';

class EstivoleViewMembers extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		$this->pagination	= $this->get('Pagination');
		$this->searchterms	= $this->state->get('filter.search');
		$this->campingPlace	= $this->state->get('filter.campingPlace');
		$this->services_members	= $this->state->get('filter.services_members');
		$this->user = JFactory::getUser();
		$this->limitstart=$this->state->get('limitstart');

		//retrieve task list from model
		$model = new EstivoleModelMembers();
		$this->members = $model->listItems();
		$this->totalMembersM = $model->getTotalItems('M');
		$this->totalMembersF = $model->getTotalItems('F');
		
		$modelCalendars = new EstivoleModelCalendars();
		$modelDaytime = new EstivoleModelDaytime();
		$this->calendars = $modelCalendars->listItems();
		
		for($i=0; $i<count($this->members); $i++){
			$this->members[$i]->member_daytimes = $modelDaytime->getMemberDaytimes($this->members[$i]->member_id, $this->calendars[0]->calendar_id);
		}
		
		for($i=0; $i<count($this->totalMembersM); $i++){
			$this->totalMembersM[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForTshirt($this->totalMembersM[$i]->member_id, $this->calendars[0]->calendar_id);
			$this->totalShirtsM+=ceil(count($this->totalMembersM[$i]->member_daytimes)/2);
			
			$this->totalMembersM[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForPolo($this->totalMembersM[$i]->member_id, $this->calendars[0]->calendar_id);
			$this->totalPolosM+=ceil(count($this->totalMembersM[$i]->member_daytimes)/2);
		}
		for($i=0; $i<count($this->totalMembersF); $i++){
			$this->totalMembersF[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForTshirt($this->totalMembersF[$i]->member_id, $this->calendars[0]->calendar_id);
			$this->totalShirtsF+=ceil(count($this->totalMembersF[$i]->member_daytimes)/2);
			
			$this->totalMembersF[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForPolo($this->totalMembersF[$i]->member_id, $this->calendars[0]->calendar_id);
			$this->totalPolosF+=ceil(count($this->totalMembersF[$i]->member_daytimes)/2);
		}
			
		EstivoleHelpersEstivole::addSubmenu('members');
		$this->sidebar = JHtmlSidebar::render();

		$this->addToolbar();

		//display
		return parent::display($tpl);
	} 

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar()
    {
        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('Gestion des bénévoles : Bénévoles'));
        JToolbarHelper::addNew('member.add');
		JToolbarHelper::deleteList('Etes-vous sûr de vouloir supprimer le(s) membre(s)? Ceci supprimera également toutes les tranches horaires alloues à ce dernier. Alors?', 'members.deleteListMember');
    }
}