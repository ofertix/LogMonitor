<?php

/*
 * This file is part of the parser package.
 *
 * (c) Joan Valduvieco <joan.valduvieco@ofertix.com>
 * (c) Jordi Llonch <jordi.llonch@ofertix.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogMonitor\Publishers;

class ZeroMQ
{
    public function __construct(array $config)
    {
        $context = new \ZMQContext();
        $this->publisher = $context->getSocket(\ZMQ::SOCKET_PUB);
        $this->publisher->bind($config['socket']);
    }

    public function publish($msg)
    {
        $this->publisher->send($msg);
        //      print_r($data); // debug
        //      echo "\n";
    }
}