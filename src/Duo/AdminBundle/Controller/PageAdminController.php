<?php

namespace Duo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Controller\Behavior\CloneableTrait;
use Duo\AdminBundle\Controller\Behavior\VersionableTrait;
use Duo\AdminBundle\Controller\Behavior\SoftDeletableTrait;
use Duo\AdminBundle\Controller\Behavior\SortableTrait;
use Duo\AdminBundle\Controller\Behavior\PublishableTrait;
use Duo\AdminBundle\Entity\Page;
use Duo\AdminBundle\Form\PageAdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageAdminController extends AbstractAdminController
{
	use VersionableTrait;
	use SoftDeletableTrait;
	use SortableTrait;
	use PublishableTrait;
	use CloneableTrait;

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
	 *
	 * @Route("/", name="duo_admin_page_list")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="duo_admin_page_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="duo_admin_page_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="duo_admin_page_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duo_admin_page_duplicate", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function duplicateAction(Request $request, int $id)
	{
		return $this->doDuplicateAction($request, $id);
	}

    /**
     * {@inheritdoc}
     *
     * @Route("/version/{id}/", name="duo_admin_page_version", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
     */
    public function versionAction(Request $request, int $id)
    {
        return $this->doVersionAction($request, $id);
    }

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/revert/{id}", name="duo_admin_page_revert", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
    public function revertAction(Request $request, int $id)
	{
		return $this->doRevertAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="duo_admin_page_delete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function deleteAction(Request $request, int $id)
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="duo_admin_page_undelete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function undeleteAction(Request $request, int $id)
	{
		return $this->doUndeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="duo_admin_page_move_up", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveUpAction(Request $request, int $id)
	{
		return $this->doMoveUpAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-down/{id}", name="duo_admin_page_move_down", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveDownAction(Request $request, int $id)
	{
		return $this->doMoveDownAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     "/move-to/{id}/{weight}/{parentId}",
	 *     name="duo_admin_page_move_to",
	 *     requirements={ "id" = "\d+", "weight" = "\d+", "parentId" = "\d+" }
	 * )
	 * @Method({"POST", "GET"})
	 */
	public function moveToAction(Request $request, int $id, int $weight, int $parentId = null)
	{
		return $this->doMoveToAction($request, $id, $weight, $parentId);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/publish/{id}", name="duo_admin_page_publish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function publishAction(Request $request, int $id)
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/unpublish/{id}", name="duo_admin_page_unpublish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function unpublishAction(Request $request, int $id)
	{
		return $this->doUnpublishAction($request, $id);
	}
}