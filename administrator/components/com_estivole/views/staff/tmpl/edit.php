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
//Get services options
$services = JFormHelper::loadFieldType('Services', false);
$servicesOptions=$services->getOptions(); // works only if you set your field getOptions on public!!

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
		if (task == 'staff.cancel' || document.formvalidator.isValid(document.id('member-form'))) {
			<?php //echo $this->form->getField('summary')->save(); ?>
			Joomla.submitform(task, document.getElementById('member-form'));
		}
	}
</script>

<div id="j-main-container" class="span12">
	<?php if($this->user!=null){ ?>
		<h1>Staff "<?php echo $this->user->name; ?>"</h1>
	<?php }else{ ?>
		<h1>Nouveau staff</h1>
	<?php } ?>
	<form action="<?php echo JRoute::_('index.php?option=com_estivole&view=staff&layout=edit&member_id=' . (int) $this->member->member_id);?>" method="post" name="adminForm" id="member-form" class="form-validate">
		<div class="form-inline form-inline-header">
			<input type="hidden" class="form-control" name="jform[name]" placeholder="Username" value="<?php echo $this->user->name; ?>" />
			<input type="text" class="form-control required" name="jform[email]" placeholder="Email" value="<?php echo $this->user->email; ?>" />
			<input type="text" class="form-control required" name="jform[profilestivole][firstname]" placeholder="Prénom" value="<?php echo $this->userProfilEstivole->profilestivole['firstname']; ?>" />
			<input type="text" class="form-control required" name="jform[profilestivole][lastname]" placeholder="Nom" value="<?php echo $this->userProfilEstivole->profilestivole['lastname']; ?>" />
			<input type="date" class="form-control" name="jform[profile][birthdate]" placeholder="Date de naissance" value="<?php echo $this->userProfile->profile['dob']; ?>" />
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
			<select name="jform[profilestivole][service_id]" class="inputbox">
				<option value=""> - Secteur - </option>
			<?php echo JHtml::_('select.options', $servicesOptions, 'value', 'text', $this->userProfilEstivole->profilestivole['service_id']);?>
			</select>
			<br />
			<br />
			<label>Dors au camping ?</label><br />
			<input type="checkbox" name="jform[profilestivole][campingPlace]" value=1 <?php if($this->userProfilEstivole->profilestivole['campingPlace']=='1'){ ?> checked=checked <?php } ?> />
			<input type="hidden" name="task" value="" />
			<input type="hidden" class="form-control" name="jform[username]" value="<?php if($this->member->member_id==0){ echo generateRandomString(); }else{ echo $this->user->username; } ?>" />
			<input type="hidden" name="jform[member_id]" value="<?php echo $this->member->member_id; ?>" />
			<input type="hidden" name="jform[member_type_id]" value="2" />
			<input type="hidden" name="jform[user_id]" value="<?php echo $this->user->id; ?>" />
			<input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>