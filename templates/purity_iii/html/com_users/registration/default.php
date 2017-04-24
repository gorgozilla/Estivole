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
	
	<h1>Charte des bénévoles</h1>
	<p>En tant que bénévole, je porte les couleurs du festival sur moi et représente ainsi son image. Pendant mes heures de travail et aussi longtemps que je suis habillé en tant que bénévole, je m’engage à être aimable, non-violent et disponible pour le public. Je tiens également à l’image que je renvoie de moi-même, je suis donc propre et en état d’assumer mes fonctions.</p>
	<p><strong>En tant que bénévole, je dois faire attention à :</strong></p>
	<ul>
	<li>Arriver 30 minutes avant ma prise de service le temps de passer à l'accueil bénévoles..</li>
	<li>Porter les moyens permettant de m’identifier en tant que bénévoles (t-shirt et bracelet).</li>
	<li>Respecter le matériel mis à ma disposition et le restituer dans le meilleur état.</li>
	<li>Respecter la propreté du site.</li>
	<li>Respecter la propreté de mon espace de travail et de mon lieu de pause.</li>
	<li>Respecter les consignes de sécurité qui me sont données.</li>
	<li>Respecter les différentes zones à accès</li>
	<li>Respecter la tranquillité des artistes</li>
	<li>Eviter les nuisances sonores après la fermeture du site. Des habitations et un home médicalisé sont à proximité immédiate du site.</li>
	<li>Avertir mon responsable direct si je ne peux pas venir travailler.</li>
	<li>Ne pas accorder de privilèges à mes proches comme des boissons offertes ou autres.</li>
	<li>Respecter la législation suisse en matière de vente d’alcool aux mineurs.</li>
	</ul>
	<h2>A quoi ai-je droit ?</h2>
	<ul>
	<li>L’entrée au festival du jour travaillé.</li>
	<li>Un T-Shirt.</li>
	<li>Un bracelet bénévole me donnant accès à :
	<ul>
	<li>A l’espace bénévole (minérales gratuites, bière pression 2.-).</li>
	<li>Au camping bénévole.</li>
	<li>Un bon repas valable à l’espace bénévole.</li>
	<li>Deux bons boissons valable dans les bars de l’Estivale et à l’espace bénévole.</li>
	<li>Un petit déjeuner pour 2.- (pain, confiture, cacao, café et jus).</li>
	</ul>
	</li>
	</ul>
	<p>Si je ne respecte pas les différents éléments de cette charte, j’accepte que l’on me relève de mes fonctions.</p>
	<p><strong>Déjà 1000 milles mercis à tous. Au plaisir de se retrouver pour l’édition 2017.</strong></p>

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
