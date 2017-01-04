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

<script type="text/javascript" language="javascript">
	jQuery(document).ready(function() {

	});
</script>
<div id="copyCalendarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="copyCalendarModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('Copier un calendrier'); ?></h3>
	</div>
	<div class="modal-body" style="height:500px;">
		<div class="row-fluid">
			<form id="copyCalendarForm" method="POST" action="index.php?option=com_estivole&task=add.copy_calendar&controller=add&tmpl=component">
				<div id="calendar-modal-info" class="media"></div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform_name" for="jform_name" class="required">Nom du calendrier : </label>
					</div>
					<div class="controls">
						<input type="text" name="jform[name]" id="name" value="<?php echo $this->calendar->name; ?>" />
					</div>
				</div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform_year" for="jform_year" class="required">Description : </label>
					</div>
					<div class="controls">
						<input type="text" name="jform[description]" id="description" value="<?php echo $this->calendar->description; ?>" />
					</div>
				</div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform[year]" for="jform_year" class="required">Année : </label>
					</div>
					<div class="controls">
						<?php echo EstivoleHelpersHtml::yearsList('jform[year]'); ?>
					</div>
				</div>
				
				<input type="hidden" name="table" value="calendar" />
				<input type="hidden" name="model" value="calendar" />
				<input type="hidden" name="task" value="add.copy_calendar" />
				<input type="hidden" name="jform[calendar_id]" id="calendar_id" value="<?php echo $this->calendar->calendar_id; ?>" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Annuler'); ?></button>
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_('Copier le calendrier'); ?></button>
	</div>
	</form>
</div>