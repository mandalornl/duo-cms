<?php

namespace Duo\AdminBundle\Event;

use Duo\AdminBundle\Twig\TwigContextInterface;
use Symfony\Component\EventDispatcher\Event;

class TwigEvent extends Event
{
	/**
	 * @var TwigContextInterface
	 */
	private $context;

	/**
	 * TwigEvent constructor
	 *
	 * @param TwigContextInterface $context
	 */
	public function __construct(TwigContextInterface $context)
	{
		$this->context = $context;
	}

	/**
	 * Set context
	 *
	 * @param TwigContextInterface $context
	 *
	 * @return TwigEvent
	 */
	public function setContext(TwigContextInterface $context): TwigEvent
	{
		$this->context = $context;

		return $this;
	}

	/**
	 * Get context
	 *
	 * @return TwigContextInterface
	 */
	public function getContext(): ?TwigContextInterface
	{
		return $this->context;
	}
}