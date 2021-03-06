<?php

namespace ApiGen\Tests\Command;

use ApiGen\Configuration\Exceptions\ConfigurationException;
use ApiGen\Console\Command\GenerateCommand;
use ApiGen\Tests\ContainerAwareTestCase;
use ApiGen\Tests\MethodInvoker;
use ApiGen\Utils\FileSystem;

class GenerateCommandPrepareOptionsTest extends ContainerAwareTestCase
{

    /**
     * @var GenerateCommand
     */
    private $generateCommand;

    /**
     * @var FileSystem
     */
    private $fileSystem;


    protected function setUp()
    {
        $this->generateCommand = $this->container->getByType(GenerateCommand::class);
        $this->fileSystem = new FileSystem;
    }


    public function testPrepareOptionsDestinationNotSet()
    {
        $this->setExpectedException(ConfigurationException::class, 'Destination is not set');
        MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => '...'
        ]]);
    }


    public function testPrepareOptionsSourceNotSet()
    {

        $this->setExpectedException(ConfigurationException::class, 'Source is not set');
        MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => '...',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api'
        ]]);
    }


    public function testPrepareOptions()
    {
        $options = MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => '...',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api',
            'source' => __DIR__
        ]]);

        $this->assertSame($this->fileSystem->getAbsolutePath(TEMP_DIR . '/api'), $options['destination']);
    }


    public function testPrepareOptionsConfigPriority()
    {
        $configAndDestinationOptions = [
            'config' => __DIR__ . DIRECTORY_SEPARATOR . 'apigen.neon',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api',
            'source' => __DIR__
        ];

        $options = MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [
            $configAndDestinationOptions
        ]);
        $this->assertSame($this->fileSystem->getAbsolutePath(__DIR__ . '/../../../src'), $options['source'][0]);
    }


    public function testPrepareOptionsMergeIsCorrect()
    {
        $options = MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => __DIR__ . DIRECTORY_SEPARATOR . 'apigen.neon',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api',
            'download' => false
        ]]);

        $this->assertSame(['public', 'protected', 'private'], $options['accessLevels']);
        $this->assertSame('http://apigen.org', $options['baseUrl']);
        $this->assertTrue($options['download']);
        $this->assertSame('packages', $options['groups']);
        $this->assertFalse($options['todo']);
    }


    public function testPrepareOptionsMergeIsCorrectFromYamlConfig()
    {
        $optionsYaml = MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => __DIR__ . DIRECTORY_SEPARATOR . 'apigen.yml',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api',
            'download' => false
        ]]);

        $optionsNeon = MethodInvoker::callMethodOnObject($this->generateCommand, 'prepareOptions', [[
            'config' => __DIR__ . DIRECTORY_SEPARATOR . 'apigen.neon',
            'destination' => TEMP_DIR . DIRECTORY_SEPARATOR . 'api',
            'download' => false
        ]]);

        $this->assertSame($optionsNeon, $optionsYaml);
    }


    public function testLoadOptionsFromConfig()
    {
        $options['config'] = '...';
        file_put_contents(getcwd() . DIRECTORY_SEPARATOR . 'apigen.neon.dist', 'debug: true');

        $options = MethodInvoker::callMethodOnObject($this->generateCommand, 'loadOptionsFromConfig', [$options]);
        $this->assertSame([
            'config' => '...',
            'debug' => true
        ], $options);

        unlink(getcwd() . DIRECTORY_SEPARATOR . 'apigen.neon.dist');
    }
}
