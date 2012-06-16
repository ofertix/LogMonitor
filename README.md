What is "LogMonitor"?
=====================

LogMonitor is the component in charge of parse all kind of log files and publishes errors and stats to the configured channel. The published messages could be collected by the "Collector" component or the "WebUIMonitor" component.

Files are parsed using `tail -f` command. Every line is sent to the configured class and then parsed data is sent to configured actor classes that can publish events, stats, errors, etc.


Requirements
============

- PHP 5.3.2 and up with pcntl extension installed.
- RabbitMQ or ZMQ.
- tail


Libraries and services used
===========================

- PHP
	- Pimple
	- Symfony Components:
		- ClassLoader
		- YAML
	- PhpAmqpLib 
- RabbitMQ/ZMQ+OpenPGM


Installation
============

The best way to install is to clone the repository and then configure as you need. See "Configuration" section.

After cloning you must update vendors:

	./update_vendors.sh
 

Usage
=====

Start monitoring. Examples:

	rm -f test/*.sdf; php app/log_monitor.php -c app/config/test_upstream_log.yml

* As you can see, before execute the command we delete *.sdf files. These files contain information about the last position the reader had in the monitored file.
* Use test/listenRabbitMQ.php to see what is published by the parser.


Configuration
=============

All configuration is done using a YAML file.

Config file is structured in one or more sections named as you want. Every section has 4 subsections:

- file:
	- path to file that will be parsed and monitored.

- file_stat:
	- path where to write position data from monitored file.

- parser:
	- class name in charge of parsing the file.

- actors:
	- list of actor classes that receive parsed data and do actions defined in the class code.

- actor_config:
	- config parameters passed to actor classes.

See config file for more details and examples.


Extra notes
===========

We detect if a monitored file is rotated using "atime" parameter from stats. You must mount your file system using "noatime" parameter in /etc/fstab.

Use of ZMQ is discontinued because a memory leak using ZMQ with OpenPGM PUB/SUB.
