<?php

namespace Duo\FormBundle\Validator\Constraints;

use ReCaptcha\ReCaptcha as GoogleRecaptcha;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RecaptchaValidator extends ConstraintValidator
{
	/**
	 * @var string
	 */
	private $secret;

	/**
	 * RecaptchaValidator constructor
	 *
	 * @param string $secret
	 */
	public function __construct(string $secret)
	{
		$this->secret = $secret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($value, Constraint $constraint): void
	{
		if (!$constraint instanceof Recaptcha)
		{
			throw new UnexpectedTypeException($constraint, Recaptcha::class);
		}

		if ($value === null || $value === '')
		{
			return;
		}

		$recaptcha = new GoogleReCaptcha($this->secret);

		if ($constraint->expectedHostname !== null)
		{
			$recaptcha->setExpectedHostname($constraint->expectedHostname);
		}

		if ($constraint->expectedAction !== null)
		{
			$recaptcha->setExpectedAction($constraint->expectedAction);
		}

		if ($constraint->scoreThreshold !== null)
		{
			$recaptcha->setScoreThreshold($constraint->scoreThreshold);
		}

		if ($constraint->challengeTimeout !== null)
		{
			$recaptcha->setChallengeTimeout($constraint->challengeTimeout);
		}

		$response = $recaptcha->verify($value, $constraint->remoteIp);

		if (!$response->isSuccess())
		{
			$this->context->addViolation($constraint->message);
		}
	}
}
