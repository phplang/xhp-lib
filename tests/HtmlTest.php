<?php

require __DIR__ . '/../vendor/autoload.php';

class HtmlTest extends PHPUnit_Framework_TestCase {
  public function testP() {
    $this->assertEquals("<p></p>", (string)<p/>);
    $this->assertEquals("<p>Hello</p>", (string)<p>Hello</p>);
    $this->assertEquals("<p>Hello <b>World</b></p>", (string)<p>Hello <b>World</b></p>);

    $p = <p/>;
    $p->appendChild(<x:frag><b>Hello</b> World</x:frag>);
    $this->assertEquals("<p><b>Hello</b> World</p>", (string)$p);
  }
}
