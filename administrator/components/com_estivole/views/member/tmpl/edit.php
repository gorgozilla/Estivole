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

//Get tshirt-size options
JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
$membersOptions = JFormHelper::loadFieldType('Members', false);
$tshirtOptions=$membersOptions->getOptionsTshirtSize(); // works only if you set your field getOptions on public!!
$sexOptions=$membersOptions->getOptionsSex(); // works only if you set your field getOptions on public!!

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

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
	<?php if($this->user!=null){ ?>
		<h1>Membre "<?php echo $this->user->name; ?>"</h1>
	<?php }else{ ?>
		<h1>Nouveau membre</h1>
	<?php } ?>
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=member&layout=edit&member_id=' . (int) $this->member->member_id);?>" method="post" name="adminForm" id="member-form" class="form-validate">
		<div class="form-inline form-inline-header">
			<input type="hidden" class="form-control" name="jform[name]" placeholder="Username" value="<?php echo $this->user->name; ?>" />
			<input type="text" class="form-control required" name="jform[email]" placeholder="Email" value="<?php echo $this->user->email; ?>" />
			<input type="text" class="form-control required" name="jform[profilestivole][firstname]" placeholder="Prénom" value="<?php echo $this->userProfilEstivole->profilestivole['firstname']; ?>" />
			<input type="text" class="form-control required" name="jform[profilestivole][lastname]" placeholder="Nom" value="<?php echo $this->userProfilEstivole->profilestivole['lastname']; ?>" />
			<input type="text" class="form-control" name="jform[profile][birthdate]" placeholder="Ex. : 23-08-1984" value="<?php echo $this->userProfile->profile['dob']!='' ? date("d-m-Y", strtotime($this->userProfile->profile['dob'])) : ''; ?>" />
			<input type="text" class="form-control" name="jform[profile][phone]" placeholder="Téléphone" value="<?php echo JText::_($this->userProfile->profile['phone']); ?>" />
			<input type="text" class="form-control" name="jform[profile][address1]" placeholder="Adresse" value="<?php echo $this->userProfile->profile['address1']; ?>" />
			<input type="text" class="form-control" name="jform[profile][zipcode]" placeholder="NPA" value="<?php echo $this->userProfile->profile['postal_code']; ?>" />
			<input type="text" class="form-control" name="jform[profile][city]" placeholder="Ville" value="<?php echo $this->userProfile->profile['city']; ?>" />
			<br />
			<br />
			<select name="jform[profilestivole][tshirtsize]" class="inputbox">
				<option value=""> - Select tshirt-size - </option>
				<?php echo JHtml::_('select.options', $tshirtOptions, 'value', 'text',  $this->userProfilEstivole->profilestivole['tshirtsize']);?>
			</select>
			<select name="jform[profilestivole][sex]" class="inputbox">
				<option value=""> - Select sex - </option>
				<?php echo JHtml::_('select.options', $sexOptions, 'value', 'text',  $this->userProfilEstivole->profilestivole['sex']);?>
			</select>
			<br />
			<br />
			<label>Dors au camping ?</label><br />
			<input type="checkbox" name="jform[profilestivole][campingPlace]" value=1 <?php if($this->userProfilEstivole->profilestivole['campingPlace']=='1'){ ?> checked=checked <?php } ?> />
			<input type="hidden" name="task" value="" />
			<input type="hidden" class="form-control" name="jform[username]" value="<?php if($this->member->member_id==0){ echo generateRandomString(); }else{ echo $this->user->username; } ?>" />
			<input type="hidden" name="jform[member_id]" value="<?php echo $this->member->member_id; ?>" />
			<input type="hidden" name="jform[member_type_id]" value="1" />
			<input type="hidden" name="jform[user_id]" value="<?php echo $this->user->id; ?>" />
			<input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
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
					<?php echo JText::_('Tâche'); ?>
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
							<?php echo JText::_($item->service_name); ?>
						</a>
					</td>
					<td class="left">
						<?php echo JText::_($item->description); ?>
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