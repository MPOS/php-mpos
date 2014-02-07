<?php 

class Logger {
  private $KLogger;
  private $logging = false;
  public function __construct($config) {
    if ($config['logging']['enabled'] && $config['logging']['level'] > 0) {
      $this->KLogger = new KLogger($config['logging']['path']."/".$config['logging']['file'], $config['logging']['level']);
      $this->logging = true;
    }
  }
  public function log($type, $message) {
    // Logmask, we add some infos into the KLogger
    $strMask = "[ %12s ] [ %8s | %-8s ] : %s";
    $strIPAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $strPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 'none';
    $strAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'none';
    if ($this->logging) {
      switch ($type) {
      	case 'info':
          $this->KLogger->LogInfo(sprintf($strMask, $strIPAddress, $strPage, $strAction, $message));
          break;
        case 'warn':
          $this->KLogger->LogWarn(sprintf($strMask, $strIPAddress, $strPage, $strAction, $message));
          break;
        case 'error':
          $this->KLogger->LogError(sprintf($strMask, $strIPAddress, $strPage, $strAction, $message));
          break;
        case 'fatal':
          $this->KLogger->LogFatal(sprintf($strMask, $strIPAddress, $strPage, $strAction, $message));
          break;
      }
      return true;
    } else {
      return true;
    }
  }
}
$log = new Logger($config);
?>
