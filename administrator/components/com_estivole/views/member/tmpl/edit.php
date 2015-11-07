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
		if (task == 'member.cancel' || document.formvalidator.isValid(document.id('member-form'))) {
			<?php //echo $this->form->getField('summary')->save(); ?>
			Joomla.submitform(task, document.getElementById('member-form'));
		}
	}
</script>

<div id="j-main-container" class="span12">
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=member&layout=edit&member_id=' . (int) $this->member->member_id);?>" method="post" name="adminForm" id="member-form" class="form-validate">
		<div class="form-inline form-inline-header">
			<?php echo $this->form->getControlGroup('firstname'); ?>
			<?php echo $this->form->getControlGroup('lastname'); ?>
			<?php echo $this->form->getControlGroup('email'); ?>
			<?php echo $this->form->getControlGroup('phone'); ?>
			<?php echo $this->form->getControlGroup('address'); ?>
			<?php echo $this->form->getControlGroup('npa'); ?>
			<?php echo $this->form->getControlGroup('city'); ?>
			<?php echo $this->form->getControlGroup('tshirtsize'); ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="jform[member_id]" value="<?php echo $this->member->member_id; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
	
<?php if($this->member->member_id!=null){ ?>
	<h2>Assignation aux calendriers</h2>
	<?php
	foreach($this->calendars as $calendar){
		echo '<h3>Calendrier "'.$calendar->name.'"</h3>';
	?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="left">
					<?php echo JText::_('Date'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Secteur'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Tranche horaire'); ?>
				</th>
				<th class="center">
					<?php echo JText::_('Status'); ?>
				</th>
				<th class="center">
					<?php echo JText::_('Actions'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		
		<?php
		if(count($calendar->member_daytimes)>0){
			foreach ($calendar->member_daytimes as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="left">
						<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $calendar->calendar_id; ?>&daytime=<?php echo $item->daytime_day; ?>">
							<?php echo date('d-m-Y', strtotime($item->daytime_day)); ?>
						</a>
					</td>
					<td class="left">
						<a href="index.php?option=com_estivole&view=service&layout=edit&service_id=<?php echo $item->service_id; ?>">
							<?php echo JText::_($item->name); ?>
						</a>
					</td>
					<td class="left">
						<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $calendar->calendar_id; ?>&daytime=<?php echo $item->daytime_day; ?>">
							<?php echo date('H:i', strtotime($item->daytime_hour_start)).' - '.date('H:i', strtotime($item->daytime_hour_end));  ?>
						</a>
					</td>
					<td class="center">
						<?php if($item->status_id==0){ ?>
							<a href="index.php?option=com_estivole&controller=daytime&task=daytime.changeStatusDaytime&member_daytime_id=<?php echo $item->member_daytime_id; ?>&status_id=1" title="Confirmer la disponibilité">
								<span class="badge-warning"><i class="icon-time"></i></span>
							</a>
						<?php }else{ ?>
							<a href="index.php?option=com_estivole&controller=daytime&task=daytime.changeStatusDaytime&member_daytime_id=<?php echo $item->member_daytime_id; ?>&status_id=0" title="Remttre le status en attente de validation">
								<span class="badge-success"><i class="icon-ok"></i></span>
							</a>
						<?php } ?>
					</td>
					<td class="center">
						<a class="btn" href="index.php?option=com_estivole&controller=member&task=member.deleteAvailibility&member_daytime_id=<?php echo $item->member_daytime_id; ?>">
							<i class="icon-trash"></i>
						</a>
					</td>
				</tr>
				<?php endforeach;
		}else{ ?>
				<tr>
					<td class="left" colspan="5">
						<p>Pas de tranche horaire définie pour le moment.</p>
					</td>
				</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php
	}
	?>
	<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addAvailibilityModal('<?php echo $this->member->member_id; ?>')"><?php echo JText::_('Assigner à un poste'); ?></a>
</div>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'member'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_addavailibility.php'); ?>
<?php } ?>