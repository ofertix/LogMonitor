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

// usage:
//
// rm -f test/*.sdf; php app/log_monitor.php -c app/config/test_backoffice_log.yml
// rm -f test/*.sdf; php app/log_monitor.php -c app/config/test_error_access_log.yml
// rm -f test/*.sdf; php app/log_monitor.php -c app/config/test_phpfpm_error_log.yml
// rm -f test/*.sdf; php app/log_monitor.php -c app/config/test_upstream_log.yml

require __DIR__ . '/../vendor/logspy/logspy.php';

// register my classes
$app['autoloader']->registerNamespaces(array(
    'LogMonitor' => __DIR__ . "/../src/",
    'PhpAmqpLib' => __DIR__ . '/../vendor/php-amqplib/',
));
$app['autoloader']->register();

// run app
$app->run();
