<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;

if ($user->isAuthenticated()) {
  if ($setting->getValue('disable_inbox')) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Inbox is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
    $smarty->assign('CONTENT', '');
  } else if (@$_REQUEST['do'] == 'Reply' && !empty($_GET['message_id'])) {
    if (!$aMessage = $inbox->getMessage((int)$_REQUEST['message_id'], (int)$_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unknown message', 'TYPE' => 'errormsg');
      $smarty->assign('CONTENT', '');
    } else {
      $aMessage['content'] = "\r\n\r\n\r\n> " . $aMessage['content'];
      $aMessage['subject'] = 'Re: ' . $aMessage['subject'];

      $smarty->assign('MESSAGE', $aMessage);
      $smarty->assign('CONTENT', 'reply.tpl');
    }
  } else if (@$_REQUEST['do'] == 'send') {
    if (!$user->isAdmin($_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Only admins can send messages to users', 'TYPE' => 'errormsg');
      $smarty->assign('CONTENT', '');
    } else {
      $aUser = $user->getUserData((int)@$_REQUEST['account_id']);
      if ($aUser) {
        $smarty->assign('USER', $aUser);
        $smarty->assign('CONTENT', 'send.tpl');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unknown user', 'TYPE' => 'errormsg');
      }
    }
  } else if (@$_REQUEST['do'] == 'Delete') {
    if ($inbox->deleteMessage((int)$_REQUEST['message_id'], (int)$_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Successfully deleted message', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to delete entry: ' . $inbox->getError(), 'TYPE' => 'errormsg');
    }
  } else if (@$_REQUEST['do'] == 'save') {
    if (!$user->isAdmin($_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Only admins can send messages to users', 'TYPE' => 'errormsg');
      $smarty->assign('CONTENT', '');
    } else {
      if ($inbox->addMessage((int)$_SESSION['USERDATA']['id'], $_POST)) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Your message has been sent', 'TYPE' => 'success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to send reply: ' . $inbox->getError(), 'TYPE' => 'errormsg');
      }
    }
  } else if (@$_REQUEST['do'] == 'save_reply') {
    if ($inbox->addReply((int)$_SESSION['USERDATA']['id'], $_POST)) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Reply has been sent', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to send reply: ' . $inbox->getError(), 'TYPE' => 'errormsg');
    }
  }

  if ($smarty->getTemplateVars('CONTENT') === null) {
    $aMessages = $inbox->getAllMessages((int)$_SESSION['USERDATA']['id']);
    if (!$aMessages) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You have no messages', 'TYPE' => 'errormsg');
    } else if (is_array($aMessages)) {
      foreach ($aMessages as &$aData) {
        // Transform Markdown content to HTML
        $aData['content'] = Markdown::defaultTransform($aData['content']);
      }
    }

    $smarty->assign('MESSAGES', $aMessages);
    $smarty->assign('CONTENT', 'default.tpl');
  }
}
?>
