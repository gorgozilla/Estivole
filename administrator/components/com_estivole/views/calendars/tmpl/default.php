<?php
/*
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal', 'a.modal');

// $listOrder  = $this->escape($this->state->get('list.ordering'));
// $listDirn   = $this->escape($this->state->get('list.direction'));
// $loggeduser = JFactory::getUser();
// $sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	// Joomla.orderTable = function()
	// {
		// table = document.getElementById("sortTable");
		// direction = document.getElementById("directionTable");
		// order = table.options[table.selectedIndex].value;
		// if (order != '<?php echo $listOrder; ?>')
		// {
			// dirn = 'asc';
		// }
		// else
		// {
			// dirn = direction.options[direction.selectedIndex].value;
		// }
		// Joomla.tableOrdering(order, dirn, '');
	// }
</script>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=calendars');?>" method="post" name="adminForm" id="adminForm">
			<div id="j-main-container">
			<?php
			// Search tools bar
			//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
			?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="left">
						Nom
					</th>
					<th class="left">
						Description
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->calendars as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="left">
						<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=calendar.edit&calendar_id='.(int) $item->calendar_id); ?>">
						<?php echo JText::_($item->name); ?>
						</a>
					</td>
					<td class="left">
						<?php echo JText::_($item->description); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>