#!/usr/bin/php
<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$time_start = microtime(true);

require_once (__DIR__ . '/tests_common.php');
_wl('test "' . basename(__FILE__) . '');

require_once (__DIR__ . '/../lib/OSM/Api.php');

require_once (__DIR__ . '/../../secrets.php');

$osmApi = new OSM_Api(array(
		'url' => OSM_Api::URL_DEV_UK,
		'url4Write' => OSM_Api::URL_DEV_UK,
		'user' => OSM_USER_DEV,
		'password' => OSM_PASSWORD_DEV,
		'simulation' => false
		));

// http://api06.dev.openstreetmap.org/api/0.6/relation/500
// http://api06.dev.openstreetmap.org/api/0.6/way/8184
// http://api06.dev.openstreetmap.org/api/0.6/node/611571

// get a node

$node = $osmApi->getNode('611571');

// add a tag

$tagName = 'yapafo.net::test::'.time() ;
$tagValue = 'Have a nice dev ;-)' ;
$node->addTag(new OSM_Objects_Tag($tagName, $tagValue));
$tags = $node->findTags(array($tagName=>$tagValue));
_assert( count($tags)==1 );
_assert( $node->hasTags(array($tagName=>$tagValue)) );

// save changes

_assert($node->isDirty());
$osmApi->saveChanges('A yapafo.net test');
_assert( $node->isDirty() );
$tags = $node->findTags(array($tagName=>$tagValue));
_assert( count($tags)==1 );
_assert( $node->hasTags(array($tagName=>$tagValue)) );

// remove then reload object to get real changes

$osmApi->removeObject(OSM_Api::OBJTYPE_NODE, '611571');
$node = $osmApi->getNode('611571');
$tags = $node->findTags(array($tagName=>$tagValue));
_assert( count($tags)==1 );
_assert( $node->hasTags(array($tagName=>$tagValue)) );

// test a node's tag modification 

$tagName = 'yapafo.net::test::modify' ;
$tagValue = '1' ;

$osmApi->removeAllObjects();
$node = $osmApi->getNode('611571');

if( ! $node->hasTags(array($tagName=>null)) )
{
	$node->addTag(new OSM_Objects_Tag($tagName, $tagValue));
	$osmApi->saveChanges('A yapafo.net test');
	$osmApi->removeAllObjects();
	$node = $osmApi->getNode('611571');
}
else
{
	
}
$node->setTag($tagName, $tagValue+1 );
_assert( $node->isDirty() );
$osmApi->saveChanges('A yapafo.net test');
_assert( $node->isDirty() );
$osmApi->removeAllObjects();

$node = $osmApi->getNode('611571');
_assert( ! $node->isDirty() );
_assert( $node->getTag($tagName, $tagValue+1)->getValue() == '2' );

$time_end = microtime(true);
_wl('Test well done in ' . number_format($time_end - $time_start, 3) . ' second(s).');
