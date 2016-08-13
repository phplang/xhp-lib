<?php

namespace PhpLang\XhpLib;

class ClassLoader {
  protected $map;

  /**
   * Construct a new autoloader.
   *
   * Takes an optional initial map, as an
   * associative array from ":xhp-prefix" to "path/"
   * similar to Composer's PSR-4 autoloader.
   */
  public function __construct(array $map = array()) {
    $this->map = $map;
  }

  /**
   * Add an additional path mapping
   *
   * @param string $prefix - XHP namespace prefix (e.g. ":foo")
   * @param string $path - Filesystem path (e.g. "tags/")
   */
  public function add($prefix, $path) {
    $this->map[$prefix] = $path;
  }

  /**
   * Remove a previously added mapping
   *
   * @param string $prefix - XHP namespace prefix (e.g. ":foo")
   */
  public function remove($prefix) {
    unset($this->map[$prefix]);
  }

  /**
   * Enable SPL Autoload hook
   *
   * @param bool $prepend - Add loader to start or end
   */
  public function register($prepend = false) {
    spl_autoload_register([$this, 'loadClass'], true, $prepend);
  }

  /**
   * Disable SPL Autoload hook
   */
  public function unregister() {
    spl_autoload_unregister([$this, 'loadClass']);
  }

  /**
   * SPL Autoload callback
   */
  public function loadClass($class) {
    if ($filename = $this->findClass($class)) {
      require $filename;
      return true;
    }

    return false;
  }

  /**
   * Resolve a mangled XHP classname to a filepath
   *
   * @param string $class - Raw (mangled) classname (e.g. "xhp_foo__bar__baz")
   * @return string|false - Path to tag definition, or FALSE on unknown
   */
  public function findClass($class) {
    $xhpname = ':' . self::unmangle($class);
    if ($xhpname === ':') {
      return false;
    }
    foreach ($this->map as $prefix => $path) {
      if (($prefix === $xhpname) ||
         !strncasecmp($prefix . ':', $xhpname, strlen($prefix) + 1)) {
        $suffix = substr($xhpname, strlen($prefix));
        $filename = $path . str_replace(':', '/', $suffix) . '.php';
        if (file_exists($filename)) {
          return $filename;
        }
      }
    }
    return false;
  }

  /**
   * Translate a mangled XHP classname to a user-friendly tag
   *
   * @param string $class - Mangled name (e.g. "xhp_foo__bar__baz")
   * @return string - Demangled name (e.g. "foo:bar:baz")
   */
  public static function unmangle($class) {
    if (strncmp('xhp_', $class, 4)) {
      return false;
    }
    return str_replace('__', ':', substr($class, 4));
  }

  /**
   * Translate a user-friendly tagname to a mangled classname
   *
   * @param string $tag - Demangled name (e.g. "foo:bar:baz")
   * @return string - Mangled name (e.g. "xhp_foo__bar__baz")
   */
  public static function mangle($tag) {
    return 'xhp_' . str_replace(':', '__', $tag);
  }
}
