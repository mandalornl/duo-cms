<?php

namespace Duo\PageBundle\Twig;

use Duo\PageBundle\Entity\PageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadcrumbsExtension extends AbstractExtension
{
	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('get_breadcrumbs', [$this, 'getBreadcrumbs'])
		];
	}

	/**
	 * Get breadcrumbs
	 *
	 * @param PageInterface $page
	 *
	 * @return PageInterface[]
	 */
	public function getBreadcrumbs(PageInterface $page): array
	{
		$breadcrumbs = [];

		// limit iterations to prevent infinite loop
		$iterations = 99;

		do
		{
			$breadcrumbs[] = $page;

			$page = $page->getParent();
		} while ($page !== null && $iterations--);

		return array_reverse($breadcrumbs);
	}
}
