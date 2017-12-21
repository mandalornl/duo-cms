<?php

namespace Softmedia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Softmedia\AdminBundle\Configuration\Field;
use Softmedia\AdminBundle\Entity\Taxonomy;
use Softmedia\AdminBundle\Form\TaxonomyAdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxonomyAdminController extends AbstractAdminController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClassName(): string
	{
		return Taxonomy::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormClassName(): string
	{
		return TaxonomyAdminType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getListType(): string
	{
		return 'taxonomy';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		// TODO: Implement defineFilters() method.
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(
				(new Field())
					->setTitle('Name')
					->setProperty('name')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function preDecorateEntity($entity)
	{
		foreach ($this->getParameter('locales') as $locale)
		{
			/**
			 * @var Taxonomy $entity
			 */
			$entity->translate($locale);
		};

		$entity->mergeNewTranslations();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function postDecorateEntity($entity)
	{
		/**
		 * @var Taxonomy $entity
		 */
		$entity->mergeNewTranslations();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="softmedia_admin_taxonomy_list")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="softmedia_admin_taxonomy_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="softmedia_admin_taxonomy_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="softmedia_admin_taxonomy_destroy", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}
}