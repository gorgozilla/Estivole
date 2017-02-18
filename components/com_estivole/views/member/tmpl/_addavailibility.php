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
JHtml::_('formbehavior.chosen');
?>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function() {
			var daytime = jQuery("#addDayTimeForm #jformdaytime").chosen().val();
			var service_id = jQuery("#addDayTimeForm #jformservice_id").chosen().val();
			var calendar_id = jQuery("#addDayTimeForm #calendar_id").val();
			
			getCalendarDaytimes(calendar_id, daytime, service_id);
			getDaytimesByService(calendar_id, service_id);
			
			jQuery("#addDayTimeForm #jformdaytime, #addDayTimeForm #jformservice_id").chosen().change(function() {
				var daytime = jQuery("#addDayTimeForm #jformdaytime").chosen().val();
				var service_id = jQuery("#addDayTimeForm #jformservice_id").chosen().val();
				var calendar_id = jQuery("#addDayTimeForm #calendar_id").val();
				getCalendarDaytimes(calendar_id, daytime, service_id);
			});
			
			jQuery("#addDayTimeForm #jformservice_id").chosen().change(function() {
				var service_id = jQuery("#addDayTimeForm #jformservice_id").chosen().val();
				var calendar_id = jQuery("#addDayTimeForm #calendar_id").val();
				getDaytimesByService(calendar_id, service_id);
			});
		});
	</script>
	<h2>Ajouter une disponibilité</h2>
	<form id="addDayTimeForm" method="POST" action="index.php?option=com_estivole&task=add.add_member_daytime&controller=add&tmpl=component">
		<div id="availibility-modal-info" class="media"></div>
		<p>Sélectionnez le secteur de votre choix dans la liste ci-dessous. La liste des dates d'inscription sera automatiquement adaptée en fonction du secteur sélectionné.</p>
		<div class="control-group ">
			<div class="control-label">
				<label id="jform_service_id" for="jform_service_id" class="required">Secteur : </label>
			</div>
			<div class="controls">
				<?php echo EstivoleHelpersHtml::servicesList(); ?>
			</div>
		</div>
		
		<p>Sélectionnez la date pour laquelle vous souhaitez participer comme bénévole.</p>
		<div class="control-group ">
			<div class="control-label">
				<label id="jform_daytime_id" for="jform_daytime_id" class="required">Date : </label>
			</div>
			<div class="controls">
				<?php echo EstivoleHelpersHtml::datesList($this->calendars[0]->calendar_id,$this->calendars[0]->service_id); ?>
			</div>
		</div>
		
		<p class="text-danger"><strong>Important :<br />Pour les bénévoles travaillant le 1er août, soit durant la soirée gratuite, un billet vous sera offert l'année prochaine pour la soirée de votre choix!</strong></p>
		
		<div id="availibilityTableDiv">
		
		</div>
		<p>
			<i>Légende : </i><br />
			<span style="background-color:#F89406;">&nbsp;<i class="icon-time"></i>&nbsp;</span> Date réservée en attente de validation<br />
			<span style="background-color:#0f0;">&nbsp;<i class="icon-ok"></i>&nbsp;</span> Date réservée validée<br />
			<span style="background-color:#f00;">&nbsp;<i class="icon-remove"></i>&nbsp;</span> Complet
		</p>
		
		<input type="hidden" name="table" value="member_daytime" />
		<input type="hidden" name="model" value="daytime" />
		<input type="hidden" name="task" value="add.add_member_daytime" />
		<input type="hidden" name="jform[calendar_id]" id="calendar_id" value="<?php echo $this->calendars[0]->calendar_id; ?>" />
		<input type="hidden" name="jform[member_id]" id="member_id" value="<?php echo $this->member->member_id; ?>" />
		<input type="hidden" name="jform[member_daytime_id]" id="member_daytime_id" value="" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="modal-footer">
			<button class="btn" onclick="this.form.submit();" id="btnAddTimeSchedule"><?php echo JText::_('Ajouter la tranche horaire'); ?></button>
		</div>
	</form>