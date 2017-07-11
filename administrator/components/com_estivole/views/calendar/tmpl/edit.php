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

//Get services options
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$services = JFormHelper::loadFieldType('Services', false);
$servicesOptions=$services->getOptions(); // works only if you set your field getOptions on public!!
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

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
	<h1>Calendriers > <?php echo $this->calendar->name; ?></h1>
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
				<th class="left">
					<?php echo JText::_('Jour'); ?>
				</th>
				<th class="center">
					<?php echo JText::_('Nbre campeurs'); ?>
				</th>
				<th class="center">
					<?php echo JText::_('Quota'); ?>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->daytimes as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left">
					<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $this->calendar->calendar_id; ?>&daytime=<?php echo $item->daytime_day; ?>">
						<?php echo date('d-m-Y', strtotime($item->daytime_day)); ?>
					</a>
				</td>				
				<td class="center">
					<?php 
						echo count($item->campersCount);
					?>
				</td>
				<td class="center">
					<?php 
						$totalquota=0;
						foreach ($item->totalDaytimes as $daytime){
							$totalquota+=$daytime->quota;
						}
						echo '<p'; echo $item->filledQuota==$totalquota ? 'style=font-weight:bold;background-color:#11aa00;padding:10px;>':'>'; echo $item->filledQuota.' / '.$totalquota.'</p>';
					?>
				</td>
				<td class="center">
					<a class="btn" onClick="javascript:return confirm('Supprimera également toutes les inscriptions associées à cette date. Êtes-vous sûr?')" href="index.php?option=com_estivole&task=calendar.deleteListDaytime&daytime_id=<?php echo $item->daytime_id; ?>">
						<i class="icon-trash"></i>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addDayTimeModal(null, '<?php echo $this->calendar->calendar_id; ?>', null);"><?php echo JText::_('Ajouter une date'); ?></a>
</div>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'calendar'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_adddaytime.php'); ?>