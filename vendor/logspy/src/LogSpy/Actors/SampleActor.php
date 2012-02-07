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

namespace LogSpy\Actors;

/**
 * Example actor class.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
class SampleActor implements ActorInterface
{
    protected $config;

    /**
     * Initialize injecting 'actor_config' parameter from configuration.
     *
     * @abstract
     * @param array $config Configuration
     */
    public function initialize(array $config)
    {
        $this->config = $config;
    }

    /**
     * Execute actor
     *
     * @abstract
     * @param array $data Parsed data
     */
    public function act(array $data)
    {
        echo '[' . $this->config['test_echo'] . '] ' . $data[1] . "\n";
    }

}
