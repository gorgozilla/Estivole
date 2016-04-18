<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/service.php';
require_once JPATH_COMPONENT . '/models/member.php';

/*
JMail deifinition
--------------------
addAttachment 
- Add file attachments to the email

addBCC
-Add blind carbon copy recipients to the email

addCC 
- Add carbon copy recipients to the email

addRecipient 
- Add recipients to the email

addReplyTo 
- Add Reply to email address(es) to the email

getInstance 
- Returns the global email object, only creating it if it doesn't already exist.

isHtml 
- Sets message type to HTML

Send 
- Send the mail

sendAdminMail 
- Sends mail to administrator for approval of a user submission

sendMail 
- Function to send an email

setBody 
- Set the email body

setSender 
- Set the email sender

setSubject 
- Set the email subject

useSendmail 
- Use sendmail for sending the email

useSMTP 
- Use SMTP for sending the email */

class EstivoleHelpersMail
{	
	function confirmMemberDaytime($member_id, $service_id, $daytime_id)
	{	
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_members as b, #__estivole_services as s, #__estivole_daytimes as d');
		$query->where('b.member_id = ' . (int) $member_id);
		$query->where('s.service_id = ' . (int) $service_id);
		$query->where('d.daytime_id = ' . (int) $daytime_id);
		$db->setQuery($query);
		$mailModel = $db->loadObject();
		
		$mailBody = "<h1>Confirmation d'inscription à une tranche horaire Estivale 2016</h1>
		
		<p>Cher (Chère) bénévole,</p>

		<p>Tout d'abord un grand merci à toi pour ta contribution à cette belle fête qu'est l'Estivale Open Air! Sans toi cette manifestation n'existerait pas et nous en sommes grandement reconnaissants! :)</p>

		<p>Je suis le responsable du secteur ".$mailModel->service_name.". C'est donc moi qui m'occuperait de t'accueillir et te donner les consignes nécessaires pour remplir ta tâche.</p>
		Cette dernière se veut assez simple, voici un résumé :</p>

		<p>".$mailModel->summary."</p>

		<p><strong>Voici la date et tranche horaire confirmée :</strong></p>

		<p><strong>".$mailModel->description."  -  ".date('d-m-Y',strtotime($mailModel->daytime_day))."  -  ".date('H:i',strtotime($mailModel->daytime_hour_start))."  -  ".date('H:i',strtotime($mailModel->daytime_hour_end))."</strong></p>	

		<p>Tu es prié de te rendre 15 minutes avant le début de ta tranche horaire à l'accueil bénévoles situé à proximité l'entrée de l'entrée du festival. C'est là que te seront remis ton pass pour la soirée ainsi que les consignes pour la suite des événements.</p>

		<p>Voilà pour ces premières informations, je te ferai un rappel de tout ceci quelques jours avant le début du festival!</p>
		
		<p><a href=\"".JURI::root()."index.php/votre-calendrier\">Cliquez ici</a> pour accéder à votre \"calendrier bénévole\" et ainsi voir toutes vos tranches horaires.</p>

		<p>Encore merci à toi pour ton aide et au plaisir de te rencontrer tout bientôt! :)</p>

		<p>Meilleures salutations,</p>";
		
		$email_member = $mailModel->email;
		
		define("BodyConfirmMemberDaytime", $mailBody);
		define("SubjectConfirmMemberDaytime", "Confirmation d'inscription bénévole Estivale 2015");
		
		$mail = JFactory::getMailer();
		$mail->setBody(constant("BodyConfirmMemberDaytime"));
		$mail->setSubject(constant("SubjectConfirmMemberDaytime"));
		$mail->isHtml();
		$mail->addRecipient($email_member);
		$mail->Send();
	}
	
	function confirmResponsableDaytime($service_id, $daytime_id, $member_id)
	{	
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__estivole_services as s, #__estivole_daytimes as d, #__estivole_members as m');
		$query->where('s.service_id = ' . (int) $service_id);
		$query->where('d.daytime_id = ' . (int) $daytime_id);
		$query->where('m.member_id = ' . (int) $member_id);
		$db->setQuery($query);
		$mailModel = $db->loadObject();
		$this->user = JFactory::getUser($mailModel->user_id);
		
		// Get the dispatcher and load the user's plugins.
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('user');
		$data = new JObject;
		$data->id = $mailModel->user_id;
		// Trigger the data preparation event.
		$dispatcher->trigger('onContentPrepareData', array('com_users.profilestivole', &$data));

		$userProfilEstivole=$data;
		$userName = $userProfilEstivole->profilestivole['firstname'].' '.$userProfilEstivole->profilestivole['lastname'];
		
		$mailBody = "<h1>Confirmation de tranche horaire secteur ".$mailModel->service_name."</h1>
		
		<p>Cher (Chère) responsable du secteur ".$mailModel->service_name.",</p>

		<p><strong>Voici la date et tranche horaire confirmée :</strong></p>
		
		<p>Nom bénévole : ".$userName."<br />
		Email bénévole : <a href=\"mailto:".$this->user->email."\">".$this->user->email."</a><br />
		<p><strong>".$mailModel->description."  -  ".date('d-m-Y',strtotime($mailModel->daytime_day))."  -  ".date('H:i',strtotime($mailModel->daytime_hour_start))."  -  ".date('H:i',strtotime($mailModel->daytime_hour_end))."</strong></p>";
		
		$email_responsable = $mailModel->email_responsable;
		
		define("BodyConfirmResponsable", $mailBody);
		define("SubjectConfirmResponsable", "Confirmation de tranche horaire secteur ".$mailModel->service_name);
		
		$mail = JFactory::getMailer();
		$mail->setBody(constant("BodyConfirmResponsable"));
		$mail->setSubject(constant("SubjectConfirmResponsable"));
		$mail->isHtml();
		$mail->addRecipient($email_responsable);
		$mail->Send();
	}
}