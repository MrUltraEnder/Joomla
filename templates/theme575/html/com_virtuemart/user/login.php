<?php
/**
*
* Layout for the login
*
* @package	VirtueMart
* @subpackage User
* @author Max Milbers, George Kostopoulos
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 4431 2011-10-17 grtrustme $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

//set variables, usually set by shopfunctionsf::getLoginForm in case this layout is differently used
if (!isset( $this->show )) $this->show = TRUE;
if (!isset( $this->from_cart )) $this->from_cart = FALSE;
if (!isset( $this->order )) $this->order = FALSE ;

if(!class_exists('shopFunctionsF')) require(JPATH_VM_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
$comUserOption='com_users';
if (empty($this->url)){
	$url = vmURI::getCleanUrl();
} else{
	$url = $this->url;
}

$user = JFactory::getUser();

if ($this->show and $user->id == 0  ) {
JHTML::_('behavior.formvalidation');
JHTML::_ ( 'behavior.modal' );

	//Extra login stuff, systems like openId and plugins HERE
    if (JPluginHelper::isEnabled('authentication', 'openid')) {
        $lang = JFactory::getLanguage();
        $lang->load('plg_authentication_openid', JPATH_ADMINISTRATOR);
        $langScript = '
//<![CDATA[
'.'var JLanguage = {};' .
                ' JLanguage.WHAT_IS_OPENID = \'' . JText::_('WHAT_IS_OPENID') . '\';' .
                ' JLanguage.LOGIN_WITH_OPENID = \'' . JText::_('LOGIN_WITH_OPENID') . '\';' .
                ' JLanguage.NORMAL_LOGIN = \'' . JText::_('NORMAL_LOGIN') . '\';' .
                ' var comlogin = 1;
//]]>
                ';
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($langScript);
        JHTML::_('script', 'openid.js');
    }

    $html = '';
    JPluginHelper::importPlugin('vmpayment');
    $dispatcher = JDispatcher::getInstance();
    $returnValues = $dispatcher->trigger('plgVmDisplayLogin', array($this, &$html, $this->from_cart));

if (is_array($html)) {
	foreach ($html as $login) {
	    echo $login;
	}
}
else {
	echo $html;
}

//end plugins section

//anonymous order section
if ( $this->order ) { ?>
    <div class="order-view">

        <h2 class="form_title"><?php echo JText::_('COM_VIRTUEMART_ORDER_ANONYMOUS') ?></h2>

        <form action="<?php echo JRoute::_( 'index.php', 1, $this->useSSL); ?>" method="post" name="com-login" class="form-inline">

        	<div class="form-group" id="com-form-order-number">
        		<label class="sr-only" for="order_number"><?php echo JText::_('COM_VIRTUEMART_ORDER_NUMBER') ?></label>
        		<input type="text" id="order_number" name="order_number" class="form-control inputbox" alt="order_number" placeholder="<?php echo JText::_('COM_VIRTUEMART_ORDER_NUMBER') ?>" />
        	</div>
        	<div class="form-group" id="com-form-order-pass">
        		<label class="sr-only" for="order_pass"><?php echo JText::_('COM_VIRTUEMART_ORDER_PASS') ?></label>
        		<input type="text" id="order_pass" name="order_pass" class="form-control inputbox" alt="order_pass" value="p_" placeholder="<?php echo JText::_('COM_VIRTUEMART_ORDER_PASS') ?>" />
        	</div>

        		<input type="submit" name="Submitbuton" class="btn btn-default button" value="<?php echo JText::_('COM_VIRTUEMART_ORDER_BUTTON_VIEW') ?>" />

        	<div class="clr"></div>
        	<input type="hidden" name="option" value="com_virtuemart" />
        	<input type="hidden" name="view" value="orders" />
        	<input type="hidden" name="layout" value="details" />
        	<input type="hidden" name="return" value="" />

        </form>

    </div>

<?php } ?>

    <form id="com-form-login" action="<?php echo JRoute::_('index.php', $this->useXHTML, $this->useSSL); ?>" method="post" name="com-login">

	    <?php if (!$this->from_cart ) { ?>
        	<div>
        		<h2><?php echo JText::_('COM_VIRTUEMART_ORDER_CONNECT_FORM'); ?></h2>
        	</div>
        <?php } else { ?>
                <?php /*?><p><?php echo JText::_('COM_VIRTUEMART_ORDER_CONNECT_FORM'); ?></p><?php */?>
        <?php }   ?>
        
    <div class="container2">
         <div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
         <div class="form-group">
            <div id="com-form-login-username">
                <label class="sr-only" for=""><?php echo JText::_('COM_VIRTUEMART_USERNAME'); ?></label>
                <input type="text" name="username" class="form-control inputbox" alt="<?php echo JText::_('COM_VIRTUEMART_USERNAME'); ?>" placeholder="<?php echo JText::_('COM_VIRTUEMART_USERNAME'); ?>" />
            </div>
        </div>
        <div class="checkbox">
            <div id="com-form-login-remember">                
                <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                    <label for="remember"><?php echo $remember_me = vmText::_('JGLOBAL_REMEMBER_ME'); ?>
                        <input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" />
                    </label>
                <?php endif; ?>
            </div>        
        </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
        	 <div class="row">
             	 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                 <div class="form-group">
                    <div id="com-form-login-password">
                            <label class="sr-only" for=""><?php echo JText::_('COM_VIRTUEMART_PASSWORD'); ?></label>
                            <input id="modlgn-passwd" type="password" name="password" class="form-control inputbox" alt="<?php echo JText::_('COM_VIRTUEMART_PASSWORD'); ?>" placeholder="<?php echo JText::_('COM_VIRTUEMART_PASSWORD'); ?>" />
                    </div>        </div>
                 </div>
                 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                 	<input type="submit" name="Submit" class="btn btn-default" value="<?php echo JText::_('COM_VIRTUEMART_LOGIN') ?>" />
                 </div>
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="list__login-links">
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option='.$comUserOption.'&view=remind'); ?>" rel="nofollow">
                            <?php echo JText::_('COM_VIRTUEMART_ORDER_FORGOT_YOUR_USERNAME'); ?></a>
                        </li>
                        <li class="or"><?php echo JText::_('COM_VIRTUEMART_ORDER_OR'); ?></li>
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option='.$comUserOption.'&view=reset'); ?>" rel="nofollow">
                            <?php echo JText::_('COM_VIRTUEMART_ORDER_FORGOT_YOUR_PASSWORD'); ?></a>
                        </li>
              		</ul>
                 </div>
             </div>
        </div>
 		
		 
	</div>
</div>
       
	     <input type="hidden" name="task" value="user.login" />
        <input type="hidden" name="option" value="<?php echo $comUserOption ?>" />
        <input type="hidden" name="return" value="<?php echo base64_encode($url) ?>" />
        <?php echo JHTML::_('form.token'); ?>
    </form>

<?php  } else if ( $user->id ) { ?>

    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="login" id="form-login">
        <?php echo JText::sprintf( 'COM_VIRTUEMART_HINAME', $user->name ); ?>
	    <input type="submit" name="Submit" class="btn btn-default button" value="<?php echo JText::_( 'COM_VIRTUEMART_BUTTON_LOGOUT'); ?>" />

        <input type="hidden" name="option" value="<?php echo $comUserOption ?>" />
        <?php if ( JVM_VERSION===1 ) { ?>
            <input type="hidden" name="task" value="logout" />
        <?php } else { ?>
            <input type="hidden" name="task" value="user.logout" />
        <?php } ?>
        <?php echo JHtml::_('form.token'); ?>
	   <input type="hidden" name="return" value="<?php echo base64_encode($url) ?>" />
    </form>

<?php } ?>

