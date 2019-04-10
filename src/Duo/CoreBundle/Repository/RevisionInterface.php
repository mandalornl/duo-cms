<?php

namespace Duo\CoreBundle\Repository;

interface RevisionInterface
{
	/**
	 * Check whether or not revision name exists.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function revisionNameExists(string $name): bool;
}
