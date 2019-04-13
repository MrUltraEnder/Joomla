<?php
/*
 * @component QuickDownload
 * @version 3.1 'QD-03'
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$extension = QuickDownloadHelper::getExtensionDetails ('quickdownload');
?>

	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
 
<div class="span6">
	
	<div class="clr"></div>
	<p style="font-size:16px;">If you need more control over your file access we recommend you to take a look at our component <a href="http://extensions.joomla.org/extensions/access-a-security/site-access/content-restriction/12685">J!Extranet</a>.</p>
	<p style="font-size:16px;"><a href="http://extensions.joomla.org/extensions/access-a-security/site-access/content-restriction/12685">J!Extranet</a> is the perfect Joomla component to implement a restricted area for each user. </p>
	<p style="font-size:16px;">J!Extranet offers all the features a client area needs and unlike other solutions, it does not require an IT department while it allows you to tightly control user access.</p>


</div>


<div class="span4">			
		<h3><?php echo  JText::_('COM_QUICKDOWNLOAD_VERSION') ; ?></h3>
		<p><?php echo $extension['version'];?></p>

		<h3><?php echo  JText::_('COM_QUICKDOWNLOAD_COPYRIGHT') ; ?></h3>
		<p>Ionut Lupu</p>

		<h3><?php echo  JText::_('COM_QUICKDOWNLOAD_LICENSE') ; ?></h3>
		<p>GNU/GPL</p>
		
		<h3>Feedback</h3>
		<p>If you use QuickDownload, please post a rating and a review at the <a href="http://extensions.joomla.org/extensions/directory-a-documentation/downloads/14306">Joomla! Extensions Directory</a>.</p>
</div>
