<?php

namespace cbednarski\souschef;

require_once(__DIR__ . '/../../../vendor/autoload.php');


class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge()
    {
        $blue = Environment::createFromFile(__DIR__ . '/../../Resources/environment-blue.json');
        $blue->mergeFile(__DIR__ . '/../../Resources/control-script.json');

        $new_blue = Environment::createFromFile(__DIR__ . '/../../Resources/environment-blue-updated.json');

        $this->assertEquals($new_blue, $blue);
    }
}
