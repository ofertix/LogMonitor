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
 * Actor interface.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
interface ActorInterface
{
    /**
     * Initialize injecting 'actor_config' parameter from configuration.
     *
     * @abstract
     * @param array $config Configuration
     */
    public function initialize(array $config);

    /**
     * Execute actor
     *
     * @abstract
     * @param array $data Parsed data
     */
    public function act(array $data);
}

