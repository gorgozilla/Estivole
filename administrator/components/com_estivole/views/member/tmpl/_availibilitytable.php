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
	<table id="availibilityTable" class="table">
		<thead>
			<tr>
				<th class="center" colspan="4">
					<h4><?php echo date('d-m-Y',strtotime($this->daytimes[0]->daytime_day)); ?></h4>
				</th>
			</tr>
			<tr>
				<th class="left">
					<?php echo JText::_('Heure début'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Heure fin'); ?>
				</th>
				<th class="left">
					<?php echo JText::_('Quota'); ?>
				</th>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->daytimes as $i => $item) :
			// $canEdit   = $this->canDo->get('core.edit');
			// $canChange = $loggeduser->authorise('core.edit.state',	'com_users');

			// // If this group is super admin and this user is not super admin, $canEdit is false
			// if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
			// {
				// $canEdit   = false;
				// $canChange = false;
			// }
		?>
			<tr <?php if ($item->isAvailable!=null && $item->status_id==0){ ?>style="background-color:#F89406;"<?php }elseif($item->isAvailable!=null && $item->status_id==1){ ?>style="background-color:#00ff00;"<?php }elseif($item->isComplete){ ?>style="background-color:#ff0000;"<?php } ?>>
				<td>
					<?php if ($item->isAvailable!=null){ ?>
						<?php if($item->status_id==0){ ?>
							<i class="icon-time"></i>
						<?php }else{ ?>
							<i class="icon-ok"></i>
						<?php } ?>
					<?php }elseif($item->isComplete){ ?>
						<i class="icon-remove"></i>
					<?php } ?>
					<?php echo date('H:i', strtotime($item->daytime_hour_start)); ?>
				</td>
				<td>
					<?php echo date('H:i', strtotime($item->daytime_hour_end)); ?>
				</td>
				<td>
					<?php echo $item->filledQuota !='' ? $item->filledQuota : '0'; echo ' / '.JText::_($item->quota); ?>
				</td>
				<td>
					<?php if ($item->isAvailable==null && $item->isComplete!=true) : ?>
						<?php echo JHtml::_('grid.id', $i, $item->daytime_id); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>