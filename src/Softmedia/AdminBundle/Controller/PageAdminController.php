<?php

namespace Softmedia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Softmedia\AdminBundle\Entity\Page;
use Softmedia\AdminBundle\Form\PageAdminType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageAdminController extends AbstractAdminController
{
	use SortableTrait;
	use SoftDeletableTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClassName(): string
	{
		return Page::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormClassName(): string
	{
		return PageAdminType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getRoutePrefix(): string
	{
		return 'page';
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
		// TODO: Implement defineFields() method.
	}

	/**
	 * {@inheritdoc}
	 */
	protected function preDecorateEntity($entity)
	{
		foreach ($this->getParameter('locales') as $locale)
		{
			/**
			 * @var Page $entity
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
		 * @var Page $entity
		 */
		$entity->mergeNewTranslations();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="softmedia_admin_page_list")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="softmedia_admin_page_add")
	 * @Method({"POST", "GET"})
	 */
	public function addIndex(Request $request)
	{
		return $this->doAddIndex($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="softmedia_admin_page_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editIndex(Request $request, int $id)
	{
		return $this->doEditIndex($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="softmedia_admin_page_delete", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function deleteIndex(Request $request, int $id): RedirectResponse
	{
		return $this->doDeleteIndex($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/restore/{id}", name="softmedia_admin_page_restore", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function restoreIndex(Request $request, int $id): RedirectResponse
	{
		return $this->doRestoreAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="softmedia_admin_page_destroy", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function destroyIndex(Request $request, int $id): RedirectResponse
	{
		return $this->doDestroyIndex($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="softmedia_admin_page_move_up", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function moveUp(Request $request, int $id): RedirectResponse
	{
		return $this->doMoveUp($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="softmedia_admin_page_move_down", requirements={ "id" = "\d+" })
	 * @Method("POST")
	 */
	public function moveDown(Request $request, int $id): RedirectResponse
	{
		return $this->doMoveDown($request, $id);
	}
}