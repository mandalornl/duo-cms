<?php

namespace Duo\SecurityBundle\EventListener\Listing;

use Duo\AdminBundle\Event\ListingEvent;
use Duo\SecurityBundle\Entity\UserInterface;

class UserListener
{
	/**
	 * On post edit event
	 *
	 * @param ListingEvent $event
	 */
	public function postEdit(ListingEvent $event)
	{
		$entity = $event->getEntity();

		if (!$entity instanceof UserInterface)
		{
			return;
		}

		// dirty entity to trigger new password generation
		if ($entity->getPlainPassword() !== null)
		{
			$entity->setModifiedAt(null);
		}
	}
}