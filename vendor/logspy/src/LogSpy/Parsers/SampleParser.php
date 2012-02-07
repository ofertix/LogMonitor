<?php

/*
 * This file is part of the logspy package.
 *
 * (c) Joan Valduvieco <joan.valduvieco@ofertix.com>
 * (c) Jordi Llonch <jordi.llonch@ofertix.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSpy\Parsers;

/**
 * Example parser class.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
class SampleParser implements parserInterface
{
    /**
     * Parse line data and output result.
     *
     * @abstract
     * @param string $input  Input data. It is a line of data.
     * @param array  $output Output array result.
     */
    public function parse($input, &$output)
    {
        $output = sscanf($input, "%s\t%s\t%s\n");
        if (is_array($output)) {
            return PARSE_SUCCESS;
        }
    }

    /**
     * Method executed if parse fails.
     *
     * @abstract
     * @param string $line Line data from failure
     */
    public function handleParserFailure($line)
    {
        echo "ERR: Could not parse: " . trim($line) . "\n";
    }

}
