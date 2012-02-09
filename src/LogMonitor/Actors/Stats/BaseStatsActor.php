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

namespace LogMonitor\Actors\Stats;

class BaseStatsActor extends \LogMonitor\Actors\BasePublisher
{
    protected function publishStats($output)
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
