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

namespace LogMonitor\Parsers;

class NginxAccessParser implements \LogSpy\Parsers\parserInterface
{
    public function parse($input, &$output)
    {
        $r = preg_match('/^(.+) - - \[(.+)] "(.+)" (.+) (.+) "(.+)" "(.+)"/U', $input, $matches);
        if ($r) $output['ip'] = $matches[1];
        if ($r) $output['date'] = date('Y-m-d H:i:s', strtotime($matches[2]));
        if ($r) $output['get'] = $matches[3];
        if ($r) $output['status'] = $matches[4];
        if ($r) $output['size'] = $matches[5];
        if ($r) $output['referrer'] = $matches[6];
        if ($r) $output['user_agent'] = $matches[7];

        if (count($output)) return \LogSpy\Parsers\PARSE_SUCCESS;
        else return \LogSpy\Parsers\PARSE_FAILURE;
    }

    public function handleParserFailure($line)
    {
        echo "ERR: Could not parse: " . trim($line) . "\n";
    }

}
