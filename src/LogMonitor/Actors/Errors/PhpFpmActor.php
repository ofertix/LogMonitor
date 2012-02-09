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

class PhpFpmActor extends BaseErrorActor implements \LogSpy\Actors\ActorInterface
{
    public function act(array $data)
    {
        $output = array();

        // adapter
        $data['msg'] = '[' . $data['level'] . '] ' . $data['msg'];

        // filter NO errors
        if ($data['level'] == 'NOTICE') $data['msg'] = '';
        if (preg_match('/child .+ exited with code .+ after .+ seconds from start/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/child .+ exited on signal .+ after .+ seconds from start/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/child .+ script .+ terminating/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/child .+ started/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/ptrace\(PEEKDATA\) failed/i', $data['msg'])) $data['msg'] = '';
        //        if (preg_match('/seems busy \(you may need to increase start_servers, or min\/max_spare_servers\), spawning .+ children, there are .+ idle, and .+ total children/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/phpmyadmin/i', $data['msg'])) $data['msg'] = '';

        // parse errors
        $info = false;
        if (preg_match('/seems busy \(you may need to increase start_servers/', $data['msg'])) $info = true;
        if (preg_match('/executing too slow/', $data['msg'])) $info = true;
        if ($info) $this->parseError('/.+/i', $data, $output, self::$ERROR_TYPE_SYNTAX, self::$ERROR_SEVERITY_INFO);
        else $this->parseError('/.+/i', $data, $output, self::$ERROR_TYPE_SYNTAX, self::$ERROR_SEVERITY_WARNING);

        $this->publishErrors($output);
    }
}
