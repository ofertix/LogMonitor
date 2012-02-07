#!/bin/sh

git submodule init
git submodule update

cd vendor/php-amqplib
git submodule init
git submodule update
cd ../..

