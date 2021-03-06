#!/usr/bin/env php
<?php

require __DIR__ . '/init.php';

$api_xml = '/xml/' . URFAClient::API_XML;

$doc = <<<DOC

The options are as follows:
   [-a, --api <path> ]             Path to api.xml, default: .$api_xml
   [-f, --function <name>]         Name function from api.xml
   [-t, --type <type>]             Type return array or xml, default: array
   [-l, --list]                    List of functions from api.xml
   [-h, --help ]                   This help
   [-v, --version ]                Version URFAClient


DOC;

$options = getopt("a:f:t:lv", array('api:', 'function:', 'type:', 'list', 'version'));

$api_xml = __DIR__ . $api_xml;
if (isset($options['a']))
{
    $api_xml = $options['a'];
    unset($options['a']);
}
if (isset($options['api']))
{
    $api_xml = $options['api'];
    unset($options['api']);
}

if ( ! $options) die($doc);

if (isset($options['v']) OR isset($options['version'])) die('URFAClient ' . URFAClient::VERSION . "\n");

$api = new URFAClient_Cmd($api_xml);

if (isset($options['l']) OR isset($options['list']))
{
    $methods = $api->methods();
    foreach($methods as $name => $id) print "$name ($id)\n";
    die('Count of functions: ' . count($methods) . "\n");
}

$function = (isset($options['f'])) ? $options['f'] : NULL;
$function = (isset($options['function'])) ? $options['function'] : $function;

$type = (isset($options['t'])) ? $options['t'] : NULL;
$type = (isset($options['type'])) ? $options['type'] : $type;

try
{
    var_export($api->method($function, $type));
    die("\n");
}
catch (Exception $e)
{
    die('Error: ' . $e->getMessage() . "\n");
}
