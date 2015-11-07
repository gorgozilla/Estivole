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
	<h1>Espace benevole > Secteurs</h1>
	
	<?php foreach ($this->services as $i => $item){ ?>
		<h2>"<?php echo $item->name; ?>"</h2>
		<p><?php echo $item->summary; ?></p>
	<?php } ?>