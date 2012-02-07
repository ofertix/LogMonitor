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

const PARSE_FAILURE = 0; // Could not parse log line
const PARSE_SUCCESS = 1; // Parse was OK, execute actions
const PARSE_INCOMPLETE = 2; // Parse was OK but it should wait for next line

/**
 * Parser interface.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
interface parserInterface
{
    /**
     * Parse line data and output result.
     *
     * @abstract
     * @param string $input  Input data. It is a line of data.
     * @param array  $output Output array result.
     */
    public function parse($input, &$output);

    /**
     * Method executed if parse fails.
     *
     * @abstract
     * @param string $line Line data from failure
     */
    public function handleParserFailure($line);
}
