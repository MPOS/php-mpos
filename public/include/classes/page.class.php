<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Page extends Base {
  protected $table = 'pages';

  protected $templates_table = 'page_templates';

  /**
   * Get all pages
   **/
  public function getAll() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY name ASC");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Get all pages in "slug" => "name" format for dropdown
   **/
  public function getAllAsHash() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT name, slug FROM $this->table ORDER BY name ASC");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result()) {
      foreach($result->fetch_all(MYSQLI_ASSOC) as $row) {
        $array[$row['slug']] = $row['name'];
      }
      return $array;
    }
    return false;
  }

  /**
   * Get a specific news entry
   **/
  public function getEntry($slug, $template) {
    $this->debug->append("STA " . __METHOD__, 4);

    $stmt = $this->mysqli->prepare("SELECT p.name, pt.* FROM $this->table AS p, $this->templates_table AS pt WHERE p.slug = pt.slug AND p.slug = ? AND pt.template = ?");
    if ($stmt && $stmt->bind_param('ss', $slug, $template) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    else
      echo $this->mysqli->error;
    return $this->sqlError('E0038');
  }

  /**
   * Update a page entry
   **/
  public function updatePage($slug, $template, $content, $active=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("UPDATE $this->templates_table SET content = ?, active = ?, modified_at = CURRENT_TIMESTAMP WHERE slug = ? AND template = ?");
    if ($stmt && $stmt->bind_param('siss', $content, $active, $slug, $template) && $stmt->execute() && $stmt->affected_rows == 1)
      return true;
    return $this->sqlError('E0037');
  }
}

//Avoid collission with $page/$action variables
$pageModel = new Page();
$pageModel->setDebug($debug);
$pageModel->setMysql($mysqli);
?>
