<?php

namespace Duo\TaxonomyBundle\Form;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo.taxonomy.form.taxonomy_choice.label',
			'class' => Taxonomy::class,
			'empty_data' => null,
			'multiple' => true,
			'query_builder' => function(EntityRepository $repository)
			{
				return $repository->createQueryBuilder('e')
					->join('e.translations', 't', Join::WITH, 't.translatable = e AND t.locale = :locale')
					->setParameter('locale', $this->translator->getLocale())
					->orderBy('t.name', 'ASC');
			},
			'choice_label' => function(Taxonomy $taxonomy)
			{
				return $taxonomy->translate($this->translator->getLocale())->getName();
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