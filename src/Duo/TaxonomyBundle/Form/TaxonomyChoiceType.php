<?php

namespace Duo\TaxonomyBundle\Form;

use Doctrine\ORM\EntityRepository;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class TaxonomyChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * TaxonomyChoiceType constructor
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
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'label' => 'duo.taxonomy.form.taxonomy_choice.label',
			'class' => Taxonomy::class,
			'empty_data' => null,
			'multiple' => true,
			'query_builder' => function(EntityRepository $repository)
			{
				$reflectionClass = new \ReflectionClass($repository->getClassName());
				if ($reflectionClass->implementsInterface(TranslateInterface::class))
				{
					return $repository->createQueryBuilder('e')
						->join('e.translations', 't')
						->orderBy('t.name', 'ASC');
				}

				return $repository->createQueryBuilder('e')->orderBy('e.name', 'ASC');
			},
			'choice_label' => function(Taxonomy $taxonomy)
			{
				if ($taxonomy instanceof TranslateInterface)
				{
					return $taxonomy->translate()->getName();
				}

				return $taxonomy->getName();
			},
			'attr' => [
				'data-placeholder' => $this->translator->trans('duo.taxonomy.form.taxonomy_choice.placeholder')
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return EntityType::class;
	}
}