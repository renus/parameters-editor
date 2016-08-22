<?php

namespace Renus\ParametersEditorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('renus_parameters_editor');

        $rootNode
            ->children()
                ->booleanNode('all_parameters')
                    ->defaultFalse()
                ->end()
                ->scalarNode('default_keyword')
                    ->defaultValue('editable')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
