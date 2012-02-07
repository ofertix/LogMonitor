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

namespace LogMonitor\Actors\Errors;

class ErrorSymfonyActor extends BaseErrorActor implements \LogSpy\Actors\ActorInterface
{
    public function act(array $data)
    {
        $output = array();

        $this->parseError('/The template .+ does not exist/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);
        $this->parseError('/Validation failed in class/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);
        $this->parseError('/Error params/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);
        $this->parseError('/Widget .+ does not exist/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);
        $this->parseError('/The module ".*" is not enabled/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);
        $this->parseError('/The route ".*" does not exist/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);

        $this->publishErrors($output);
    }
}
