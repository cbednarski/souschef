<?php

namespace cbednarski\souschef;

require_once(__DIR__ . '/../../../vendor/autoload.php');


class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDataAsJson()
    {
        $blue_file = __DIR__ . '/../../Resources/environment-blue.json';
        $blue = Environment::createFromFile($blue_file);
        $this->assertEquals(json_encode(json_decode(file_get_contents($blue_file))), $blue->getDataAsJson());
    }

    public function testMerge()
    {
        $blue = Environment::createFromFile(__DIR__ . '/../../Resources/environment-blue.json');
        $blue->applyPatchfile(__DIR__ . '/../../Resources/control-script.json');

        $new_blue = Environment::createFromFile(__DIR__ . '/../../Resources/environment-blue-updated.json');

        $this->assertEquals($new_blue, $blue);

        $this->assertEquals($new_blue->getDataAsJson(), $blue->getDataAsJson());
    }
}
