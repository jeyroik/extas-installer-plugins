<?php
namespace tests\plugins;

use extas\interfaces\plugins\IPluginInstall;

use extas\components\console\TSnuffConsole;
use extas\components\packages\Installer;
use extas\components\plugins\init\InitPluginsInstaller;
use extas\components\repositories\TSnuffRepository;
use extas\components\plugins\PluginRepository;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class InitPluginsTest
 *
 * @package tests\plugins
 * @author jeyroik <jeyroik@gmail.com>
 */
class InitPluginsTest extends TestCase
{
    use TSnuffConsole;
    use TSnuffRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->registerSnuffRepos(['pluginRepository' => PluginRepository::class]);
    }

    protected function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testInitializing()
    {
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $plugin = new InitPluginsInstaller([
            InitPluginsInstaller::FIELD__INPUT => $this->getInput(),
            InitPluginsInstaller::FIELD__OUTPUT => $output
        ]);
        $plugin('extas.init.section.plugins_install', [
            [
                IPluginInstall::FIELD__REPOSITORY => 'snuffRepository',
                IPluginInstall::FIELD__NAME => 'snuff item'
            ]
        ]);
        $outputText = $output->fetch();
        $this->assertStringContainsString(
            'Created install plugin for snuff_items',
            $outputText,
            'Current output: ' . $outputText
        );
        $this->assertStringContainsString(
            'Created uninstall plugin for snuff_items',
            $outputText,
            'Current output: ' . $outputText
        );

        $installer = new Installer([
            Installer::FIELD__INPUT => $this->getInput(),
            Installer::FIELD__OUTPUT => $output
        ]);

        $installer->installPackages([
            [
                'snuff_items' => [
                    ['id' => 'test1'],
                    ['id' => 'test2'],
                    ['id' => 'test1']
                ]
            ]
        ]);

        /**
         * One is skipped cause the same id.
         */
        $this->assertCount(2, $this->allSnuffRepos('snuffRepository'));
    }
}
