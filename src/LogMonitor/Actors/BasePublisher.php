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

namespace LogMonitor\Actors;

class BasePublisher
{
    protected $publishers;
    protected $name;

    public function initialize(array $configs)
    {
        $this->name = $configs['name'];

        foreach ($configs['publishers'] as $item)
        {
            $this->publishers[] = new $item['class']($item['config']);
        }
    }

    public function publish($msg)
    {
        foreach ($this->publishers as $publisher) $publisher->publish($msg);
    }

}
