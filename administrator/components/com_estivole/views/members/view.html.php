<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 

class EstivoleViewMembers extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		$this->pagination	= $this->get('Pagination');
		$this->searchterms	= $this->state->get('filter.search');
		$this->user = JFactory::getUser();
		$userId = $this->user->id; 
		$this->userProfile = JUserHelper::getProfile( $userId );

		//retrieve task list from model
		$model = new EstivoleModelMembers();
		$this->members = $model->listItems();
		
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
    }
}