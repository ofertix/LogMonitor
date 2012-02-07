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

class ErrorSqlActor extends BaseErrorActor implements \LogSpy\Actors\ActorInterface
{
    public function act(array $data)
    {
        $output = array();

        $this->parseError('/SQLSTATE/i', $data, $output, self::$ERROR_TYPE_SQL, self::$ERROR_SEVERITY_CRITICAL);

        $this->parseError('/You must specify the value to findBy/i', $data, $output, self::$ERROR_TYPE_SQL, self::$ERROR_SEVERITY_INFO);

        $this->parseError('/Duplicate alias \'.*\' in query/i', $data, $output, self::$ERROR_TYPE_SQL, self::$ERROR_SEVERITY_WARNING);

        $this->publishErrors($output);
    }
}
