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

class NginxErrorParser implements \LogSpy\Parsers\parserInterface
{
    protected $incomplete = false;
    protected $lastLine;
    protected $lastOutput;

    public function parse($input, &$output)
    {
        $output = array();

        // if before parsed was incomplete
        if ($this->incomplete) {
            $input = $this->lastLine . $input;
            $output = $this->lastOutput;
        }

        $r = preg_match('/^(.+) \[error\]/U', $input, $matches);
        if ($r) $output['date'] = date('Y-m-d H:i:s', strtotime($matches[1]));

        $r = preg_match('/\[error\] (.+), client: /U', $input, $matches);
        if ($r) $output['msg'] = $matches[1];

        $r = preg_match('/client: (.+),/U', $input, $matches);
        if ($r) $output['client'] = $matches[1];

        $r = preg_match('/server: (.+),/U', $input, $matches);
        if ($r) $output['server'] = $matches[1];

        $r = preg_match('/request: "(.+)"/U', $input, $matches);
        if ($r) $output['request'] = $matches[1];

        $r = preg_match('/upstream: "(.+)"/U', $input, $matches);
        if ($r) $output['upstream'] = $matches[1];

        $r = preg_match('/server: "(.+)"/U', $input, $matches);
        if ($r) $output['server'] = $matches[1];

        $r = preg_match('/host: "(.+)"/U', $input, $matches);
        if ($r) $output['host'] = $matches[1];

        $r = preg_match('/referrer: "(.+)"/U', $input, $matches);
        if ($r) $output['referrer'] = $matches[1];

        // incomplete parse?
        if (!isset($output['msg'])) {
            $this->incomplete = true;
            $this->lastLine = $input;
            $this->lastOutput = $output;
            return \LogSpy\Parsers\PARSE_INCOMPLETE;
        }

        //    echo $input . "\n";
        //    print_r($output);echo "\n";
        //    flush();

        // parse is complete
        $this->incomplete = false;

        if (count($output)) return \LogSpy\Parsers\PARSE_SUCCESS;
        else return \LogSpy\Parsers\PARSE_FAILURE;
    }

    public function handleParserFailure($line)
    {
        echo "ERR: Could not parse: " . trim($line) . "\n";
    }

}
