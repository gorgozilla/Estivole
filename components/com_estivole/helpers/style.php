<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class EstivoleHelpersStyle
{
	function load()
	{
		$document = JFactory::getDocument();

		// //stylesheets
		//$document->addStylesheet(JURI::base().'components/com_estivole/assets/css/jquery.dataTables.min.css');
		
		//javascripts
		$document->addScript(JURI::base().'components/com_estivole/assets/js/estivole.js');
		//$document->addScript(JURI::base().'components/com_estivole/assets/js/jquery.dataTables.min.js');
	}
}