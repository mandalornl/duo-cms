<?php

namespace Duo\FormBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Recaptcha extends Constraint
{
	/**
	 * @var string
	 */
	public $message = 'Invalid reCAPTCHA.';

	/**
	 * @var string
	 */
	public $expectedHostname;

	/**
	 * @var string
	 */
	public $expectedAction;

	/**
	 * @var string
	 */
	public $scoreThreshold;

	/**
	 * @var string
	 */
	public $challengeTimeout;

	/**
	 * @var string
	 */
	public $remoteIp;

	/**
	 * Recaptcha constructor
	 *
	 * @param mixed $options [optional]
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);

		if ($this->scoreThreshold !== null && ($this->scoreThreshold < 0 || $this->scoreThreshold > 1))
		{
			throw new InvalidOptionsException(
				sprintf('Invalid value for option \'scoreThreshold\' in constraint %s', __CLASS__),
				['scoreThreshold']
			);
		}

		if ($this->challengeTimeout !== null && !is_numeric($this->challengeTimeout))
		{
			throw new InvalidOptionsException(
				sprintf('Invalid value for option \'challengeTimeout\' in constraint %s', __CLASS__),
				['challengeTimeout']
			);
		}

		if ($this->remoteIp !== null && !filter_var($this->remoteIp, FILTER_VALIDATE_IP))
		{
			throw new InvalidOptionsException(
				sprintf('Invalid value for option \'remoteIp\' in constraint %s', __CLASS__),
				['remoteIp']
			);
		}
	}
}
