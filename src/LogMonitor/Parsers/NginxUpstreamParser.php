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

namespace LogMonitor\Parsers;

class NginxUpstreamParser implements \LogSpy\Parsers\parserInterface
{
    public function parse($input, &$output)
    {
        $r = preg_match('/^(.+) - - \[(.+)] "(.+) (.+) (.+)" (.+) upstream (.+) request (.+) \[for (.+) via (.+)\]/U', $input, $matches);
        if ($r) $output['ip'] = $matches[1];
        if ($r) $output['date'] = date('Y-m-d H:i:s', strtotime($matches[2]));
        if ($r) $output['method'] = $matches[3];
        if ($r) $output['url'] = $matches[4];
        if ($r) $output['protocol'] = $matches[5];
        if ($r) $output['status'] = $matches[6];
        if ($r) $output['time_upstream'] = $matches[7]; // php time
        if ($r) $output['time_request'] = $matches[8]; // nginx time
        if ($r) $output['url_host'] = $matches[9];
        if ($r) $output['upstream_server'] = $matches[10];

        if (count($output)) return \LogSpy\Parsers\PARSE_SUCCESS;
        else return \LogSpy\Parsers\PARSE_FAILURE;
    }

    public function handleParserFailure($line)
    {
        echo "ERR: Could not parse: " . trim($line) . "\n";
    }

}
