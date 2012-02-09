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

class BaseErrorActor extends \LogMonitor\Actors\BasePublisher
{
    public static $ERROR_TYPE_FATAL = 1;
    public static $ERROR_TYPE_SYNTAX = 2;
    public static $ERROR_TYPE_SQL = 3;
    public static $ERROR_TYPE_SYMFONY = 4;

    public static $ERROR_SEVERITY_CRITICAL = 1;
    public static $ERROR_SEVERITY_WARNING = 2;
    public static $ERROR_SEVERITY_INFO = 3;

    protected function parseError($match, $data, &$output, $error_type, $error_severity)
    {
        if (!isset($data['date'])) $data['date'] = '';
        if (!isset($data['msg'])) $data['msg'] = '';
        if (!isset($data['client'])) $data['client'] = '';
        if (!isset($data['server'])) $data['server'] = '';
        if (!isset($data['request'])) $data['request'] = '';
        if (!isset($data['host'])) $data['host'] = '';
        if (!isset($data['upstream'])) $data['upstream'] = '';
        if (!isset($data['referrer'])) $data['referrer'] = '';

        if (preg_match($match, $data['msg'])) {
            $output[] = array(
                'name' => $this->name, // source
                'error_type' => $error_type,
                'error_severity' => $error_severity,
                'msg' => $data['msg'],
                'date' => $data['date'],
                'client' => $data['client'],
                'server' => $data['server'],
                'request' => $data['request'],
                'upstream' => $data['upstream'],
                'host' => $data['host'],
                'referrer' => $data['referrer'],
            );
        }
    }

    protected function publishErrors($output)
    {
        // publish errors
        foreach ($output as $item)
        {
            $item = json_encode($item);
            $this->publish($item);
            //            print_r($item); // debug
            //            echo "\n";
        }
    }

}
