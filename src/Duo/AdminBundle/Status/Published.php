<?php

namespace Duo\AdminBundle\Status;

class Published
{
	/**
	 * @var int
	 */
	const NONE = 0;

	/**
	 * @var int
	 */
	const ALL = 1;

	/**
	 * @var int
	 */
	const PARTIAL = 2;

	/**
	 * Publish constructor
	 */
	private function __construct() {}
}