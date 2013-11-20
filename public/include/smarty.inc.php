<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

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
  protected $page;

  public function __construct($page) {
    $this->page = $page;
  }
  /**
   * Fetch a template and its modification time from database
   *
   * @param string $name template name
   * @param string $source template source
   * @param integer $mtime template modification timestamp (epoch)
   * @return void
   */
  protected function fetch($name, &$source, &$mtime)
  {
    //TODO action support
    $page = explode('/',$name)[0];

    $oPage = $this->page->getActiveTemplate($page);
    if ( $oPage ) {
      $source = $oPage['content'];
      $mtime = strtotime($oPage['modified_at']);
    } else {
      $source = null;
      $mtime = null;
    }
  }

}

class Smarty_Resource_Hybrid extends Smarty_Resource {

  protected $databaseResource;

  protected $fileResource;

  protected $stringResource;

  public function __construct($dbResource, $fileResource, $stringResource) {
    $this->databaseResource = $dbResource;
    $this->fileResource = $fileResource;
    $this->stringResource = $stringResource;
  }

  /**
   * populate Source Object with meta data from Resource
   *
   * @param Smarty_Template_Source   $source    source object
   * @param Smarty_Internal_Template $_template template object
   */
  public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
  {
    if ( $this->isEmpty($source) ) {
      $source->name = '';
      $source->type = 'string';
      return $this->stringResource->populate($source, $_template);
    }
    $this->prepareSource($source);
    $this->databaseResource->populate($source, $_template);
    if ( !$source->exists ) {
      return $this->fileResource->populate($source, $_template);
    }
  }

  /**
   * Load template's source into current template object
   *
   * @param Smarty_Template_Source $source source object
   * @return string template source
   * @throws SmartyException if source cannot be loaded
   */
  public function getContent(Smarty_Template_Source $source)
  {
    if ( $this->isEmpty($source) )
      return '';
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
  public function getBasename(Smarty_Template_Source $source)
  {
    if ( $this->isEmpty($source) )
      return '';
    return $this->fileResource->getBasename($source);
  }

  /**
   * Add $page and $action prefix for template name
   * @param Smarty_Template_Source $source source object
   */
  protected function prepareSource($source) {
    //TODO Dependency Injection
    global $page;
    global $action;

    $source->name = $page."/".$action."/".$source->name;
    $source->resource = $source->type.":".$source->name;
  }

  protected function isEmpty($source) {
    return $source->name == '' || $source->name == 'empty';
  }

}

// We initialize smarty here
$debug->append('Instantiating Smarty Object', 3);
$smarty = new Smarty;

// Assign our local paths
$debug->append('Define Smarty Paths', 3);
$smarty->template_dir = BASEPATH . 'templates/' . THEME . '/';
$smarty->compile_dir = BASEPATH . 'templates/compile/';
$smarty->registerResource('hybrid', new Smarty_Resource_Hybrid(
  new Smarty_Resource_Database($pageModel),
  new Smarty_Internal_Resource_File(),
  new Smarty_Internal_Resource_String()
));
$smarty_cache_key = md5(serialize($_REQUEST) . serialize(@$_SESSION['USERDATA']['id']));

// Optional smarty caching, check Smarty documentation for details
if ($config['smarty']['cache']) {
  $debug->append('Enable smarty cache');
  $smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
  $smarty->cache_lifetime = $config['smarty']['cache_lifetime'];
  $smarty->cache_dir = BASEPATH . "templates/cache";
  $smarty->use_sub_dirs = true;
}
?>
