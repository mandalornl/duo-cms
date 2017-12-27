<?php

namespace Duo\AdminBundle\Form\Security;

use Doctrine\ORM\EntityRepository;
use Duo\AdminBundle\Entity\Security\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class GroupChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * GroupChoiceType constructor
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
			'label' => 'duo.form.group_choice.label',
			'class' => Group::class,
			'empty_data' => null,
			'multiple' => true,
			'query_builder' => function(EntityRepository $repository)
			{
				return $repository->createQueryBuilder('e')
					->orderBy('e.name', 'ASC');
			},
			'choice_label' => 'name',
			'attr' => [
				'data-placeholder' => $this->translator->trans('duo.form.group_choice.placeholder')
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return EntityType::class;
	}
}