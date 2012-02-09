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

class RequestTimeStatsActor extends BaseStatsActor implements \LogSpy\Actors\ActorInterface
{

    public function act(array $data)
    {
        $output = array();

        $url_arr = explode('/', $data['url']);

        // foo
        if ($data['url_host'] == 'www.myweb.com' && preg_match('/^\/foo/', $data['url']) && !preg_match('/^\/foo_unico/', $data['url'])) $output[] = $this->msgStat('foo_app_time_foo', $data);
        // foo
        if (preg_match('/^\/foo\//', $data['url']) && $data['method'] == 'GET' && !preg_match('/^\/foo\/.+\/foo\/.+/', $data['url'])) $output[] = $this->msgStat('foo_app_time_foo', $data);


        $this->publishStats($output);
    }


    protected function msgStat($name, $data)
    {
        return array(
            'name' => $name,
            'ts' => $data['date'],
            'values' => array(
                array('time_year' => $data['time_upstream']),
            )
        );
    }

}
