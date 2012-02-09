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

require __DIR__ . '/../vendor/logspy/autoload.php';
$loader->registerNamespaces(array(
    'PhpAmqpLib' => __DIR__ . '/../vendor/php-amqplib/',
));

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

//        if (!defined('AMQP_DEBUG')) define('AMQP_DEBUG', true);
$config['host'] = 'localhost';
$config['port'] = 5672;
$config['user'] = 'guest';
$config['pass'] = 'guest';
$config['vhost'] = '/';
$config['errors']['exchange'] = 'myproject_errors';
$config['stats']['exchange'] = 'myproject_stats';


// subscriber
$conn = new AMQPConnection($config['host'], $config['port'], $config['user'], $config['pass'], $config['vhost']);
$ch = $conn->channel();

list($queue_name, ,) = $ch->queue_declare("", false, false, true, false);

$ch->exchange_declare($config['stats']['exchange'], 'fanout', false, false, false);
$ch->exchange_declare($config['errors']['exchange'], 'fanout', false, false, false);
$ch->queue_bind($queue_name, $config['stats']['exchange']);
$ch->queue_bind($queue_name, $config['errors']['exchange']);

$ch->basic_consume($queue_name, 'consumer', false, true, false, false, 'processMessage');

// Loop as long as the channel has callbacks registered
while (count($ch->callbacks))
{
    $ch->wait();
}


function processMessage($msg)
{
    echo $msg->body . "\n";
}
