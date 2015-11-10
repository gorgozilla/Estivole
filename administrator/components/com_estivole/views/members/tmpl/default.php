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
					<button type="button" class="btn hasTooltip" title="Effacer" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th class="left">
							<?php echo JHTML::_( 'grid.sort', 'Nom', 'b.lastname', $this->sortDirection, $this->sortColumn); ?>
						</th>
						<th class="left">
							<?php echo JHTML::_( 'grid.sort', 'Email', 'b.email', $this->sortDirection, $this->sortColumn); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Tél.'); ?>
						</th>
						<th class="left">
							<?php echo JText::_('Adresse'); ?>
						</th>
						<th class="left">
							<?php echo JHTML::_( 'grid.sort', 'Ville', 'b.city', $this->sortDirection, $this->sortColumn); ?>
						</th>
						<th class="center">
							<?php echo JText::_('Actions'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->members as $i => $item) :
						$userId = $item->user_id; 
						$userProfile = JUserHelper::getProfile( $userId );
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="left">
							<a href="<?php echo JRoute::_('index.php?option=com_estivole&task=member.edit&member_id='.(int) $item->member_id); ?>">
								<?php echo JText::_($this->user->name); ?>
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
							<?php echo JText::_($userProfile->profile['zipcode']." / ".$userProfile->profile['city']); ?>
						</td>
						<td class="center">
							<a class="btn" onClick="javascript:return confirm('Supprimera également toutes les inscriptions associées à ce membre. Êtes-vous sûr?')" href="index.php?option=com_estivole&controller=member&task=member.delete&member_id=<?php echo $item->member_id; ?>">
								<i class="icon-trash"></i>
							</a>
						</td>
					</tr>
					<?php endforeach; ?>
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