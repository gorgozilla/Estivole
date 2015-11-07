<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/helpers/estivole.php';
 
class EstivoleViewMembers extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		
		$this->state	= $this->get('State');

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
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // $canDo  = EstivoleHelpersEstivole::getActions();

        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Bénévoles'));
               
        // if ($canDo->get('core.admin'))
        // {
            JToolbarHelper::addNew('member.add');
			JToolbarHelper::editList();
        // }
    }
}