<?php

namespace AppBundle\Composer;

use Composer\Script\Event;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;

class ScriptHandler extends SensioScriptHandler
{
	/**
	 * Run migrations
	 *
	 * @param Event $event
	 */
	public static function runMigrations(Event $event): void
	{
		$consoleDir = static::getConsoleDir($event, 'run database migrations');

		if ($consoleDir === null)
		{
			return;
		}

		$options = static::getOptions($event);

		static::executeCommand($event, $consoleDir, 'doctrine:migrations:migrate -n --allow-no-migration', $options['process-timeout']);
	}
}