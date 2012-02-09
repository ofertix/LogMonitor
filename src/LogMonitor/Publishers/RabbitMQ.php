<?php

/*
 * This file is part of the LogMonitor package.
 *
 * (c) Joan Valduvieco <joan.valduvieco@ofertix.com>
 * (c) Jordi Llonch <jordi.llonch@ofertix.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogMonitor\Publishers;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    protected $conn;
    protected $ch;
    protected $exchange;
    protected $queue;

    public function __construct(array $config)
    {
        //        if (!defined('AMQP_DEBUG')) define('AMQP_DEBUG', true);

        // publisher
        $this->conn = new AMQPConnection($config['host'], $config['port'], $config['user'], $config['pass'], $config['vhost']);
        $this->ch = $this->conn->channel();
        $this->exchange = $config['exchange'];
        $this->ch->exchange_declare($this->exchange, 'fanout', false, false, false);
    }

//    public function __destruct()
//    {
//        $this->ch->close();
//        $this->conn->close();
//    }

    public function publish($msg_body)
    {
        $msg = new AMQPMessage($msg_body, array('content_type' => 'application/json', 'delivery-mode' => 2));
        $this->ch->basic_publish($msg, $this->exchange);
    }
}