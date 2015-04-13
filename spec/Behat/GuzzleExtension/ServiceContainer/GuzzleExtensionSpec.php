<?php

namespace spec\Behat\GuzzleExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\GuzzleExtension\ServiceContainer\GuzzleExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class GuzzleExtensionSpec extends ObjectBehavior
{
    public function it_is_a_testwork_extension()
    {
        $this->shouldHaveType('Behat\Testwork\ServiceContainer\Extension');
    }

    public function it_has_specific_configuration(
        ArrayNodeDefinition $builder,
        NodeBuilder $nodeBuilder,
        ScalarNodeDefinition $node
    ) {
        $builder->addDefaultsIfNotSet()
            ->shouldBeCalled()
            ->willReturn($builder);

        $builder->children()
            ->shouldBeCalled()
            ->willReturn($nodeBuilder);

        $nodeBuilder->scalarNode('base_url')
            ->shouldBeCalled()
            ->willReturn($node);

        $nodeBuilder->scalarNode('service_descriptions')
            ->shouldBeCalled()
            ->willReturn($node);

        $node->defaultNull()
            ->shouldBeCalled()
            ->willReturn($node);

        $node->end()
            ->shouldBeCalled()
            ->willReturn($nodeBuilder);

        $nodeBuilder->end()
            ->shouldBeCalled()
            ->willReturn($builder);

        $builder->end()
            ->shouldBeCalled()
            ->willReturn($builder);

        $this->configure($builder);
    }

    public function it_is_named_guzzle()
    {
        $this->getConfigKey()->shouldReturn('guzzle');
    }

    public function it_does_not_initialize_anything()
    {
        $this->initialize(new ExtensionManager(array()));
    }

    public function it_loads_a_config(ContainerBuilder $container)
    {
        $config = array(
            'base_url' => 'https://api.travis-ci.org'
        );

        $container->getParameter('guzzle.base_url')
            ->shouldBeCalled();

        $container->getParameter('guzzle.parameters')
            ->shouldBeCalled();

        $container->setParameter('guzzle.base_url', $config['base_url'])
            ->shouldBeCalled();

        $container->setParameter('guzzle.parameters', $config)
            ->shouldBeCalled();

        $container->setDefinition(
            GuzzleExtension::GUZZLE_CLIENT_ID,
            new Definition(
                'Guzzle\Service\Client',
                array(
                    'baseUrl' => null,
                    'config' => null
                )
            )
        )->shouldBeCalled();

        $definition = new Definition(
            'Behat\GuzzleExtension\Context\Initializer\GuzzleAwareInitializer',
            array(
                new Reference(GuzzleExtension::GUZZLE_CLIENT_ID),
                '%guzzle.parameters%',
            )
        );
        $definition->addTag(
            ContextExtension::INITIALIZER_TAG,
            array('priority' => 0)
        );

        $container->setDefinition(
            'guzzle.context_initializer',
            $definition
        )->shouldBeCalled();

        $this->load($container, $config);
    }

    public function it_does_not_process_anything(ContainerBuilder $container)
    {
        $this->process($container);
    }
}
