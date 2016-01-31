<?php
/*
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div id="addAvailibilityModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="newAvailibilityModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('Ajouter une tranche horaire'); ?></h3>
	</div>
	<div class="modal-body" style="height:500px;">
		<div class="row-fluid">
			<form id="sendEmailMember" method="POST" action="index.php?option=com_estivole&task=email.sendEmailMember&controller=email&tmpl=component">
				<div id="availibility-modal-info" class="media"></div>
				<div class="control-group ">
					<div class="control-label">
						<label id="emailBody" for="emailBody" class="required">Votre message : </label>
					</div>
					<div class="controls">
						<textarea name="emailBody"></textarea>
					</div>
				</div>
				
				<input type="hidden" name="table" value="member_daytime" />
				<input type="hidden" name="model" value="daytime" />
				<input type="hidden" name="task" value="add.add_member_daytime" />
				<input type="hidden" name="jform[member_id]" id="member_id" value="<?php echo $this->member->member_id; ?>" />
				<input type="hidden" name="jform[calendar_id]" id="calendar_id" value="<?php echo $this->calendars[0]->calendar_id; ?>" />
				<input type="hidden" name="jform[member_daytime_id]" id="member_daytime_id" value="" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Annuler'); ?></button>
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_('Ajouter la tranche horaire'); ?></button>
	</div>
	</form>
</div>