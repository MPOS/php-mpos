<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$debug->append('Loading Smarty libraries', 2);
define('SMARTY_DIR', INCLUDE_DIR . '/smarty/libs/');

// Include the actual smarty class file
include(SMARTY_DIR . 'Smarty.class.php');

/**
 * Custom Smarty Template Resource for Pages
 * Get templates from Database
 * Allow admin to manage his templates from Backoffice
 */
class Smarty_Resource_Database extends Smarty_Resource_Custom {
  protected $template;

  public function __construct($template) {
    $this->template = $template;
  }
  /**
   * Fetch a template and its modification time from database
   *
   * @param string $name template name
   * @param string $source template source
   * @param integer $mtime template modification timestamp (epoch)
   * @return void
   */
  protected function fetch($name, &$source, &$mtime) {
    $oTemplate = $this->template->getEntry($this->fullTemplateName($name));
    if ( $oTemplate && $oTemplate['active'] ) {
      $source = $oTemplate['content'];
      $mtime = strtotime($oTemplate['modified_at']);
    } else {
      $source = null;
      $mtime = null;
    }
  }

  /**
  * Fetch a template's modification time from database
  *
  * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the comple template source.
  * @param string $name template name
  * @return integer timestamp (epoch) the template was modified
  */
  protected function fetchTimestamp($name) {
    $templates = $this->template->cachedGetActiveTemplates();
    $mtime = @$templates[$this->fullTemplateName($name)];
    return $mtime ? $mtime : false;
  }

  /**
   * Prepend THEME name to template name to get valid DB primary key
   *
   * @param string $name template name
   */
  protected function fullTemplateName($name) {
    return $this->normalisePath(THEME . "/" . $name);
  }

  /**
   * Normalise a file path string so that it can be checked safely.
   *
   * Attempt to avoid invalid encoding bugs by transcoding the path. Then
   * remove any unnecessary path components including '.', '..' and ''.
   *
   * @param $path string
   * The path to normalise.
   * @return string
   * The path, normalised.
   * @see https://gist.github.com/thsutton/772287
   */
  protected function normalisePath($path) {
    // Process the components
    $parts = explode('/', $path);
    $safe = array();
    foreach ($parts as $idx => $part) {
      if (empty($part) || ('.' == $part)) {
        continue;
      } elseif ('..' == $part) {
        array_pop($safe);
        continue;
      } else {
        $safe[] = $part;
      }
    }
    // Return the "clean" path
    $path = implode(DIRECTORY_SEPARATOR, $safe);
    return $path;
  }

}

class Smarty_Resource_Hybrid extends Smarty_Resource {

  protected $databaseResource;

  protected $fileResource;

  public function __construct($dbResource, $fileResource) {
    $this->databaseResource = $dbResource;
    $this->fileResource = $fileResource;
  }

  /**
   * populate Source Object with meta data from Resource
   *
   * @param Smarty_Template_Source   $source    source object
   * @param Smarty_Internal_Template $_template template object
   */
  public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null) {
    if ( !@$_REQUEST['disable_template_override'] ) {
      $this->databaseResource->populate($source, $_template);
      if( $source->exists )
        return;
    }
    $source->type = 'file';
    return $this->fileResource->populate($source, $_template);
  }

  /**
   * Load template's source into current template object
   *
   * @param Smarty_Template_Source $source source object
   * @return string template source
   * @throws SmartyException if source cannot be loaded
   */
  public function getContent(Smarty_Template_Source $source) {
    try {
      return $this->databaseResource->getContent($source);
    } catch(SmartyException $e) {
      return $this->fileResource->getContent($source);
    }
  }

  /**
   * Determine basename for compiled filename
   *
   * @param Smarty_Template_Source $source source object
   * @return string resource's basename
   */
  public function getBasename(Smarty_Template_Source $source) {
    return $this->fileResource->getBasename($source);
  }

}

// We initialize smarty here
$debug->append('Instantiating Smarty Object', 3);
$smarty = new Smarty;

// Assign our local paths
$debug->append('Define Smarty Paths', 3);
$smarty->template_dir = BASEPATH . 'templates/' . THEME . '/';
$smarty->compile_dir = BASEPATH . 'templates/compile/' . THEME . '/';
$smarty->registerResource('hybrid', new Smarty_Resource_Hybrid(
  new Smarty_Resource_Database($template),
  new Smarty_Internal_Resource_File()
));
$smarty->default_resource_type = "hybrid";
$smarty_cache_key = md5(serialize($_REQUEST) . serialize(@$_SESSION['USERDATA']['id']));

// Optional smarty caching, check Smarty documentation for details
if ($config['smarty']['cache']) {
  $debug->append('Enable smarty cache');
  $smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
  $smarty->cache_lifetime = $config['smarty']['cache_lifetime'];
  $smarty->cache_dir = BASEPATH . "templates/cache/" . THEME;
  $smarty->escape_html = true;
  $smarty->use_sub_dirs = true;
}

// Load custom smarty plugins
require_once(INCLUDE_DIR . '/lib/smarty_plugins/function.acl.php');
?>
