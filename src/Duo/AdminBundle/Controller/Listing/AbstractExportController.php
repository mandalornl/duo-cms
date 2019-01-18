<?php

namespace Duo\AdminBundle\Controller\Listing;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Duo\AdminBundle\Configuration\Field\FieldInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractExportController extends AbstractController
{
	/**
	 * @var ArrayCollection
	 */
	protected $fields;

	/**
	 * Export action
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return StreamedResponse
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	protected function doExportAction(Request $request, int $id = null): Response
	{
		$selection = (array)$id ?: $this->getSelection($request);

		// define properties
		$this->defineFields($request);

		$response = new StreamedResponse();
		$response->setCallback(function() use ($request, $selection)
		{
			$type = $request->getRequestFormat(Type::XLSX);

			$writer = WriterFactory::create($type);
			$writer->openToBrowser(uniqid() . '.' . $type);

			/**
			 * @var FieldInterface[] $fields
			 */
			$fields = $this->getFields()->toArray();

			$writer->addRow(array_merge([
				'Id'
			], array_map(function(FieldInterface $field)
			{
				return $this->get('translator')->trans($field->getLabel());
			}, $fields)));

			/**
			 * @var EntityRepository $repository
			 */
			$repository = $this->getDoctrine()->getRepository($this->getEntityClass());

			$builder = $repository->createQueryBuilder('e')
				->select('e.id');

			if ($this->getEntityReflectionClass()->implementsInterface(TranslateInterface::class))
			{
				$builder
					->join('e.translations', 't', Join::WITH, 't.locale = :locale')
					->setParameter('locale', $request->getLocale());
			}

			foreach ($fields as $index => $field)
			{
				$field->buildExport($request, $builder);
			}

			foreach (array_chunk($selection, 100) as $ids)
			{
				$result = $builder
					->andWhere('e.id IN(:ids)')
					->setParameter('ids', $ids)
					->getQuery()
					->getScalarResult();

				$writer->addRows($result);
			}

			$writer->close();
		});

		return $response;
	}

	/**
	 * Export action
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|StreamedResponse
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	abstract public function exportAction(Request $request, int $id = null): Response;

	/**
	 * Add field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function addField(FieldInterface $field)
	{
		$this->getFields()->set($field->getHash(), $field);

		return $this;
	}

	/**
	 * Remove field
	 *
	 * @param FieldInterface $field
	 *
	 * @return $this
	 */
	public function removeField(FieldInterface $field)
	{
		$this->getFields()->remove($field->getHash());

		return $this;
	}

	/**
	 * Get fields
	 *
	 * @return ArrayCollection
	 */
	public function getFields(): ArrayCollection
	{
		return $this->fields = $this->fields ?: new ArrayCollection();
	}

	/**
	 * Define fields
	 *
	 * @param Request $request
	 */
	abstract protected function defineFields(Request $request): void;
}
