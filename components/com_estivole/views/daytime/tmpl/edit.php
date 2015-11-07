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
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'daytime.cancel' || document.formvalidator.isValid(document.id('daytime-form'))) {
			<?php //echo $this->form->getField('summary')->save(); ?>
			Joomla.submitform(task, document.getElementById('daytime-form'));
		}
	}
</script>
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=daytime&layout=edit&daytime_id=' . (int) $this->daytime->daytime_id);?>" method="post" name="adminForm" id="daytime-form" class="form-validate">
		<input type="hidden" name="calendar_id" value="<?php echo $this->daytime->calendar_id; ?>" />
		<input type="hidden" name="task" value="" />
	</form>
	<h1><?php echo date('d-m-Y', strtotime($this->daytime->daytime_day)); ?></h1>
	<h2>Tranches horaire de la date</h2>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Jour'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Heure début'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Heure fin'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Quota'); ?>
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
					<a href="javascript:void();" onclick="addDayTimeModal('<?php echo $item->daytime_id; ?>','<?php echo $item->service_id; ?>','<?php echo $item->daytime_hour_start; ?>', '<?php echo $item->daytime_hour_end; ?>', '<?php echo $item->quota; ?>');"><?php echo date('d-m-Y',strtotime($item->daytime_day)); ?></a>
				</td>
				<td class="left">
					<?php echo date('H:i', strtotime($item->daytime_hour_start));  ?>
				</td>
				<td class="left">
					<?php echo date('H:i', strtotime($item->daytime_hour_end));  ?>
				</td>
				<td class="left">
					<?php echo $item->filledQuota!=''?$item->filledQuota:'0'.' / '.JText::_($item->quota); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addDayTimeModal();">
		<?php echo JText::_('Ajouter une tranche horaire'); ?>
	</a>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'daytime'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_addtime.php'); ?>