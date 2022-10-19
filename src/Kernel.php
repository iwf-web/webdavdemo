<?php

namespace App;

use Exception;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * Override default symfony log dir ('log') in order to keep logs persistent in our docker environment
     */
    public function getLogDir(): string
    {
        return $this->getProjectDir().'/var/logs';
    }

    /**
     * phpunit performance optimization
     *
     * @see http://kriswallsmith.net/post/27979797907/get-fast-an-easy-symfony2-phpunit-optimization
     *
     * @throws Exception
     */
    protected function initializeContainer()
    {
        static $first = true;

        if ('test' !== $this->getEnvironment()) {
            parent::initializeContainer();
            return;
        }
        $debug = $this->debug;

        if (!$first) {
            // disable debug mode on all but the first initialization
            $this->debug = false;
        }

        // will not work with --process-isolation
        $first = false;

        try {
            parent::initializeContainer();
        } catch (\Exception $e) {
            $this->debug = $debug;
            throw $e;
        }

        $this->debug = $debug;

    }
}
