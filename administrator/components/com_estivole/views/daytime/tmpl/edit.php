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

//Get services options
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$services = JFormHelper::loadFieldType('Services', false);
$servicesOptions=$services->getOptions(); // works only if you set your field getOptions on public!!

$subscriptionsMembersCounter=0;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'daytime.cancel' || document.formvalidator.isValid(document.id('daytime-form'))) {
			Joomla.submitform(task, document.getElementById('daytime-form'));
		}
	}
</script>
<style>
	.subrow td{
		padding:10px 50px !important;
	}
</style>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
	<h1>Calendriers > <?php echo $this->calendar->name; ?> > <?php echo date('d-m-Y', strtotime($this->daytime)); ?></h1>
	<h2>Tranches horaire de la date</h2>
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=daytime&layout=edit');?>" method="post" name="adminForm" id="daytime-form" class="form-validate">
		<input type="hidden" name="calendar_id" value="<?php echo $this->calendar->calendar_id; ?>" />
		<input type="hidden" name="daytime" value="<?php echo $this->daytime; ?>" />
		<input type="hidden" name="task" value="" />
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-right hidden-phone">
				<select name="filter_servicesdaytime" class="inputbox" onchange="this.form.submit()">
					<option value=""> - Secteur - </option>
					<?php echo JHtml::_('select.options', $servicesOptions, 'value', 'text', $this->state->get('filter.services_daytime'));?>
				</select>
			</div>
		</div>
	</form>
		<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addDayTimeModal(null, <?php echo $this->daytimes[0]->calendar_id; ?>,'<?php echo $this->daytimes[0]->daytime_day; ?>');">
			<?php echo JText::_('Ajouter une tranche horaire'); ?>
		</a>
		<a href="index.php?option=com_estivole&task=daytime.exportDaytime&daytime=<?php echo $this->daytimes[0]->daytime_day; ?>&daytime_id=<?php echo $this->daytimes[0]->daytime_id; ?>" class="btn btn-large btn-success" role="button">
			<?php echo JText::_('Exporter les tranches horaire'); ?>
		</a>
	<div id="j-main-container">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="left">
						<?php echo JText::_('Jour'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Secteur'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Tâche'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Heure début'); ?>
					</th>
					<th class="left">
						<?php echo JText::_('Heure fin'); ?>
					</th>
					<th class="left" colspan="2">
						<?php echo JText::_('Quota'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->daytimes as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="left">
						<a href="#" onclick="addDayTimeModal(<?php echo $item->daytime_id; ?>,<?php echo $item->calendar_id; ?>,'<?php echo $item->daytime_day; ?>');"><?php echo date('d-m-Y',strtotime($item->daytime_day)); ?></a>
					</td>
					<td class="left">
						<?php echo $item->service_name;  ?>
					</td>
					<td class="left">
						<?php echo $item->description; ?>
					</td>
					<td class="left">
						<?php echo date('H:i', strtotime($item->daytime_hour_start));  ?>
					</td>
					<td class="left">
						<?php echo date('H:i', strtotime($item->daytime_hour_end));  ?>
					</td>
					<td class="left">
						<?php echo $item->filledQuota !='' ? $item->filledQuota : '0'; echo ' / '.JText::_($item->quota); ?>
					</td>
					<td class="center">
						<button class="btn" onClick="toggleCalendarDaytime(<?php echo $subscriptionsMembersCounter; ?>);" title="Voir les bénévoles inscrits">
							<i class="icon-search"></i>
						</button>
						<a class="btn" href="javascript:void(0);" onclick="assignAvailibilityModal('<?php echo $item->service_id.'\', \''.$item->daytime_id; ?>');" title="Assigner la tranche horaire à un bénévole">
							<i class="icon-user"></i>
						</a>
						<a class="btn" onClick="javascript:return confirm('Supprimera également toutes les inscriptions associées à cette tranche horaire. Êtes-vous sûr?')" href="index.php?option=com_estivole&task=daytime.deleteListDaytime&daytime_id=<?php echo $item->daytime_id; ?>">
							<i class="icon-trash"></i>
						</a>
					</td>
				</tr>
				
				<?php foreach ($item->subscriptionsMembers as $i => $subscr) : 
				
					$userId = $subscr->user_id; 
					$user = JFactory::getUser($userId);
					$userProfile = JUserHelper::getProfile( $userId );
					$userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $userId );
					
				?>
					<tr class="subrow subrow-<?php echo $subscriptionsMembersCounter; ?>" style="display:none;">
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=member.edit&member_id='.(int) $subscr->member_id); ?>">
								<?php echo JText::_($user->name); ?>
							</a>
						</td>
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=member.edit&member_id='.(int) $subscr->member_id); ?>">
							<?php echo JText::_($subscr->email); ?>
							</a>
						</td>
						<td class="left">
							<?php 
							$birthDate = new DateTime($userProfile->profile['dob']); 
							echo $birthDate->format('d-m-Y'); 
							?>
						</td>
						<td class="left">

						</td>
						<td class="left">

						</td>
						<td class="left">

						</td>
						<td class="center">

						</td>
					</tr>
					<?php endforeach;
					$subscriptionsMembersCounter++;
					?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<a href="javascript:void(0);" class="btn btn-large btn-success" role="button" onclick="addDayTimeModal(null, <?php echo $item->calendar_id; ?>,'<?php echo $item->daytime_day; ?>');">
			<?php echo JText::_('Ajouter une tranche horaire'); ?>
		</a>
		<a href="index.php?option=com_estivole&task=daytime.exportDaytime&daytime=<?php echo $item->daytime_day; ?>&daytime_id=<?php echo $item->daytime_id; ?>" class="btn btn-large btn-success" role="button">
			<?php echo JText::_('Exporter les tranches horaire'); ?>
		</a>
	</div>
</div>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'daytime'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_assignavailibility.php'); ?>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'daytime'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_addtime.php'); ?>
<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'daytime'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_export.php'); ?>