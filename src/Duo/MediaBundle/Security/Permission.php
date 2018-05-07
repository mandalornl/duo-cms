<?php

namespace Duo\MediaBundle\Security;

class Permission
{
	/**
	 * @var int
	 */
	const NONE = 0;

	/**
	 * @var int
	 */
	const READ = 1;

	/**
	 * @var int
	 */
	const WRITE = 2;

	/**
	 * @var int
	 */
	const CREATE = 4;

	/**
	 * @var int
	 */
	const RENAME = 8;

	/**
	 * @var int
	 */
	const DELETE = 16;

	/**
	 * @var int
	 */
	const ALL = 31;

	/**
	 * Permission constructor
	 */
	private function __construct() {}
}