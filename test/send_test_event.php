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

$context = new \ZMQContext();
$publisher = $context->getSocket(\ZMQ::SOCKET_PUB);
$publisher->bind('epgm://eth0;224.1.1.1:5558');
$data = array(
    'ts' => date('Y-m-d H:i:s'),
    'event' => 'event_test_' . time()
);
$data = json_encode($data);
$publisher->send($data);
