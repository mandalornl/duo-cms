<?php

$database = parse_url(getenv('DATABASE_URL'));

if (($database['scheme'] ?? '') === 'mysql')
{
	$container->setParameter('database_driver', 'pdo_mysql');
	$container->setParameter('database_host', $database['host'] ?? '127.0.0.1');
	$container->setParameter('database_port', $database['port'] ?? 3306);
	$container->setParameter('database_name', substr($database['path'], 1));
	$container->setParameter('database_user', $database['user']);
	$container->setParameter('database_password', $database['pass']);
}

$memcached = parse_url(getenv('MEMCACHED_URL'));

if (($memcached['scheme'] ?? '') === 'memcached')
{
	$container->setParameter('memcached_host', $memcached['host'] ?? '127.0.0.1');
	$container->setParameter('memcached_port', $memcached['port'] ?? 11211);
}

$mailer = parse_url(getenv('MAILER_URL'));

if (in_array(($mailer['scheme'] ?? null), ['smtp', 'gmail', 'sendmail']))
{
	$queryData = [];
	parse_str($mailer['query'] ?? '', $queryData);

	$container->setParameter('mailer_transport', $mailer['scheme']);
	$container->setParameter('mailer_host', $mailer['host'] ?? '127.0.0.1');
	$container->setParameter('mailer_port', $mailer['port'] ?? 25);
	$container->setParameter('mailer_user', $mailer['user'] ?? null);
	$container->setParameter('mailer_password', $mailer['pass'] ?? null);
	$container->setParameter('mailer_encryption', $queryData['encryption'] ?? null);
}
