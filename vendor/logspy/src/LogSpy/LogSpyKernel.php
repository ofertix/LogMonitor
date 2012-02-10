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

namespace LogSpy;

use LogSpy\Actors\actorInterface;
use LogSpy\Parsers\parserInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * LogSpyKernel opens 'tail' processes for every file to parser and send parsed data to every actor configured.
 *
 * @author Joan Valduvieco <joan.valduvieco@ofertix.com>
 * @author Jordi Llonch <jordi.llonch@ofertix.com>
 */
class LogSpyKernel
{
    protected $configs = array();
    protected $map_resources = array();
    protected $stop = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        \pcntl_signal(SIGTERM, array($this, 'shutdown'));
        \pcntl_signal(SIGINT, array($this, 'shutdown'));
    }

    /**
     * Add config part.
     *
     * @param string $name   Config name part
     * @param array  $config Configuration
     */
    public function addConfig($name, $config)
    {
        $this->checkConfig($config);

        $this->configs[$name] = $config;
        $this->configs[$name]['parserObj'] = new $this->configs[$name]['parser']();
        foreach ($this->configs[$name]['actors'] as $actor)
        {
            $obj = new $actor();
            $obj->initialize($this->configs[$name]['actor_config']);
            $this->configs[$name]['actorsObj'][] = $obj;
        }
    }

    /**
     * Load configuration from yaml file.
     *
     * @param string $fileName Path to file
     */
    public function loadConfigFile($fileName)
    {
        $configs = Yaml::parse($fileName);
        foreach ($configs as $name => $config) $this->addConfig($name, $config);
    }

    /**
     * Check if configuration part is right.
     *
     * @param  array $config Configuration
     * @return bool
     *
     * @throws \Exception When an error found
     */
    protected function checkConfig($config)
    {
        if (!isset($config['file'])) throw new \Exception('Config error: \'file\'config parameter required.');
        if (!isset($config['parser'])) throw new \Exception('Config error: \'parser\' config parameter required.');
        if (!isset($config['actors'])) throw new \Exception('Config error: \'actors\' config parameter required.');

        if (!file_exists($config['file'])) throw new \Exception('ERR: \'' . $config['file'] . '\' file does not exist.');

        return true;
    }

    /**
     * Save all files pointer.
     */
    protected function saveStatusAll()
    {
        foreach ($this->configs as $name => $config)
        {
            $this->saveStatus($config['file'], $config['file_stat']);
        }
    }

    /**
     * Save file pointer.
     *
     * @param string $file     File path
     * @param string $fileStat File stat path
     */
    protected function saveStatus($file, $fileStat)
    {
        // Save new data for the next run
        clearstatcache();
        $statDataCurrent = array_slice(stat($file), 13);

        file_put_contents($fileStat, json_encode($statDataCurrent));
    }

    /**
     * Save all status and shutdown process.
     */
    protected function shutdown()
    {
        $this->stop = true;
        $this->saveStatusAll();
    }

    /**
     * Start core process.
     */
    public function run()
    {
        $pipes = array();
        $process = array();
        foreach ($this->configs as $name => $config)
        {
            list($pipes[$name], $process[$name]) = $this->openFile($name, $config);
        }

        $this->doLoop($pipes);

        $this->closeFiles($pipes, $process);
    }

    /**
     * Open 'tail' process.
     *
     * @param string $name   Name uses to map resource
     * @param array  $config Configuration
     * @return array Pipe and Process
     */
    protected function openFile($name, $config)
    {
        $file = $config['file'];
        $fileStat = $config['file_stat'];

        $seekPos = 0; // Position to start reading.
        // Some protections. FIXME: We should move this to fileFormat

        // Gather current file stat Data
        $statDataCurrent = array_slice(stat($file), 13);

        if (file_exists($fileStat)) {
            $statDataLast = json_decode(file_get_contents($fileStat), true);

            if ($statDataLast['size'] != $statDataCurrent['size'] OR $statDataLast['atime'] != $statDataCurrent['atime']) {
                if ($statDataLast['size'] > $statDataCurrent['size']) {
                    $seekPos = 0; // Reread from the begining. Probably the file has been rotated.
                }
            } else
            {
                $seekPos = $statDataLast['size'];
            }
        }

        // Save new data for the next run
        $this->saveStatus($file, $fileStat);

        $cmdLine = "tail";
        // Seek to our current position
        $cmdLine .= " -c +" . $seekPos;
        $cmdLine .= " -F " . $file;
        // Start reading/parsing
        //    $handle = popen($cmdLine, "r");
        $descriptorspec = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stdout
            2 => array("pipe", "w") // stderr ?? instead of a file
        );
        $process = proc_open($cmdLine, $descriptorspec, $pipes);
        if (!is_resource($process)) {
            die("ERR: Could not spawn: $cmdLine. Aborting\n");
        }

        // map resource id to config
        $this->map_resources[(int)$pipes[1]] = $name;

        return array($pipes, $process);
    }

    /**
     * Read data and process.
     *
     * @param array $pipes Pipes where read data
     */
    protected function doLoop($pipes)
    {
        $read_orig = array();
        foreach ($pipes as $pipe) $read_orig[] = $pipe[1];
        while (!$this->stop)
        {
            try
            {

                /* Prepare the read array */
                $write = NULL;
                $except = NULL;
                \pcntl_signal_dispatch();
                $read = $read_orig;
                if (false === ($num_changed_streams = @stream_select($read, $write, $except, 1))) {
                    /* Error handling */
                    continue;
                } elseif ($num_changed_streams > 0)
                {
                    //        $line = stream_get_line($pipes[1], 4096, "\n");
                    //        $line .= "{newline}\n";
                }


                foreach ($read as $resource)
                {
                    $name = $this->map_resources[(int)$resource];
                    stream_set_blocking($pipes[$name][0], 0);
                    stream_set_blocking($pipes[$name][1], 0);
                    $line = stream_get_line($pipes[$name][1], 4096, "\n");
                    //      else continue;


                    if ($line === false) continue; // An error has ocurred while reading
                    if ($line === "" || $line == "\n") continue; // No data

                    // Call the parser
                    //      foreach (explode("\n", $data) as $line)
                    //      {
                    $parsed_data = array();
                    $result = $this->configs[$name]['parserObj']->parse($line, $parsed_data);

                    // Process or not the parsed data
                    if ($result == Parsers\PARSE_SUCCESS) {
                        foreach ($this->configs[$name]['actorsObj'] as $obj)
                        {
                            $obj->act($parsed_data);
                        }
                    } else if ($result == Parsers\PARSE_FAILURE) {
                        $this->configs[$name]['parserObj']->handleParserFailure($line);
                        continue;
                    }
                }
            }
            catch (\Exception $e)
            {
                $this->stop = true;
            }
        }
    }

    /**
     * Close files resources.
     *
     * This method kill every 'tail' process
     *
     * @param array $pipes   Pipes resources
     * @param array $process Process resources
     */
    protected function closeFiles($pipes, $process)
    {
        foreach ($this->configs as $name => $config)
        {
            fclose($pipes[$name][0]);
            fclose($pipes[$name][1]);

            $s = proc_get_status($process[$name]);
            posix_kill($s['pid'], SIGKILL);

            proc_close($process[$name]);
        }
    }

}
