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

// php listenErrors.php

$context = new ZMQContext();
$subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
//$subscriber->connect("pgm://127.0.0.1:5556");
//$subscriber->connect("pgm://224.1.1.1:5556");
$subscriber->connect("epgm://eth0;224.1.1.1:5556");
$subscriber->connect("epgm://eth0;224.1.1.1:5557");
$subscriber->connect("epgm://eth0;224.1.1.1:5558");

//$filter = "Oct";
//$subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $filter);

$i = 1;
while (1)
{
    $data = $subscriber->recv();
    $data = json_decode($data, true);
    print_r($data);
    //  echo "($i): $data\n";
    $i++;
}
