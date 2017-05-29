<?php
/*
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');
jimport( 'joomla.application.module.helper' );
//If user not logged in, then display login form
if($this->user->guest){
	$app =& JFactory::getApplication();
	$app->redirect('index.php?option=com_users&view=login&Itemid=104');
}else{
	// Else display member edit form ?>
	<h1>Espace benevole > Mon calendrier</h1>
	
	<p>C'est ici que vous réservez votre place pour devenir bénévole. Pour se faire, c'est très simple : </p>
	
	<ul>
	<li>Cliquez sur le bouton "M'inscrire en tant que bénévole" ci-dessous.</li>
	<li>Sélectionnez le secteur et la date pour laquelle vous souhaitez participer.</li>
	<li>La liste des tranches horaires s'affiche, sélectionnez celle de votre choix en fonction de votre disponibilité et de la tâche vous arrangeant.</li>
	</ul>
	
	<p>Vous pouvez bien sûr ajouter autant de disponibilités que vous le souhaitez. Pour chaque disponibilité, une confirmation vous sera envoyée par email.</p>
	
		<p><strong>Important :<br />
		Pour les bénévoles travaillant le 1er août, soit durant la soirée gratuite, un billet vous sera offert l'année prochaine pour la soirée de votre choix!<br />
		De plus, uniquement les tentes seront acceptées à l'intérieur du camping bénévoles, les minibus, camping-car et tout autre type de véhicule ne seront pas admis!</strong></p>
	
	<h2>Calendrier "<?php echo $this->calendars[0]->name; ?>"</h2>
	
	<?php if(count($this->calendars[0]->member_daytimes)<2 && count($this->calendars[0]->member_daytimes)>0){ ?>
		<div class="alert alert-danger">
			<p><strong>Vous n'avez pas un minimum de 2 tranches horaires sur toute la durée du fesival, votre inscription n'est pour le moment pas prise en compte et ne sera pas validée.</strong></p>
		</div>
	<?php } ?>
	
	<table class="table" id="memberDaytimesTable">
		<thead>
		<tr>
			<th>Date</th>
			<th>Secteur</th>
			<th>Tâche</th>
			<th>Horaire</th>
			<th class="center">
				<?php echo JText::_('Actions'); ?>
			</th>
		</tr>
		</thead>
		<?php if(count($this->calendars[0]->member_daytimes)<1){ ?>
			<tr>
				<td colspan="5">
					<p>Pas d'attribution à ce calendrier.</p>
				</td>
			</tr>
		</table>
		<?php }else{ 
			foreach($this->calendars[0]->member_daytimes as $daytime) : ?>
			<tr>
				<td>
					<?php if($daytime->status_id==0){ ?>
						<a title="Date en attente de confirmation" style="background-color:#ff8800;cursor:help;">&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php }else{ ?>
						<a title="Date confirmée" style="background-color:#008800;cursor:help;">&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php } ?>
					
					<?php echo date('d-m-Y',strtotime($daytime->daytime_day)); ?>
				</td>
				<td><?php echo $daytime->service_name; ?></td>
				<td><?php echo $daytime->description; ?></td>
				<td><?php echo date('H:i', strtotime($daytime->daytime_hour_start)).' - '.date('H:i', strtotime($daytime->daytime_hour_end));  ?></td>
				<td class="center">
					<?php if($daytime->status_id==0){ ?>
						<a title="Supprimer la disponibilité. Une fois validée, votre inscription ne pourra plus être supprimée." href="index.php?option=com_estivole&controller=member&task=member.deleteAvailibility&member_daytime_id=<?php echo $daytime->member_daytime_id; ?>" class="btn btn-default">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					<?php } ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
		
		<p>
			<i>Légende : </i><br />
			<a title="Date en attente de confirmation" style="background-color:#ff8800;cursor:help;">&nbsp;&nbsp;&nbsp;&nbsp;</a> Date réservée en attente de validation<br />
			<a title="Date confirmée" style="background-color:#008800;cursor:help;">&nbsp;&nbsp;&nbsp;&nbsp;</a> Date confirmée
		</p>
		<?php } ?>
	
	<br />
	<a href="index.php?option=com_estivole&view=member&layout=_addavailibility&tmpl=component&calendar_id=<?php echo $this->calendars[0]->calendar_id; ?>" class="modal btn btn-warning" rel="{size: {y: 650}, onClose:function(){var js = window.location.reload();}, handler:'iframe'}">
        M'inscrire en tant que bénévole
	</a>
<?php	} ?>