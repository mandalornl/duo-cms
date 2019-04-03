<?php

$memcached = parse_url(getenv('MEMCACHED_URL'));

if (($memcached['scheme'] ?? '') === 'memcached')
{
	$container->setParameter('memcached_host', $memcached['host'] ?? '127.0.0.1');
	$container->setParameter('memcached_port', $memcached['port'] ?? 11211);
}
