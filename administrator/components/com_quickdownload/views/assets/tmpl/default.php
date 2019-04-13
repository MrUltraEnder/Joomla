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

$user 	= JFactory::getUser();
$folder = JRequest::getVar('folder','','','string');
?>

<script type="text/javascript">
	function insertURL ( file ) {
		document.uploadForm.getElementById('f_url').value = '<?php echo $folder;?>' + '/' + file;
	}

	function insertFile() {
		file = document.uploadForm.getElementById('f_url').value;
		if ( ! file ) {
			return false;
		}
		window.parent.document.adminForm.getElementById('<?php echo JRequest::getCmd('fieldid', '');?>').value = file;
		window.parent.SqueezeBox.close();
	}
</script>

<div class="row-fluid upload">
	<div class="span12">

		<form action="<?php echo JURI::base(); ?>index.php?option=com_quickdownload&task=file.upload&<?php echo JFactory::getSession()->getFormToken(); ?>=1" id="uploadForm" name="uploadForm" method="post" enctype="multipart/form-data">

			<div class="well">
				<div class="fltrt">
					<button class="btn btn-primary" type="button" onclick="insertFile();"><?php echo JText::_('COM_QUICKDOWNLOAD_FILES_INSERT') ?></button>
					<button class="btn" type="button" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('JCANCEL'); ?></button>
				</div>
			</div>

			
			<div class="row-fluid">
				<div class="span12">
					<?php if ( !empty( $this->files ) ) { foreach ( $this->files as $file ) {?>
						<div class="well well-small pull-left file">
							<div class="row-fluid">
								<div class="span12">
									<a href="javascript:insertURL('<?php echo $file->title;?>');" title="<?php echo $file->title;?>"><?php echo (strlen( $file->title ) > 10) ? substr( $file->title, 0, 10 ) . '...' : $file->title;?></a>
									<div class="row-fluid">
										<div class="span12">
											<i class="icon icon-file text-center" title="<?php echo $file->title;?>"></i> <?php echo $file->filetype;?>
											<div class="row-fluid">
												<div class="span12 small">
													<?php echo QuickDownloadHelper::parseSize($file->size) ;?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } } ?> 
				</div>
			</div>

			<label for="f_url"><?php echo JText::_('COM_QUICKDOWNLOAD_FILES_URL') ?></label>
			<input type="text" id="f_url" value="" class="input input-xlarge" />


			<div class="clearfix">
			</div>

			<div class="well">
					<label for="upload-file" class="hidelabeltxt"><?php echo JText::_('COM_QUICKDOWNLOAD_FILES_UPLOAD'); ?></label>
					<input type="file" id="upload-file" name="Filedata" class="input" />

					<button class="btn btn-primary" id="upload-submit">
						<i class="icon-upload icon-white"></i> <?php echo JText::_('COM_QUICKDOWNLOAD_FILES_START_UPLOAD'); ?>
					</button>
					<input type="hidden" name="folder" value="<?php echo $folder;?>" />
					<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_quickdownload&view=assets&tmpl=component&fieldid='.JRequest::getCmd('fieldid', '').'&folder='.$folder); ?>" />
			</div>
		
			<?php echo JHtml::_('form.token'); ?>

		</form>

	</div>
</div>
