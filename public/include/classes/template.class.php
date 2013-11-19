<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Template extends Base {
  protected $table = 'templates';

  /**
   * Get all available themes
   * Read theme folders from THEME_DIR
   *
   * @return array - list of available themes
   */
  public function getThemes() {
    $this->debug->append("STA " . __METHOD__, 4);
    $aTmpThemes = glob(THEME_DIR . '/*');
    $aThemes = array();
    foreach ($aTmpThemes as $dir) {
      if (basename($dir) != 'cache' && basename($dir) != 'compile' && basename($dir) != 'mail') $aThemes[basename($dir)] = basename($dir);
    }
    return $aThemes;
  }

  /**
   * Return the content of specific template file
   *
   * @param $file - file of template related to THEME_DIR
   * @return string - content of the template file
   */
  public function getTemplateContent($file) {
    $this->debug->append("STA " . __METHOD__, 4);
    $filepath = THEME_DIR . '/' . $file;
    return file_get_contents($filepath);
  }

  /**
   * Get all possible templates of specific theme
   *
   * @param $theme - name of the theme
   * @return array - list of available templates of theme
   */
  public function getTemplateFiles($theme) {
    $this->debug->append("STA " . __METHOD__, 4);
    $folder = THEME_DIR . '/' . $theme;

    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, '!'.preg_quote($folder, '!').'/(.*\.tpl$)!', RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList[] = $file[1];
    }

    return $fileList;
  }

  /**
   * Return specific template form database
   *
   * @param $template - name (filepath) of the template
   * @return array - result from database
   */
  public function getEntry($template) {
    $this->debug->append("STA " . __METHOD__, 4);

    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE template = ?");
    if ($stmt && $stmt->bind_param('s', $template) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();

    $this->setErrorMessage('Failed to get the template');
    $this->debug->append('Template::getEntry failed: ' . $this->mysqli->error);
    return false;
  }

  /**
   * Update template in database
   *
   * @param $template - name (filepath) of the template
   * @param $content - content of the template
   * @param $active - active flag for the template
   **/
  public function updateEntry($template, $content, $active=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (`template`, `content`, `active`, `modified_at`) VALUES(?, ?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE content = VALUES(content), active = VALUES(active), modified_at = CURRENT_TIMESTAMP");
    if ($stmt && $stmt->bind_param('ssi', $template, $content, $active) && $stmt->execute())
      return true;

    $this->setErrorMessage('Database error');
    $this->debug->append('Template::updateEntry failed: ' . $this->mysqli->error);
    return false;
  }
}

$template = new Template();
$template->setDebug($debug);
$template->setMysql($mysqli);
