<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Template extends Base {
  protected $table = 'templates';
  /**
   * Get filepath for template name based on current PAGE and ACTION
   */
  public function getFullpath($name) {
    $chunks = array(PAGE);
    if( ACTION )
      $chunks[] = ACTION;
    $chunks[] = $name;

    return join('/', $chunks);
  }

  /**
   * Get all available themes
   * Read theme folders from TEMPLATE_DIR
   *
   * @return array - list of available themes
   */
  public function getThemes() {
    $this->debug->append("STA " . __METHOD__, 4);
    $aTmpThemes = glob(TEMPLATE_DIR . '/*');
    $aThemes = array();
    foreach ($aTmpThemes as $dir) {
      if (basename($dir) != 'cache' && basename($dir) != 'compile' && basename($dir) != 'mail') $aThemes[basename($dir)] = basename($dir);
    }
    return $aThemes;
  }

  /**
   * Get all available designs
   * Read css files from css/design folder
   *
   * @return array - list of available designs
   */
  public function getDesigns() {
    $this->debug->append("STA " . __METHOD__, 4);
    $aTmpDesigns = glob(BASEPATH . 'site_assets/' . THEME . '/css/design/*.css');
    $aDesigns = array();
    $aDesigns['default'] = 'default';
    foreach ($aTmpDesigns as $filename) {
      if (basename($filename) != '.' && basename($filename) != '..') $aDesigns[basename($filename, ".css")] = basename($filename, ".css");
    }
    return $aDesigns;
  }
  
  /**
   * Cached getActiveTemplates method
   *
   * @see getActiveTemplates
   */
  private static $active_templates;
  public function cachedGetActiveTemplates() {
      if ( is_null(self::$active_templates) ) {
          self::$active_templates = $this->getActiveTemplates();
      }
      return self::$active_templates;
  }
  /**
   * Return the all active templates as hash,
   * where key is template and value is modified_at
   *
   * @return array - list of active templates
   */
  public function getActiveTemplates() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT template, modified_at FROM $this->table WHERE active = 1");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result()) {
      $rows = $result->fetch_all(MYSQLI_ASSOC);
      $hash = array();
      foreach($rows as $row) {
          $hash[$row['template']] = strtotime($row['modified_at']);
      }
      return $hash;
    }

    $this->setErrorMessage('Failed to get active templates');
    $this->debug->append('Template::getActiveTemplates failed: ' . $this->mysqli->error);
    return false;
  }

  /**
   * Return the content of specific template file
   *
   * @param $file - file of template related to TEMPLATE_DIR
   * @return string - content of the template file
   */
  public function getTemplateContent($file) {
    $this->debug->append("STA " . __METHOD__, 4);
    $filepath = TEMPLATE_DIR . '/' . $file;
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
    $folder = TEMPLATE_DIR . '/' . $theme;

    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, '!'.preg_quote($folder, '!').'/(.*\.tpl$)!', RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList[] = $theme . '/' . $file[1];
    }

    return $fileList;
  }

  /**
   * Get tree of all possible templates, where key is filename
   * and value is whether array of subfiles if filename is directory
   * or true, if filename is file
   *
   * @param $themes - optional, themes array
   * @return array - tree of all templates
   */
  public function getTemplatesTree($themes = null) {
    if( is_null($themes) )
      $themes = $this->getThemes();

    $templates = array();
    foreach($themes as $theme) {
      $templates[$theme] = $this->_getTemplatesTreeRecursive(TEMPLATE_DIR . '/' . $theme);
    }

    return $templates;

  }

  private function _getTemplatesTreeRecursive($path) {
    if( !is_dir($path) ) {
      return preg_match("/\.tpl$/", $path);
    } else {
      $subfiles = scandir($path);
      if ( $subfiles === false )
        return false;

      $files = array();
      foreach($subfiles as $subfile) {
        if($subfile == ".." || $subfile == ".") continue;
        $subpath = $path . '/' . $subfile;
        $subsubfiles = $this->_getTemplatesTreeRecursive($subpath);
        if ( !$subsubfiles ) continue;
        $files[$subfile] = $subsubfiles;
      }
      return $files;
    }
    return array();
  }

  /**
   * Return specific template from database
   *
   * @param $template - name (filepath) of the template
   * @return array - result from database
   */
  public function getEntry($template, $columns = "*") {
    $this->debug->append("STA " . __METHOD__, 4);

    $stmt = $this->mysqli->prepare("SELECT $columns FROM $this->table WHERE template = ?");
    if ($stmt && $stmt->bind_param('s', $template) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();

    $this->setErrorMessage('Failed to get the template');
    $this->debug->append('Template::getEntry failed: ' . $this->mysqli->error);
    return false;
  }

  /**
   * Return last modified time of specific template from database
   *
   * @param $template - name (filepath) of the template
   * @return timestamp - last modified time of template
   */
  public function getEntryMTime($template) {
    $this->debug->append("STA " . __METHOD__, 4);

    $entry = $this->getEntry($template, "modified_at, active");
    if ( $entry && $entry['active'])
        return strtotime($entry['modified_at']);

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
