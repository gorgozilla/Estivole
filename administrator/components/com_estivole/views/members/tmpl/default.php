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
$this->sortColumn	= $this->escape($this->state->get('list.ordering'));
$this->sortDirection	= $this->escape($this->state->get('list.direction'));

//Get tshirt-size options
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$membersOptions = JFormHelper::loadFieldType('Members', false);
$tshirtOptions=$membersOptions->getOptionsTshirtSize(); // works only if you set your field getOptions on public!!

//Get camping options
$campingOptions=$membersOptions->getOptionsCamping(); // works only if you set your field getOptions on public!!

//Get member status options
$memberStatusOptions=$membersOptions->getOptionsMemberStatus(); // works only if you set your field getOptions on public!!

//Get validation status options
$validationStatusOptions=$membersOptions->getOptionsValidationStatus(); // works only if you set your field getOptions on public!!

//Get services options
$services = JFormHelper::loadFieldType('Services', false);
$servicesOptions=$services->getOptions(); // works only if you set your field getOptions on public!!

//Get calendars options
$calendars = JFormHelper::loadFieldType('Calendars', false);
$calendarsOptions=$calendars->getOptions(); // works only if you set your field getOptions on public!!

$subscriptionsMembersCounter=0;
?>
<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task )
{
	var form = document.adminForm;
	form.filter_order.value = order;
	form.filter_order_Dir.value = dir;
	document.adminForm.submit( task );
}
</script>

<script type="text/javascript" language="javascript">
	jQuery(document).ready(function() {
		jQuery("#addDayTimeForm #jformcalendar_id, #addDayTimeForm #jformdaytime, #addDayTimeForm #jformservice_id").change(function() {
			var daytime = jQuery("#addDayTimeForm #jformdaytime").val();
			var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
			var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
			getCalendarDaytimes(calendar_id, daytime, service_id);
		});
		
		jQuery("#addDayTimeForm #jformcalendar_id, #addDayTimeForm #jformservice_id").change(function() {
			var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
			var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
			getDaytimesByService(calendar_id, service_id);
		});
	});
