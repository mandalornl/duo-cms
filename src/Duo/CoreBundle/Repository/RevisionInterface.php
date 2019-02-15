<?php

namespace Duo\CoreBundle\Repository;

interface RevisionInterface
{
	/**
	 * Check whether or not name exists.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function nameExists(string $name): bool;
}
