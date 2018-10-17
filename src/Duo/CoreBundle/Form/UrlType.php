<?php

namespace Duo\CoreBundle\Form;

use Duo\CoreBundle\Form\DataTransformer\UrlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UrlType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->addViewTransformer(new UrlTransformer());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TextType::class;
	}
}