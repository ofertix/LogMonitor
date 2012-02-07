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

class PhpFpmParser implements \LogSpy\Parsers\parserInterface
{
    public function parse($input, &$output)
    {
        $r = preg_match('/^(.+)\..+\[(.+)] (.+)/', $input, $matches);
        if ($r) {
            $output['date'] = date('Y-m-d H:i:s', strtotime($matches[1]));
            $output['level'] = $matches[2];
            $output['msg'] = $matches[3];
        }

        if (count($output)) return \LogSpy\Parsers\PARSE_SUCCESS;
        else return \LogSpy\Parsers\PARSE_FAILURE;
    }

    public function handleParserFailure($line)
    {
        echo "ERR: Could not parse: " . trim($line) . "\n";
    }

}
