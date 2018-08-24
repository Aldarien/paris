<?php
namespace App\Service;

use \App\Alias\Model;

class ParisConfig
{
  protected $configuration;

  public function __construct($configuration = null)
  {
    if ($configuration == null) {
      $configuration = [];
      $configuration['default'] = 'mysql';
    }
    $this->configuration = $configuration;
  }
  public function setUp($databases)
  {
    $this->set($databases[$this->configuration['default']]);
    unset($databases[$this->configuration['default']]);
    if (count($databases) > 0) {
      foreach ($databases as $name => $data) {
        $this->set($data);
      }
    }
  }
  protected function set($data)
  {
    switch ($data['driver']) {
      case 'mysql':
        $this->setMySQL($data);
        break;
      case 'sqlite':
        $this->setSQLite($data);
        break;
    }
  }
  protected function setMySQL($data)
  {
    $dsn = 'mysql:host=' . $data['host']['name'];
    if (isset($data['host']['port'])) {
      $dsn .= ';port=' . $data['host']['port'];
    }
    $dsn .= ';dbname=' . $data['name'] . ';charset=utf8';
    \ORM::configure($dsn);
    \ORM::configure('username', $data['user']['name']);
    \ORM::configure('password', $data['user']['password']);
    if (isset($data['short_names']) and $data['short_names']) {
      Model::$short_table_names = true;
    }
  }
}
