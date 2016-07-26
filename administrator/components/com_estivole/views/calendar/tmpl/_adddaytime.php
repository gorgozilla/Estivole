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
<div id="addDayTimeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="newDayTimeModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('Ajouter une date'); ?></h3>
	</div>
	<div class="modal-body" style="height:500px;">
		<div class="row-fluid">
			<form id="addDayTimeForm" method="POST" action="index.php?option=com_estivole&task=add.execute&controller=add">
				<div class="alert alert-info">
					<?php echo JText::_('Calendrier "'.$this->calendar->name.'"'); ?>
				</div>
				<div id="daytime-modal-info" class="media"></div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform_daytime_day-lbl" for="jform_daytime_day" class="required">
					</div>
					<div class="controls">
						<?php echo JHTML::calendar(date("Y-m-d"),'jform[daytime_day]', 'jform_daytime_day', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>'required')); ?>
					</div>
				</div>
				
				<input type="hidden" name="jform[calendar_id]" id="calendar_id" value="<?php echo $this->calendar->calendar_id; ?>" />
				<input type="hidden" name="jform[service_id]" id="service_id" value="1" />
				<input type="hidden" name="table" value="daytime" />
				<input type="hidden" name="model" value="daytime" />
				<input type="hidden" name="task" value="add.execute" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Annuler'); ?></button>
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_('Ajouter la date'); ?></button>
	</div>
	</form>
</div>