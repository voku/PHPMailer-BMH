<?php

namespace Test\BounceMailHandler;

use BounceMailHandler\BounceMailHandler;

/**
 * @group functional
 */
class MultipleMessageTest extends \PHPUnit_Framework_TestCase
{
    protected function getMailboxPath($localMailboxPath)
    {
        if (!file_exists($localMailboxPath)) {
            throw new \Exception('Local mailbox doesn\'t exist: '.$localMailboxPath);
        }

        $localMailboxPath = realpath($localMailboxPath);
        
        $homeDirectory = getenv('HOME');
        if (strncmp($localMailboxPath, $homeDirectory.DIRECTORY_SEPARATOR, strlen($homeDirectory)+1)) {
            throw new \Exception('Mailbox must be under home directory: '.$homeDirectory);
        }

        return substr($localMailboxPath, strlen($homeDirectory)+1);
    }

    public function testProcessMailbox()
    {
        $testData = array(
            // 'filename' => array(
            //     $fetched, $processed, $unprocessed, $deleted, $moved,
            // ),

            // @todo review
            'bouncehammer/17-messages.eml' => array(
                37, 35, 2, 35, 0,
            ),
            // @todo review
            'bouncehammer/double-messages.eml' => array(
                2, 2, 0, 2, 0,
            ),
        );

        $bmh = new BounceMailHandler;
        $bmh->testMode = true;

        foreach ($testData as $testFile => $expected)
        {
            list($fetched, $processed, $unprocessed, $deleted, $moved) = $expected;

            ob_start();
            $rc = $bmh->openLocal($this->getMailboxPath(__DIR__.'/../../fixtures/'.$testFile));
            ob_end_clean();

            $this->assertTrue($rc, $testFile.': openLocal');

            $bmh->actionFunction =
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
