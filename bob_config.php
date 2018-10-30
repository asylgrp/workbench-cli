<?php

namespace Bob\BuildConfig;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

task('default', ['test', 'phpstan', 'sniff', 'phar', 'behat-phar']);

desc('Run unit and feature tests');
task('test', ['phpspec', 'behat']);

desc('Run phpspec unit tests');
task('phpspec', function() {
    shell('phpspec run');
    println('Phpspec unit tests passed');
});

desc('Run behat feature tests');
task('behat', ['update_container'], function() {
    shell('behat --stop-on-failure --suite=default');
    println('Behat feature tests passed');
});

desc('Run behat feature tests using phar');
task('behat-phar', ['workbench.phar'], function() {
    shell('behat --stop-on-failure --suite=phar');
    println('Behat feature tests using PHAR passed');
});

desc('Run statical analysis using phpstan feature tests');
task('phpstan', function() {
    // TODO
    println('PHPSTAN TESTS COMMENTED OUT...');
    #shell('phpstan analyze src -l 7');
    #println('Phpstan analysis passed');
});

desc('Run php code sniffer');
task('sniff', function() {
    shell('phpcs src --standard=PSR2 --ignore=src/DependencyInjection/ProjectServiceContainer.php');
    println('Syntax checker on src/ passed');
    shell('phpcs spec --standard=spec/ruleset.xml');
    println('Syntax checker on spec/ passed');
});

desc('Build phar');
task('phar', function() {
    build_phar();
});

fileTask('workbench.phar', ['workbench.phar'], function () {
    build_phar();
});

function build_phar()
{
    shell('composer install --prefer-dist --no-dev');
    shell('box compile');
    shell('composer install');
    println('Phar generation done');
}

desc('Build dependency injection container');
task('container', ['load_dependencies'], function () {
    build_container();
});

define('CONTAINER_PATH', 'src/DependencyInjection/ProjectServiceContainer.php');

task('update_container', ['load_dependencies', CONTAINER_PATH]);

fileTask(CONTAINER_PATH, fileList('*.yaml')->in([__DIR__ . '/etc']), function () {
    // TODO
    println('AUTOBUILD CONTAINER COMMENTED OUT...');
    // build_container();
});

function build_container()
{
    $dic = new ContainerBuilder;
    (new YamlFileLoader($dic, new FileLocator(__DIR__ . '/etc')))->load(basename('container.yaml'));
    $dic->addCompilerPass(new RegisterListenersPass(Dispatcher::CLASS, 'event_listener', 'event_subscriber'));
    $dic->compile();
    file_put_contents(CONTAINER_PATH, (new PhpDumper($dic))->dump([
        'namespace' => 'asylgrp\workbench\DependencyInjection',
        'class' => 'ProjectServiceContainer'
    ]));
    println('Generated dependency injection container');
}

task('load_dependencies', ['vendor/autoload.php'], function () {
    require_once 'vendor/autoload.php';
});

fileTask('vendor/autoload.php', ['vendor/autoload.php'], function() {
    shell('composer install');
});

desc('Globally install development tools');
task('install_dev_tools', function() {
    shell('composer global require consolidation/cgr');
    shell('cgr phpspec/phpspec');
    shell('cgr behat/behat');
    shell('cgr phpstan/phpstan');
    shell('cgr squizlabs/php_codesniffer');
    shell('cgr humbug/box --stability dev');
});

function shell(string $command)
{
    return sh($command, null, ['failOnError' => true]);
}
