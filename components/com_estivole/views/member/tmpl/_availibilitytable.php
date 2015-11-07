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
	<link rel="stylesheet" href="/estivale2015/media/jui/css/bootstrap.min.css" type="text/css" />
	<table id="availibilityTable" class="table">
		<thead>
			<tr>
				<th class="center" colspan="4">
					<h4>Faites votre choix dans la liste des tranches horaires ci-dessous pour le secteur et la date sélectionnée</h4>
				</th>
			</tr>
			<tr>
				<th>
					<?php echo JText::_('Tâche'); ?>
				</th>
				<th>
					<?php echo JText::_('Heure début'); ?>
				</th>
				<th>
					<?php echo JText::_('Heure fin'); ?>
				</th>
				<th>
					<?php echo JText::_('Quota'); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->daytimes as $item) : ?>
			<tr <?php if ($item->isAvailable!=null && $item->isAvailable->status_id==0){ ?>style="background-color:#F89406;"<?php }elseif($item->isAvailable!=null && $item->isAvailable->status_id==1){ ?>style="background-color:#00ff00;"<?php }elseif($item->isComplete){ ?>style="background-color:#ff0000;"<?php } ?>>
				<td>
					<?php if ($item->isAvailable!=null){ ?>
						<?php if($item->isAvailable->status_id==0){ ?>
							<i class="icon-time"></i>
						<?php }else{ ?>
							<i class="icon-ok"></i>
						<?php } ?>
					<?php }elseif($item->isComplete){ ?>
						<i class="icon-remove"></i>
					<?php } ?>
					<?php echo $item->description; ?>
				</td>
				<td>
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