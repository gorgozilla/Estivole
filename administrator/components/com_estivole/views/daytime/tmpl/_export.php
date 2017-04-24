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
<div id="exportModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo JText::_('Exporter les tranches horaires'); ?></h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<form id="exportForm" method="POST" action="index.php?option=com_estivole&tmpl=component">
				<div class="alert alert-info">
					<?php echo JText::_($this->daytime->daytime_day); ?>
				</div>
				<div id="daytime-modal-info" class="media"></div>
				<div class="control-group ">
					<div class="control-label">
						<label id="jform_service_id" for="jform_service_id" class="required">Secteur : </label>
					</div>
					<div class="controls">
						<?php echo EstivoleHelpersHtml::servicesList(); ?>
					</div>
				</div>
				
				<input type="hidden" name="table" value="daytime" />
				<input type="hidden" name="model" value="daytime" />
				<input type="hidden" name="task" value="add.execute" />
				<input type="hidden" name="jform[daytime_day]" value="<?php echo $this->daytime->daytime_day; ?>" />
				<input type="hidden" name="jform[daytime_id]"  id="daytime_id" value="<?php echo $this->daytime->daytime_id; ?>" />
				<input type="hidden" name="jform[calendar_id]" id="calendar_id" value="<?php echo $this->daytime->calendar_id; ?>" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('Annuler'); ?></button>
		<button class="btn btn-primary" onclick="this.form.submit();">Exporter</button>
	</div>
	</form>
</div>