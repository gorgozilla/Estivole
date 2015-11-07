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
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Nom'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Description'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->calendars as $i => $item) :
				// $canEdit   = $this->canDo->get('core.edit');
				// $canChange = $loggeduser->authorise('core.edit.state',	'com_users');

				// // If this group is super admin and this user is not super admin, $canEdit is false
				// if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
				// {
					// $canEdit   = false;
					// $canChange = false;
				// }
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php //if ($canEdit) : ?>
							<?php echo JHtml::_('grid.id', $i, $item->calendar_id); ?>
						<?php //endif; ?>
					</td>
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

		<?php //Load the batch processing form. ?>
		<?php //echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>