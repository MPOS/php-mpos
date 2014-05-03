<?php 

class Logger {
  private $KLogger;
  private $logging = false;
  public function __construct($config) {
    if ($config['logging']['enabled'] && $config['logging']['level'] > 0) {
      $this->KLogger = KLogger::instance($config['logging']['path'] . '/website', $config['logging']['level']);
      $this->logging = true;
      $this->floatStartTime = microtime(true);
    }
  }
  public function log($strType, $strMessage) {
    // Logmask, we add some infos into the KLogger
    $strMask = "[ %12s ] [ %8s | %-8s ] [ %7.7s ] : %s";
    $strIPAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $strPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 'none';
    $strAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'none';
    $strMessage = sprintf($strMask, $strIPAddress, $strPage, $strAction, number_format(round((microtime(true) - $this->floatStartTime) * 1000, 2), 2), $strMessage);
    if ($this->logging) {
      switch ($strType) {
      case 'emerg':
        $this->KLogger->LogEmerg($strMessage);
        break;
      case 'alert':
        $this->KLogger->LogAlert($strMessage);
        break;
      case 'crit':
        $this->KLogger->LogCrit($strMessage);
        break;
      case 'error':
        $this->KLogger->LogError($strMessage);
        break;
      case 'warn':
        $this->KLogger->LogWarn($strMessage);
        break;
      case 'notice':
        $this->KLogger->LogNotice($strMessage);
        break;
      case 'info':
        $this->KLogger->LogInfo($strMessage);
        break;
      case 'fatal':
        $this->KLogger->LogFatal($strMessage);
        break;
      case 'debug':
        $this->KLogger->LogDebug($strMessage);
        break;
      case '':
        $this->KLogger->LogFatal($strMessage);
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
