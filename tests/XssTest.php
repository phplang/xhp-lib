<?php

require __DIR__ . '/../vendor/autoload.php';

class XssTest extends PHPUnit_Framework_TestCase {
  public function testEscape() {
    $evil = '"><script>alert(1);</script>';
    $this->assertEquals('<p>&quot;&gt;&lt;script&gt;alert(1);&lt;/script&gt;</p>', (string)<p>{$evil}</p>);
    $this->assertEquals('<p id="&quot;&gt;&lt;script&gt;alert(1);&lt;/script&gt;"></p>', (string)<p id={$evil} />);
  }
}
