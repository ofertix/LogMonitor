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

class AccessStatsActor extends BaseStatsActor implements \LogSpy\Actors\ActorInterface
{

    public function act(array $data)
    {
        $output = array();

        if ($data['status'] == '404') $output[] = $this->msgError404($data['date']);

        $this->publishStats($output);
    }

    protected function msgError404($date)
    {
        return array(
            'name' => '404',
            'ts' => $date,
            'values' => array(
                array('counter' => 1)
            )
        );
    }

    protected function msgLogin($date, $time)
    {
        return array(
            'name' => 'login',
            'ts' => $date,
            'values' => array(
                array('time' => $time),
                array('counter' => 1)
            )
        );
    }

}
