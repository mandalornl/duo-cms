<?php

namespace Softmedia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Softmedia\AdminBundle\Configuration\Field;
use Softmedia\AdminBundle\Controller\Behavior\CloneableTrait;
use Softmedia\AdminBundle\Controller\Behavior\SoftDeletableTrait;
use Softmedia\AdminBundle\Controller\Behavior\SortableTrait;
use Softmedia\AdminBundle\Controller\Behavior\PublishableTrait;
use Softmedia\AdminBundle\Entity\Page;
use Softmedia\AdminBundle\Form\PageAdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageAdminController extends AbstractAdminController
{
	use CloneableTrait;
	use SoftDeletableTrait;
	use SortableTrait;
	use PublishableTrait;

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
	protected function getListType(): string
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
		$this
			->addField(
				(new Field())
					->setTitle('Name')
					->setProperty('name')
			)
			->addField(
				(new Field())
					->setTitle('Title')
					->setProperty('title')
			)
			->addField(
				(new Field())
					->setTitle('Created at')
					->setProperty('createdAt')
			)
			->addField(
				(new Field())
					->setTitle('Modified at')
					->setProperty('modifiedAt')
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
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="softmedia_admin_page_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="softmedia_admin_page_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="softmedia_admin_page_duplicate", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function duplicateAction(Request $request, int $id)
	{
		return $this->doDuplicateAction($request, $id);
	}

    /**
     * {@inheritdoc}
     *
     * @Route("/view/{id}/version/{versionId}", name="softmedia_admin_page_version", requirements={ "id" = "\d+", "versionId" = "\d+" })
     * @Method("GET")
     */
    public function versionAction(Request $request, int $id, int $versionId)
    {
        return $this->doVersionAction($request, $id, $versionId);
    }

    /**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="softmedia_admin_page_delete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function deleteAction(Request $request, int $id)
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="softmedia_admin_page_undelete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function undeleteAction(Request $request, int $id)
	{
		return $this->doUndeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="softmedia_admin_page_move_up", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveUpAction(Request $request, int $id)
	{
		return $this->doMoveUpAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="softmedia_admin_page_move_down", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveDownAction(Request $request, int $id)
	{
		return $this->doMoveDownAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/publish/{id}", name="softmedia_admin_page_publish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function publishAction(Request $request, int $id)
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/unpublish/{id}", name="softmedia_admin_page_unpublish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function unpublishAction(Request $request, int $id)
	{
		return $this->doUnpublishAction($request, $id);
	}
}