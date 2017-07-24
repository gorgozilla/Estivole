<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/member.php';
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
		$this->validationStatus	= $this->state->get('filter.validationStatus');
		$this->user = JFactory::getUser();
		$this->limitstart=$this->state->get('limitstart');
		
		$modelCalendars = new EstivoleModelCalendars();
		$modelDaytime = new EstivoleModelDaytime();
		$modelMember = new EstivoleModelMember();
		$this->calendars = $modelCalendars->listItems();
		$this->filterCalendarId	= $this->state->get('filter.calendar_id') == null ? $this->calendars[0]->calendar_id : $this->state->get('filter.calendar_id');
		$this->filterMemberStatus = $this->state->get('filter.member_status') == null ? 'Y' : $this->state->get('filter.member_status');
		
		//retrieve task list from model
		$model = new EstivoleModelMembers();
		$this->members = $model->listItems();
		$this->totalMembersM = $model->getTotalItems('M');
		$this->totalMembersF = $model->getTotalItems('F');
		
		for($i=0; $i<count($this->members); $i++){
			$this->members[$i]->member_daytimes = $modelDaytime->getMemberDaytimes($this->members[$i]->member_id, $this->filterCalendarId);
			$this->members[$i]->hasNonValidatedDaytimes=$modelMember->hasNonValidatedDaytimes($this->members[$i]->member_id,$this->filterCalendarId);
		}
		
		for($i=0; $i<count($this->totalMembersM); $i++){
			$this->totalMembersM[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForTshirt($this->totalMembersM[$i]->member_id, $this->filterCalendarId);
			$this->totalShirtsM+=ceil(count($this->totalMembersM[$i]->member_daytimes)/2);
			
			$this->totalMembersForPolosM[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForPolo($this->totalMembersM[$i]->member_id, $this->filterCalendarId);
			$this->totalPolosM+=ceil(count($this->totalMembersForPolosM[$i]->member_daytimes)/2);
		}
		for($i=0; $i<count($this->totalMembersF); $i++){
			$this->totalMembersF[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForTshirt($this->totalMembersF[$i]->member_id, $this->filterCalendarId);
			$this->totalShirtsF+=ceil(count($this->totalMembersF[$i]->member_daytimes)/2);
			
			$this->totalMembersForPolosF[$i]->member_daytimes = $modelDaytime->getMemberDaytimesForPolo($this->totalMembersF[$i]->member_id, $this->filterCalendarId);
			$this->totalPolosF+=ceil(count($this->totalMembersForPolosF[$i]->member_daytimes)/2);
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
		JToolbarHelper::editList('member.edit');
		JToolbarHelper::deleteList('Etes-vous sûr de vouloir supprimer le(s) membre(s)? Ceci supprimera également toutes les tranches horaires alloues à ce dernier. Alors?', 'members.deleteListMember');
    }
}