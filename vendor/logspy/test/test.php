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

// example:
// rm -f test.dat.sdf; rm -f test2.dat.sdf; php test.php -c config.yml
//
// or
//
// rm -f test.dat.sdf; php test.php -f test.dat -p \\LogSpy\\Parsers\\SampleParser -a \\LogSpy\\Actors\\SampleActor
// rm -f test2.dat.sdf; php test.php -f test2.dat -p \\LogSpy\\Parsers\\SampleParser -a \\LogSpy\\Actors\\SampleActor

require __DIR__ . '/../logspy.php';
$app->run();
