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
// rm -f test/test.dat.sdf; php logspy.php -f test/test.dat -p \\LogSpy\\Parsers\\SampleParser -a \\LogSpy\\Actors\\SampleActor

require __DIR__ . '/../autoload.php';

use LogSpy\LogSpyApp;

$app = new LogSpyApp();
