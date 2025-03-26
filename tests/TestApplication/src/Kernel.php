<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use VisualCraft\RestBaseBundle\VisualCraftRestBaseBundle;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class Kernel extends SymfonyKernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }

    #[\Override]
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new VisualCraftRestBaseBundle(),
            new SecurityBundle(),
        ];
    }

    #[\Override]
    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/com.github.visual-craft.rest-base-bundle/tests/var/' . $this->environment . '/cache';
    }

    #[\Override]
    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/com.github.visual-craft.rest-base-bundle/tests/var/' . $this->environment . '/log';
    }

    #[\Override]
    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->getProjectDir() . '/config/routes.php');
    }

    /** @psalm-suppress UnusedParam */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $loader->load($this->getProjectDir() . '/config/{packages}/*.php', 'glob');
        $loader->load($this->getProjectDir() . '/config/{packages}/' . $this->environment . '/*.php', 'glob');
        $loader->load($this->getProjectDir() . '/config/{services}.php', 'glob');
        $loader->load($this->getProjectDir() . '/config/{services}_' . $this->environment . '.php', 'glob');
    }
}
