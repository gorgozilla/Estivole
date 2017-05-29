<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
?>
<div class="registration<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	
	<h1>Rejoins nous en tant que bénévole</h1>
	<p>La 27e édition de l’Estivale Open Air aura lieu cette année du vendredi 28 juillet au mardi 01 août 2017.</p>

	<p>Rejoins-nous pour participer au bon fonctionnement d'un festival exceptionnel !</p>

	<p><strong>Et  si  tu  fais  déjà  partie  de  cette  grande  famille  alors  tes  données  de  l’année  passée sont toujours valables.</strong></p>

	<h2>Marche à suivre</h2>

	<ol>
	<li>Connecte-toi à ton compte grâce au formulaire ci-dessous ou <a href="index.php?option=com_users&view=registration&Itemid=134">crée un compte bénévole</a></li>
	<li>Confirme ton inscription grâce à l'email de confirmation envoyé après la création de ton compte</li>
	<li>Une fois ton compte validé, connecte-toi à ce dernier et rends toi dans le menu "Votre profil > Mon calendrier"</li>
	</ol>

	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form well" enctype="multipart/form-data">
		<?php // Iterate through the form fieldsets and display each one. ?>
		<?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
			<?php $fields = $this->form->getFieldset($group);?>
			<?php if (count($fields)):?>
			<fieldset>
				<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
				<legend><?php echo JText::_($fieldset->label); ?></legend>
				<?php endif;?>
				<?php 
				$i=0;
				foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
					<?php if ($field->hidden):// If the field is hidden, just display the input.?>
						<div class="control-group">
							<div class="controls">
								<?php echo $field->input;?>
							</div>
						</div>
					<?php else:?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
								<?php if (!$field->required && $field->type != 'Spacer') : ?>
								<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
								<?php endif; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endif;?>
				<?php endforeach;?>
			</fieldset>
			<?php endif;?>
		<?php endforeach;?>
		<div class="control-group">
			<label>Camping accessible uniquement avec une tente (pas d'accès pour les camping car, voitures, bus, caravane, etc...)</label>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<label for="conditions">J'ai lu et j'accepte la <a href="images/charte.pdf" target="_blank" title="Lire la charte">charte des bénévoles</a></label>
			</div>
			<div class="controls">
				<input type="checkbox" name="conditions" id="conditions" class=required value="1">							
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate"><?php echo JText::_('JREGISTER');?></button>
				<a class="btn" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
