<?php

require_once __DIR__.'/../BounceMailHandler.php';

/**
 * @group functional
 */
class MultipleMessageTest extends PHPUnit_Framework_TestCase
{
    public function testProcessMailbox()
    {
        $testData = array(
            // 'filename' => array(
            //     $fetched, $processed, $unprocessed, $deleted, $moved,
            // ),

            // @todo review
            'bouncehammer/17-messages.eml' => array(
                37, 34, 3, 34, 0,
            ),
            // @todo review
            'bouncehammer/double-messages.eml' => array(
                2, 2, 0, 2, 0,
            ),
        );

        $bmh = new \BounceMailHandler;
        $bmh->testmode = true;

        foreach ($testData as $testFile => $expected)
        {
            list($fetched, $processed, $unprocessed, $deleted, $moved) = $expected;

            ob_start();
            $rc = $bmh->openLocal(__DIR__.'/Fixtures/'.$testFile);
            ob_end_clean();

            $this->assertTrue($rc, $testFile.': openLocal');

            $bmh->action_function =
                function($msgnum, $bounceType, $email, $subject, $xheader, $remove, $ruleNo, $ruleCat, $totalFetched, $body)
                    use ($expected)
                {
                    return ($remove === true || $remove === 1);
                };

            ob_start();
            $rc = $bmh->processMailbox();
            $output = ob_get_contents();
            ob_end_clean();

            $this->assertTrue($rc, $testFile.': processMailbox');

            preg_match('/Read: ([0-9]+) messages/', $output, $matches);
            $this->assertEquals($fetched, $matches[1], $testFile.': messages read');

            preg_match('/([0-9]+) action taken/', $output, $matches);
            $this->assertEquals($processed, $matches[1], $testFile.': action taken');

            preg_match('/([0-9]+) no action taken/', $output, $matches);
            $this->assertEquals($unprocessed, $matches[1], $testFile.': no action taken');

            preg_match('/([0-9]+) messages deleted/', $output, $matches);
            $this->assertEquals($deleted, $matches[1], $testFile.': messages deleted');

            preg_match('/([0-9]+) messages moved/', $output, $matches);
            $this->assertEquals($moved, $matches[1], $testFile.': messages moved');
        }
    }
}
