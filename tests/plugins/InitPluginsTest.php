<?php
namespace tests\plugins;

use extas\components\packages\entities\EntityRepository;
use extas\components\plugins\construct\PluginInstallConstructDefault;
use extas\components\plugins\install\InstallItem;
use extas\components\plugins\install\InstallPackage;
use extas\components\plugins\Plugin;
use extas\components\plugins\TSnuffPlugins;
use extas\components\plugins\uninstall\UninstallPackage;
use extas\interfaces\IHasIO;
use extas\interfaces\plugins\IPluginInstall;

use extas\components\console\TSnuffConsole;
use extas\components\packages\Installer;
use extas\components\plugins\init\InitPluginsInstaller;
use extas\components\repositories\TSnuffRepository;
use extas\components\plugins\PluginRepository;

use Dotenv\Dotenv;
use extas\interfaces\stages\IStagePluginInstallConstruct;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use tests\plugins\misc\DynamicRepo;
use tests\plugins\misc\PluginConstruct;

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
    use TSnuffPlugins;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->registerSnuffRepos([
            'pluginRepository' => PluginRepository::class,
            'entityRepository' => EntityRepository::class,
            'dynamic' => DynamicRepo::class
        ]);
        $this->createSnuffPlugin(PluginInstallConstructDefault::class, [IStagePluginInstallConstruct::NAME]);
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

        $this->createSnuffPlugin(InstallPackage::class, ['extas.install.package']);
        $this->createSnuffPlugin(InstallItem::class, ['extas.install.item']);

        $installer->installPackages([
            'test/installer' => [
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

    public function testAdoptingToOtherPlugins()
    {
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => PluginConstruct::class,
            Plugin::FIELD__STAGE => IStagePluginInstallConstruct::NAME,
            Plugin::FIELD__PRIORITY => 100
        ]));

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
    }

    public function testDynamic()
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
                IPluginInstall::FIELD__REPOSITORY => 'dynamic',
                IPluginInstall::FIELD__NAME => 'snuff item',
                IPluginInstall::FIELD__SECTION => 'snuff_items'
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

        $this->createSnuffPlugin(InstallPackage::class, ['extas.install.package']);
        $this->createSnuffPlugin(InstallItem::class, ['extas.install.item']);

        $installer->installPackages([
            'test/installer' => [
                'snuff_items' => [
                    ['id' => 'test1'],
                    ['id' => 'test2'],
                    ['id' => 'test1']
                ]
            ]
        ]);
        $outputText = $output->fetch();

        /**
         * One is skipped cause the same id.
         */
        $this->assertCount(2, $this->allSnuffRepos('snuffRepository'), $outputText);
    }

    public function testUninstall()
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

        $this->createSnuffPlugin(InstallPackage::class, ['extas.install.package']);
        $this->createSnuffPlugin(InstallItem::class, ['extas.install.item']);

        $installer->installPackages([
            'test/installer' => [
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

        $uninstaller = new UninstallPackage([
            IHasIO::FIELD__INPUT => $this->getInput([
                'section' => 'snuff_items'
            ]),
            IHasIO::FIELD__OUTPUT => $output
        ]);

        $package = [
            'snuff_items' => [
                ['id' => 'test1'],
                ['id' => 'test2'],
                ['id' => 'test1']
            ]
        ];
        $uninstaller('test', $package);

        $outputText = $output->fetch();
        $this->assertStringContainsString(
            'Uninstalled section snuff_items',
            $outputText,
            'Current output: ' . $outputText
        );
    }
}
