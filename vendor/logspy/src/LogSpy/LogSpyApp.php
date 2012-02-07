<?php

/*
 * This file is part of the logspy package.
 *
 * (c) Joan Valduvieco <joan.valduvieco@ofertix.com>
 * (c) Jordi Llonch <jordi.llonch@ofertix.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSpy;

use LogSpy\LogSpyKernel;
use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
 * LogSpyApp loads configuration and run kernel.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
class LogSpyApp extends \Pimple
{
    protected $kernel = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $app = $this;

        $this['autoloader'] = $this->share(function ()
        {
            $loader = new UniversalClassLoader();
            $loader->register();

            return $loader;
        });

        $this['debug'] = false;
        $this['charset'] = 'UTF-8';
    }

    /**
     * Get options from command line.
     */
    protected function getOptionsFromCommandLine()
    {
        $parameters = array(
            'c:' => 'config' // Specify config file
        );

        $config = array();

        $options = getopt(implode('', array_keys($parameters)), $parameters);
        foreach ($options as $option => $value)
        {
            switch ($option)
            {
                case 'c':
                case 'config':
                    if (is_string($value)) {
                        $this->kernel->loadConfigFile($value);
                    } else
                    {
                        echo "ERR: Illegal value for -c or --config\n";
                    }
                    break;
            }
        }

        // if manual config
        if (!empty($config)) $this->kernel->addConfig('config_1', $config);
    }

    /**
     * Load kernel and run it.
     */
    public function run()
    {
        $this->kernel = new LogSpyKernel();
        $this->getOptionsFromCommandLine();
        $this->kernel->run();
    }
}