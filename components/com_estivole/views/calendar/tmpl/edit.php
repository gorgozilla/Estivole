<?php
/*
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'calendar.cancel' || document.formvalidator.isValid(document.id('calendar-form'))) {
			<?php //echo $this->form->getField('summary')->save(); ?>
			Joomla.submitform(task, document.getElementById('calendar-form'));
		}
	}
</script>

<div id="j-main-container" class="span12">
	<h1><?php echo $this->calendar->name; ?></h1>
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=calendar&layout=edit&calendar_id=' . (int) $this->calendar->calendar_id);?>" method="post" name="adminForm" id="calendar-form" class="form-validate">
		<div class="span12">
			<div class="form-inline form-inline-header">
				<?php echo $this->form->getControlGroup('name'); ?>
				<?php echo $this->form->getControlGroup('description'); ?>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
	<h2>Dates du calendrier</h2>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Jour'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->daytimes as $i => $item) :
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
						<?php echo JHtml::_('grid.id', $i, $item->daytime_id); ?>
					<?php //endif; ?>
				</td>
				<td class="left">
					<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $this->calendar->calendar_id; ?>&daytime=<?php echo $item->daytime_day; ?>">
						<?php echo date('d-m-Y', strtotime($item->daytime_day)); ?>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addDayTimeModal('<?php echo $this->calendar->calendar_id; ?>');"><?php echo JText::_('Ajouter une date'); ?></a>
</div>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'calendar'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_adddaytime.php'); ?>