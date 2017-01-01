<?php

namespace lixu\BehatPDOExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as BehatExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;


class BehatPDOExtension implements BehatExtension
{

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'pdo';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // 
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('dsn')
                    ->defaultValue('mysql:host=127.0.0.1;dbname=test;charset=UTF8')
                ->end()
                ->scalarNode('username')
                    ->defaultValue('root')
                ->end()
                ->scalarNode('password')
                    ->defaultValue('')
                ->end()
                ->arrayNode('options')
                    ->prototype('scalar')
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $pdo = new \PDO($config['dsn'], $config['username'], $config['password'], $config['options']);

        $definition = new Definition('lixu\BehatPDOExtension\Context\PDOAwareInitializer', [$pdo]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition('pdo.initializer', $definition);
    }

}
