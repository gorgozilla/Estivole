<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class EstivoleViewService extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();

		$model = new EstivoleModelService();

		$this->state	= $this->get('State');
		$this->service		= $this->get('Item');
		$this->form		= $this->get('Form');

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

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Editer un secteur'));

		JToolbarHelper::apply('service.apply');
		JToolbarHelper::save('service.save');

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('service.cancel');
		}
		else
		{
			JToolbarHelper::cancel('service.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}