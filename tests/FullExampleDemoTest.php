<?php

require_once __DIR__ . '/FullExampleDemo.php';

/**
 * FullExampleDemoTest
 */
class FullExampleDemoTest extends \PHPUnit_Framework_TestCase
{
  public function testBounceHandling()
  {
    $bounce = new FullExampleDemo();

    $bounce->runBounceHandler();

    // give us the debug-results
    // ----------------------------------------------------------
    self::assertSame('todo', $bounce->getDebug());

  }
}