</script>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=members');?>" method="post" name="adminForm" id="adminForm">
		<div id="j-main-container">
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible">Rechercher dans le titre</label>
					<input type="text" name="filter_search" id="filter_search" placeholder="Rechercher" value="<?php echo $this->escape($this->searchterms); ?>" class="hasTooltip" title="Rechercher dans le titre" />
				</div>
				<div class="btn-group pull-left">
					<button type="submit" class="btn hasTooltip" title="Rechercher"><i class="icon-search"></i></button>
					<button type="button" class="btn hasTooltip" title="Effacer" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<select name="filter_validationStatus" class="inputbox" onchange="this.form.submit()">
						<option value="1000"> - Status validation - </option>
						<?php echo JHtml::_('select.options', $validationStatusOptions, 'value', 'text', $this->state->get('filter.validationStatus'));?>
					</select>
					<select name="filter_memberStatus" class="inputbox" onchange="this.form.submit()">
						<option value="1000"> - Status - </option>
						<?php echo JHtml::_('select.options', $memberStatusOptions, 'value', 'text', $this->filterMemberStatus);?>
					</select>
					<select name="filter_calendar_id" class="inputbox" onchange="this.form.submit()">
						<option value="1000"> - Calendrier - </option>
						<?php echo JHtml::_('select.options', $calendarsOptions, 'value', 'text', $this->filterCalendarId);?>
					</select>
					<select name="filter_services_members" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Secteur - </option>
					<?php echo JHtml::_('select.options', $servicesOptions, 'value', 'text', $this->state->get('filter.services_members'));?>
					</select>
					<br />
					<select name="filter_tshirtsize" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Taille t-shirt - </option>
						<?php echo JHtml::_('select.options', $tshirtOptions, 'value', 'text', $this->state->get('filter.tshirt_size'));?>
					</select>
					<select name="filter_campingPlace" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Camping - </option>
						<?php echo JHtml::_('select.options', $campingOptions, 'value', 'text', $this->state->get('filter.campingPlace'));?>
					</select>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<h3>Total t-shirts : <?php echo ($this->totalPolosF + $this->totalPolosM + $this->totalShirtsF + $this->totalShirtsM); ?></h3>
			<h4>Total t-shirts terrain masculins : <?php echo $this->totalShirtsM!=null ? $this->totalShirtsM  : '0'; ?></h4>
			<h4>Total t-shirts terrain féminins : <?php echo $this->totalShirtsF!=null ? $this->totalShirtsF  : '0'; ?></h4>
			<h4>Total t-shirts loges + Club91 masculins : <?php echo $this->totalPolosM!=null ? $this->totalPolosM  : '0'; ?></h4>
			<h4>Total t-shirts loges + Club91 féminins : <?php echo $this->totalPolosF!=null ? $this->totalPolosF  : '0'; ?></h4>
			<a href="index.php?option=com_estivole&task=members.exportMembersShirts&service_id=<?php echo $this->services_members; ?>" class="btn btn-large btn-success" role="button">
				<?php echo JText::_('Exporter les membres + t-shirts'); ?>
			</a>
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th>#</th>
						<th class="left">
							<?php echo JHTML::_( 'grid.sort', 'Nom', 'u.name', $this->sortDirection, $this->sortColumn); ?>
						</th>
						<th class="left">
							<?php echo JHTML::_( 'grid.sort', 'Email', 'u.email', $this->sortDirection, $this->sortColumn); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Tél.'); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Date de naissance'); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Ville'); ?>
						</th>
						<th class="left">
							<?php echo JText::_( 'T-Shirt size' ); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Camping ?'); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Status validation'); ?>
						</th>
						<th class="center">
							<?php echo JText::_('Actions'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$itemNumber = $this->limitstart;
				foreach ($this->members as $i => $item){
					$userId = $item->user_id; 
					$user = JFactory::getUser($userId);
					$userProfile = JUserHelper::getProfile( $userId );
					$userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $userId );
					$itemNumber++;

					if(empty($this->validationStatus) || $this->validationStatus=='1000' || ($this->validationStatus=='N' && $item->hasNonValidatedDaytimes) || ($this->validationStatus=='Y' && !$item->hasNonValidatedDaytimes))
					{
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->member_id); ?>
						</td>
						<td><?php echo $itemNumber; ?></td>
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=member.edit&member_id='.(int) $item->member_id); ?>">
								<?php echo JText::_($user->name); ?>
							</a>
						</td>
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=member.edit&member_id='.(int) $item->member_id); ?>">
							<?php echo JText::_($item->email); ?>
							</a>
						</td>
						<td class="left">
							<?php echo JText::_($userProfile->profile['phone']); ?>
						</td>
						<td class="left">
							<?php 
							$birthDate = new DateTime($userProfile->profile['dob']); 
							echo $birthDate->format('d-m-Y'); 
							?>
						</td>
						<td class="left">
							<?php echo JText::_($userProfile->profile['postal_code']." / ".$userProfile->profile['city']); ?>
						</td>
						<td class="left">
							<?php echo JText::_($userProfilEstivole->profilestivole['tshirtsize']); ?>
						</td>
						<td class="center">
							<?php if($userProfilEstivole->profilestivole['campingPlace']){ echo '<i class="icon-ok"></i>'; } ?>
						</td>
						<td class="center">
							<?php if(!$item->hasNonValidatedDaytimes){ echo '<i class="icon-ok" title="Toutes les tranches horaires sont validées"></i>'; }else{ echo '<i class="icon-clock" title="Tranches horaires en attente de validation"></i>'; } ?>
						</td>
						<td class="center">
							<!--<a class="btn" onclick="composeEmail('<?php echo $item->member_id; ?>')">
								<i class="icon-mail"></i>
							</a>-->
							<button class="btn" onClick="toggleCalendarDaytime(<?php echo $subscriptionsMembersCounter; ?>);" title="Voir les tranches horaires du bénévole">
								<i class="icon-search"></i>
							</button>
							<a class="btn" href="javascript:void(0);" onclick="addAvailibilityModal('<?php echo $item->member_id; ?>');" title="Assigner le bénévole à une tranche horaire">
								<i class="icon-clock"></i>
							</a>
							<?php echo JHtml::_('job.deleteListMember', $item->member_id, $i); ?>
						</td>
					</tr>
					<?php foreach ($item->member_daytimes as $x => $member_daytime) : 
					
						$userId = $member_daytime->user_id; 
						$user = JFactory::getUser($userId);
						$userProfile = JUserHelper::getProfile( $userId );
						$userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $userId );
						
					?>
					<tr class="subrow subrow-<?php echo $subscriptionsMembersCounter; ?>" style="display:none;">
						<td class="left">
							<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $calendar->calendar_id; ?>&daytime=<?php echo $member_daytime->daytime_day; ?>">
								<?php echo date('d-m-Y', strtotime($member_daytime->daytime_day)); ?>
							</a>
						</td>
						<td class="left">
							<a href="index.php?option=com_estivole&view=service&layout=edit&service_id=<?php echo $member_daytime->service_id; ?>">
								<?php echo JText::_($member_daytime->service_name); ?>
							</a>
						</td>
						<td class="left">
							<?php echo JText::_($member_daytime->description); ?>
						</td>
						<td class="left">
							<a href="index.php?option=com_estivole&view=daytime&layout=edit&calendar_id=<?php echo $calendar->calendar_id; ?>&daytime=<?php echo $member_daytime->daytime_day; ?>">
								<?php echo date('H:i', strtotime($member_daytime->daytime_hour_start)).' - '.date('H:i', strtotime($member_daytime->daytime_hour_end));  ?>
							</a>
						</td>
						<td class="center">
							<?php if($member_daytime->status_id==0){ ?>
								<a href="index.php?option=com_estivole&controller=daytime&task=daytime.changeStatusDaytime&member_daytime_id=<?php echo $member_daytime->member_daytime_id; ?>&status_id=1" title="Confirmer la disponibilité">
									<span class="badge-warning"><i class="icon-time"></i></span>
								</a>
							<?php }else{ ?>
								<a href="index.php?option=com_estivole&controller=daytime&task=daytime.changeStatusDaytime&member_daytime_id=<?php echo $member_daytime->member_daytime_id; ?>&status_id=0" title="Remttre le status en attente de validation">
									<span class="badge-success"><i class="icon-ok"></i></span>
								</a>
							<?php } ?>
						</td>
						<td class="center" colspan=6>
							<a class="btn" href="index.php?option=com_estivole&controller=member&task=member.deleteAvailibility&member_daytime_id=<?php echo $member_daytime->member_daytime_id; ?>">
								<i class="icon-trash"></i>
							</a>
						</td>
					</tr>
					<?php endforeach;
					$subscriptionsMembersCounter++;
					}
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="pagination">
				<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			</div>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

<?php include_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'member'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'_addavailibility.php'); ?>