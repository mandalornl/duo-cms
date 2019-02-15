<?php

namespace Duo\CoreBundle\Repository;

interface TreeInterface
{
	/**
	 * Get offspring id's
	 *
	 * @param int $id
	 * @param bool $traverse [optional]
	 *
	 * @return int[]
	 */
	public function getOffspringIds(int $id, bool $traverse = true): array;

	/**
	 * Get parent id's
	 *
	 * @param int $id
	 * @param bool $traverse [optional]
	 *
	 * @return int[]
	 */
	public function getParentIds(int $id, bool $traverse = true): array;
}
