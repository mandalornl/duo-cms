<?php

namespace Duo\AdminBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

class TimeTwigExtension extends \Twig_Extension
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * TimeTwigExtension constructor
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [
			new \Twig_SimpleFilter('elapsed_time', [$this, 'getElapsedTime'])
		];
	}

	/**
	 * Get elapsed time
	 *
	 * @param \DateTime $start
	 * @param \DateTime $end
	 *
	 * @return string
	 */
	public function getElapsedTime(\DateTime $start, \DateTime $end): string
	{
		$interval = $end->diff($start);

		if ($interval !== false)
		{
			$time = $interval->format('%s');

			$labels = array_flip([
				'duo.admin.elapsed_time.years' 		=> 3600 * 24 * 365,
				'duo.admin.elapsed_time.months' 	=> 3600 * 24 * 30,
				'duo.admin.elapsed_time.weeks' 		=> 3600 * 24 * 7,
				'duo.admin.elapsed_time.days' 		=> 3600 * 24,
				'duo.admin.elapsed_time.hours' 		=> 3600,
				'duo.admin.elapsed_time.minutes' 	=> 60,
				'duo.admin.elapsed_time.seconds' 	=> 1
			]);

			foreach ($labels as $unit => $label)
			{
				if ($time < $unit)
				{
					continue;
				}

				$numberOfUnits = floor($time / $unit);

				return $this->translator->transChoice($label, $numberOfUnits, [
					'%unit%' => $numberOfUnits
				]);
			}
		}

		return $this->translator->transChoice('duo.admin.elapsed_time.seconds', 0, [
			'%unit%' => 0
		]);
	}
}