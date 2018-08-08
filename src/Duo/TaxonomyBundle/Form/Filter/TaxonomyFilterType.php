<?php

namespace Duo\TaxonomyBundle\Form\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Duo\AdminBundle\Form\Filter\EnumFilterType;
use Duo\TaxonomyBundle\Entity\Taxonomy;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class TaxonomyFilterType extends EnumFilterType
{
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * TaxonomyFilterType constructor
	 *
	 * @param TranslatorInterface $translator
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(TranslatorInterface $translator, EntityManagerInterface $entityManager)
	{
		parent::__construct($translator);

		$this->entityManager = $entityManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'choices' => $this->getChoices()
		]);
	}

	/**
	 * Get choices
	 *
	 * @return array
	 */
	private function getChoices(): array
	{
		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->entityManager->getRepository(Taxonomy::class);

		$result = $repository->createQueryBuilder('e')
			->select('e.id, t.name')
			->join('e.translations', 't', Join::WITH, 't.translatable = e AND t.locale = :locale')
			->setParameter('locale', $this->translator->getLocale())
			->orderBy('t.name', 'ASC')
			->getQuery()
			->getScalarResult();

		foreach ($result as $index => $item)
		{
			$result[$item['name']] = $item['id'];

			unset($result[$index]);
		}

		return $result;
	}
}