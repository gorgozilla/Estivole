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

$this->sortColumn	= $this->escape($this->state->get('list.ordering'));
$this->sortDirection	= $this->escape($this->state->get('list.direction'));

//Get services options
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$services = JFormHelper::loadFieldType('Services', false);
$servicesOptions=$services->getOptions(); // works only if you set your field getOptions on public!!

$dates = JFormHelper::loadFieldType('Dates', false);
$datesOptions=$dates->getOptions(); // works only if you set your field getOptions on public!!

//Get calendars options
$calendars = JFormHelper::loadFieldType('Calendars', false);
$calendarsOptions=$calendars->getOptions(); // works only if you set your field getOptions on public!!
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
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=daytimes');?>" method="post" name="adminForm" id="adminForm">
		<div id="j-main-container">
			<?php
			// Search tools bar
			//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
			?>
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
					<select name="filter_calendar_id" class="inputbox" onchange="this.form.submit()">
						<option value="1000"> - Calendrier - </option>
						<?php echo JHtml::_('select.options', $calendarsOptions, 'value', 'text',  $this->filterCalendarId);?>
					</select>
					<select name="filter_services" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Select secteur - </option>
						<?php echo JHtml::_('select.options', $servicesOptions, 'value', 'text', $this->state->get('filter.services'));?>
					</select>
					<select name="filter_dates" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Select date - </option>
						<?php echo JHtml::_('select.options', $datesOptions, 'value', 'text', $this->state->get('filter.dates'));?>
					</select>
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<table class="table table-striped">
			<thead>
				<tr>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Nom', 'u.name', $this->sortDirection, $this->sortColumn); ?>
					</th>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Email', 'u.email', $this->sortDirection, $this->sortColumn); ?>
					</th>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Secteur', 's.service_name', $this->sortDirection, $this->sortColumn); ?>
					</th>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Date', 'd.daytime_day', $this->sortDirection, $this->sortColumn); ?>
					</th>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Horaire', 'd.daytime_hour_start', $this->sortDirection, $this->sortColumn); ?>					
					</th>
					<th class="left">
						<?php echo 'Dors au camping?'; ?>					
					</th>
					<th class="left">
						<?php echo JHTML::_( 'grid.sort', 'Status', 'md.status_id', $this->sortDirection, $this->sortColumn); ?>
					</th>
					<th class="center">
						<?php echo JText::_('Actions'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->member_daytimes as $i => $item){
					$userId = $item->user_id; 
					$user = JFactory::getUser($userId);
					$userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $userId );
			?>
				<tr class="row<?php echo $i % 2; ?>">
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
						<?php echo JText::_($item->service_name.' - '.$item->description); ?>
					</td>
					<td class="left">
						<?php echo date('d-m-Y',strtotime($item->daytime_day)); ?>
					</td>
					<td class="left">
						<?php echo date('H:i', strtotime($item->daytime_hour_start)).' - '.date('H:i', strtotime($item->daytime_hour_end)); ?>
					</td>
					<td class="center">
						<?php if($userProfilEstivole->profilestivole['campingPlace']){ echo '<i class="icon-ok"></i>'; } ?>
					</td>
					<td class="left">
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
			<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			</table>
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