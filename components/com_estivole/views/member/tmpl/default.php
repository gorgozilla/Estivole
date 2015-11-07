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


<?php 
//If user not logged in, then display login form
if($this->user->guest){
	$position = 'login';
	$modules =& JModuleHelper::getModules($position); 
	foreach ($modules as $module) { 
		echo JModuleHelper::renderModule($module); 
	}
// Else display member edit form 
}else{
?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script src="http://jqueryui.com/resources/demos/datepicker/datepicker-fr.js"></script>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function() {
			jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "fr" ] );
			jQuery("#birthdate").datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'yy-mm-dd'
			});
		});
	</script>
	<h1>Espace benevole > Profil <?php echo $this->member->firstname.' '.$this->member->lastname; ?></h1>
	<p>Merci de compléter le formulaire ci-dessous afin de finaliser votre inscription.</p>
	<form class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_estivole&view=member&member_id=' . (int) $this->member->member_id);?>" method="post">
		<div class="col-md-6">
			<div class="control-group">
				<label for="inputLastname" class="control-label">Nom</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[lastname]" placeholder="Nom" value="<?php echo $this->member->lastname; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputFirstname" class="control-label">Prénom</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[firstname]" placeholder="Prénom" value="<?php echo $this->member->firstname; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputEmail" class="control-label">Email</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[email]" placeholder="Email" value="<?php echo $this->member->email; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputBirthdate" class="control-label">Date de naissance</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="birthdate" name="jform[birthdate]" placeholder="Date de naissance" value="<?php echo date_format(date_create($this->userProfile->profile['dob']), 'd-m-Y'); ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputAddress" class="control-label">Adresse</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[address]" placeholder="Adresse" value="<?php echo $this->member->address; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputNpa" class="control-label">NPA</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[npa]" placeholder="NPA" value="<?php echo $this->member->npa; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputCity" class="control-label">Ville</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[city]" placeholder="Ville" value="<?php echo $this->member->city; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputCity" class="control-label">Téléphone</label>
				<div class="controls">
					<input type="text" class="input-xlarge" name="jform[phone]" placeholder="Tél." value="<?php echo $this->userProfile->profile['phone']; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="inputShirt" class="control-label">T-shirt size</label>
				<div class="controls">
					<?php echo EstivoleHelpersHtml::tshirtSizeList($this->member->tshirtsize); ?>
				</div>
			</div>	
			<div class="control-group">
				<label for="inputComment" class="control-label">Commentaire :</label>
				<div class="controls">
					<textarea class="textarea-xxlarge" name="jform[comment]" rows="5"><?php echo $this->member->comment; ?></textarea>
				</div>
			</div>			
			<div class="control-group">
				<input type="hidden" name="jform[member_id]" value="<?php echo $this->member->member_id; ?>" />
				<input type="hidden" name="task" value="member.modifyProfile" />
				<input type="submit" value="Modifier" class="btn btn-default" />
			</div>
		</div>
	</form>	
<?php	} ?>