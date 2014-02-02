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
    if ($this->logging) {
      switch ($type) {
      	case 'info':
          $this->KLogger->LogInfo($message);
          break;
        case 'warn':
          $this->KLogger->LogWarn($message);
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