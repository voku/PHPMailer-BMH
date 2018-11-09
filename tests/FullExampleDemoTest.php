<?php

require_once __DIR__ . '/FullExampleDemo.php';

/**
 * FullExampleDemoTest
 */
class FullExampleDemoTest extends \PHPUnit\Framework\TestCase
{
  public function testBounceHandling()
  {
    $bounce = new FullExampleDemo();

    $bounce->runBounceHandler();

    // give us the debug-results
    // ----------------------------------------------------------
    self::assertSame('1: 0000 | unrecognized | none | <span style="color:gray;">not deleted</span> | Robert Hafner <tedium@gmail.com> | Are you looking for tedivm? Re: With CC<br />
2: 0000 | unrecognized | none | <span style="color:gray;">not deleted</span> | tedivm@tedivm.com | Welcome<br />
3: 0000 | unrecognized | none | <span style="color:gray;">not deleted</span> | Robert Hafner <tedivm@tedivm.com> | Formatted<br />
', $bounce->getDebug());

  }
}
