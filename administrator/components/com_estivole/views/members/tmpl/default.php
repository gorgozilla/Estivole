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
					<select name="filter_tshirtsize" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Select tshirt-size - </option>
						<?php echo JHtml::_('select.options', $tshirtOptions, 'value', 'text', $this->state->get('filter.tshirt_size'));?>
					</select>
					<select name="filter_campingPlace" class="inputbox" onchange="this.form.submit()">
						<option value=""> - Camping - </option>
						<?php echo JHtml::_('select.options', $campingOptions, 'value', 'text', $this->state->get('filter.campingPlace'));?>
					</select>
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<h3>Total t-shirts : <?php echo ($this->totalPolosF + $this->totalPolosM + $this->totalShirtsF + $this->totalShirtsM); ?></h3>
			<h4>Total t-shirts terrain masculins : <?php echo $this->totalShirtsM!=null ? $this->totalShirtsM  : '0'; ?></h4>
			<h4>Total t-shirts terrain féminins : <?php echo $this->totalShirtsF!=null ? $this->totalShirtsF  : '0'; ?></h4>
			<h4>Total t-shirts loges masculins : <?php echo $this->totalPolosM!=null ? $this->totalPolosM  : '0'; ?></h4>
			<h4>Total t-shirts loges féminins : <?php echo $this->totalPolosF!=null ? $this->totalPolosF  : '0'; ?></h4>
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
							<?php echo JText::_('Adresse'); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Ville'); ?>
						</th>
						<th class="left">
							<?php echo JText::_( 'T-Shirt size' ); ?>
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
							<?php echo JText::_($userProfile->profile['address1']); ?>
						</td>
						<td class="left">
							<?php echo JText::_($userProfile->profile['postal_code']." / ".$userProfile->profile['city']); ?>
						</td>
						<td class="left">
							<?php echo JText::_($userProfilEstivole->profilestivole['tshirtsize']); ?>
						</td>
						<td class="center">
							<!--<a class="btn" onclick="composeEmail('<?php echo $this->member->member_id; ?>')">
								<i class="icon-mail"></i>
							</a>-->
							<?php echo JHtml::_('job.deleteListMember', $item->member_id, $i); ?>
						</td>
					</tr>
				<?php 
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