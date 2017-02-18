<?php
 
defined('JPATH_BASE') or die;
 
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Custom Field class for the Joomla Framework.
 *
 * @package		Joomla.Administrator
 * @subpackage	        com_my
 * @since		1.6
 */
class JFormFieldMembers extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Members';
 
	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptionsTshirtSize()
	{
		$arr = array(
			JHTML::_('select.option', 'S', JText::_('S') ),
			JHTML::_('select.option', 'M', JText::_('M') ),
			JHTML::_('select.option', 'L', JText::_('L') ),
			JHTML::_('select.option', 'XL', JText::_('XL') ),
			JHTML::_('select.option', 'XXL', JText::_('XXL')),
			JHTML::_('select.option', 'XXXL', JText::_('XXXL'))
		);
 
		return $arr;
	}
	
	public function getOptionsSex()
	{
		$arr = array(
			JHTML::_('select.option', 'M', JText::_('Masculin')),
			JHTML::_('select.option', 'F', JText::_('Féminin'))
		);
 
		return $arr;
	}
	
	public function getOptionsMemberStatus()
	{
		$arr = array(
			JHTML::_('select.option', '1', JText::_('Actif')),
			JHTML::_('select.option', '0', JText::_('Non-actif (pas inscrit à aucune tranche horaire)'))
		);
 
		return $arr;
	}
	
	public function getOptionsCamping()
	{
		$arr = array(JHTML::_('select.option', '1', 'Dors au camping' ));
		return $arr;
	}
}