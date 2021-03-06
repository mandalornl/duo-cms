<?php

namespace Duo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as CoreDateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'html5' => false,
			'date_widget' => 'single_text',
			'date_format' => 'dd-MM-yyyy',
			'format' => 'dd-MM-yyyy HH:mm',
			'attr' => [
				'class' => 'datepicker-with-time'
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return CoreDateTimeType::class;
	}
}
