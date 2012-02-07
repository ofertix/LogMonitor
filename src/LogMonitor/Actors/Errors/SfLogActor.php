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

class SfLogActor extends BaseErrorActor implements \LogSpy\Actors\ActorInterface
{
    public function act(array $data)
    {
        $output = array();

        // adapter
        $data['msg'] = '[' . $data['level'] . '] ' . $data['msg'];

        // filter NO errors

        // backoffice
        if (preg_match('/Dynamic inheritance detected for class/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/Autofiltering/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/Recompiling/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/is not a valid stream resource/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/I can break rules, too. Goodbye/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/Bad response for MAIL FROM/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/htmlspecialchars\(\)\: Invalid multibyte sequence in argument at/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/Invalid argument supplied for foreach/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/Cannot modify header information/i', $data['msg'])) $data['msg'] = '';

        // scripts
        if (preg_match('/fclose\(\): .* is not a valid stream resource/i', $data['msg'])) $data['msg'] = '';
        if (preg_match('/fwrite\(\): .* is not a valid stream resource/i', $data['msg'])) $data['msg'] = '';

        // parse errors
        $this->parseError('/.+/i', $data, $output, self::$ERROR_TYPE_SYMFONY, self::$ERROR_SEVERITY_WARNING);

        $this->publishErrors($output);
    }
}
