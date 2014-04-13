<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Monitoring extends Base {
   protected $table = 'monitoring';

  /**
   * Store Uptime Robot status information as JSON in settings table
   * @param none
   * @return bool true on success, false on error
   **/
  public function storeUptimeRobotStatus() {
    if ($api_keys = $this->setting->getValue('monitoring_uptimerobot_api_keys')) {
      $aJSONData = array();
      $url = 'http://api.uptimerobot.com';
      $aMonitors = explode(',', $api_keys);
      foreach ($aMonitors as $aData) {
        $temp = explode('|', $aData);
        $aMonitor['api_key'] = trim($temp[0]);
        $aMonitor['monitor_id'] = trim($temp[1]);
        $target = '/getMonitors?apiKey=' . $aMonitor['api_key'] . '&monitors=' . $aMonitor['monitor_id'] . '&format=json&noJsonCallback=1&customUptimeRatio=1-7-30&logs=1';
        $aMonitorStatus = $this->tools->getApi($url, $target);
        if (!$aMonitorStatus || @$aMonitorStatus['stat'] == 'fail') {
          if (is_array($aMonitorStatus) && array_key_exists('message', @$aMonitorStatus)) {
            $this->setErrorMessage($this->getErrorMsg('E0032', $aMonitorStatus['message']));
          } else {
            $this->setErrorMessage($this->getErrorMsg('E0032', $this->tools->getError()));
          }
          return false;
        }
        $aMonitorStatus['monitors']['monitor'][0]['customuptimeratio'] = explode('-', $aMonitorStatus['monitors']['monitor'][0]['customuptimeratio']);
        $aAllMonitorsStatus[] = $aMonitorStatus['monitors']['monitor'][0];
      }
      if (!$this->setting->setValue('monitoring_uptimerobot_status', json_encode($aAllMonitorsStatus)) || !$this->setting->setValue('monitoring_uptimerobot_lastcheck', time())) {
        $this->setErrorMessage($this->getErrorMsg('E0033'), $setting->getError());
        return false;
      }
    }
    return true;
  }

  /**
   * Fetch Uptime Robot Status from settings table
   * @param none
   * @return array Data on success, false on failure
   **/
  public function getUptimeRobotStatus() {
    if ($json = $this->setting->getValue('monitoring_uptimerobot_status'))
      return json_decode($json, true);
    return false;
  }

  /**
   * Check that our cron is currently activated
   * @param name string Cronjob name
   * @return bool true or false
   **/
  public function isDisabled($name) {
    $aStatus = $this->getStatus($name . '_disabled');
    return $aStatus['value'];
  }

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getStatus($name) {
    $query = $this->mysqli->prepare("SELECT * FROM $this->table WHERE name = ? LIMIT 1");
    if ($query && $query->bind_param('s', $name) && $query->execute() && $result = $query->get_result()) {
      return $result->fetch_assoc();
    } else {
      return $this->sqlError();
    }
  }

  /**
   * Insert or update a setting
   * @param name string Name of the variable
   * @param value string Variable value
   * @return bool
   **/
  public function setStatus($name, $type, $value) {
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (name, type, value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE value = ?
      ");
    if ($stmt && $stmt->bind_param('ssss', $name, $type, $value, $value) && $stmt->execute())
      return true;
    $this->debug->append("Failed to set $name to $value");
    return false;
  }

  /**
   * Start a cronjob, mark various fields properly
   * @param cron_name string Cronjob name
   **/
  public function startCronjob($cron_name) {
    $aStatus = $this->getStatus($cron_name . '_active');
    if ($aStatus['value'] == 1) {
      $this->setErrorMessage('Cron is already active in database: ' . $cron_name . '_active is 1, please force run with -f once ensured it\' not running');
      return false;
    }
    $this->setStatus($cron_name . "_active", "yesno", 1);
    $this->setStatus($cron_name . '_starttime', 'date', time());
    return true;
  }

  /**
   * End cronjob with an error message
   * @param cron_name string Cronjob Name
   * @param msgCode string Message code as stored in error_codes array
   * @param exitCode int Exit code to pass on to exit function and monitor report
   * @param fatal boolean Should we exit out entirely
   * @return none
   **/
  public function endCronjob($cron_name, $msgCode, $exitCode=0, $fatal=false, $mail=true) {
    $this->setStatus($cron_name . "_active", "yesno", 0);
    $this->setStatus($cron_name . "_message", "message", $this->getErrorMsg($msgCode));
    $this->setStatus($cron_name . "_status", "okerror", $exitCode);
    $this->setStatus($cron_name . "_endtime", "date", time());
    if ($mail) {
      $aMailData = array(
        'email' => $this->setting->getValue('system_error_email'),
        'subject' => 'Cronjob Failure',
        'Error Code' => $msgCode,
        'Error Message' => $this->getErrorMsg($msgCode)
      );
      if (!$this->mail->sendMail('notifications/error', $aMailData))
        $this->setErrorMessage('Failed to send mail notification');
    }
    if ($fatal) {
      if ($exitCode != 0) $this->setStatus($cron_name . "_disabled", "yesno", 1);
      exit($exitCode);
    }
  }
}

$monitoring = new Monitoring();
$monitoring->setErrorCodes($aErrorCodes);
$monitoring->setConfig($config);
$monitoring->setDebug($debug);
$monitoring->setMail($mail);
$monitoring->setMysql($mysqli);
$monitoring->setSetting($setting);
