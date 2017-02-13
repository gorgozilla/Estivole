<?php
/*
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$this->sortColumn	= $this->escape($this->state->get('list.ordering'));
$this->sortDirection	= $this->escape($this->state->get('list.direction'));
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
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=staffs');?>" method="post" name="adminForm" id="adminForm">
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
				foreach ($this->staffs as $i => $item){
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
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=staff.edit&member_id='.(int) $item->member_id); ?>">
								<?php echo JText::_($user->name); ?>
							</a>
						</td>
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=staff.edit&member_id='.(int) $item->member_id); ?>">
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
		
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="model" value="staff" />
		<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>