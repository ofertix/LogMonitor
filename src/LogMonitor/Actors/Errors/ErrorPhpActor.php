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

namespace LogMonitor\Actors\Errors;

class ErrorPhpActor extends BaseErrorActor implements \LogSpy\Actors\ActorInterface
{
    public function act(array $data)
    {
        $output = array();

        // parse errors
        $this->parseError('/Fatal error/i', $data, $output, self::$ERROR_TYPE_FATAL, self::$ERROR_SEVERITY_CRITICAL);
        $this->parseError('/Syntax error/i', $data, $output, self::$ERROR_TYPE_FATAL, self::$ERROR_SEVERITY_CRITICAL);

        $this->publishErrors($output);
    }
}
