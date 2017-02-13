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
<?php if($this->member->member_id!=null){ ?>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function() {
			// var daytime = jQuery("#addDayTimeForm #jformdaytime").val();
			// var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
			// var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
			// getCalendarDaytimes(calendar_id, daytime, service_id);
			// getDaytimesByService(calendar_id, service_id);
			
			// jQuery("#addDayTimeForm #jformcalendar_id, #addDayTimeForm #jformdaytime, #addDayTimeForm #jformservice_id").change(function() {
				// var daytime = jQuery("#addDayTimeForm #jformdaytime").val();
				// var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
				// var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
				// getCalendarDaytimes(calendar_id, daytime, service_id);
			// });
			
			// jQuery("#addDayTimeForm #jformcalendar_id, #addDayTimeForm #jformservice_id").change(function() {
				// var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
				// var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
				// getDaytimesByService(calendar_id, service_id);
			// });
		});
	</script>
<?php } ?>
<div id="assignAvailibilityModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="newAssignAvailibilityModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('Assigner un membre à la tranche horaire'); ?></h3>
	</div>
	<div class="modal-body" style=height:200px>
		<div class="row-fluid">
			<form id="addDayTimeForm" method="POST" action="index.php?option=com_estivole&task=add.assign_member_daytime&controller=add&tmpl=component">
				<div id="availibility-modal-info" class="media"></div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform_member_id" for="jform_member_id" class="required">Bénévole : </label>
					</div>
					<div class="controls">
						<?php echo EstivoleHelpersHtml::membersList(); ?>
					</div>
				</div>
				
				<input type="hidden" name="table" value="member_daytime" />
				<input type="hidden" name="model" value="daytime" />
				<input type="hidden" name="task" value="add.assign_member_daytime" />
				<input type="hidden" name="jform[daytime_id]" id="daytime_id" value="" />
				<input type="hidden" name="jform[service_id]" id="service_id" value="" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Annuler'); ?></button>
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_('Assigner la tranche horaire'); ?></button>
	</div>
	</form>
</div>