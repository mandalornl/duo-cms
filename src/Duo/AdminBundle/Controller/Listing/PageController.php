<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Controller\Behavior\CloneTrait;
use Duo\AdminBundle\Controller\Behavior\VersionTrait;
use Duo\AdminBundle\Controller\Behavior\SoftDeleteTrait;
use Duo\AdminBundle\Controller\Behavior\SortTrait;
use Duo\AdminBundle\Controller\Behavior\PublishTrait;
use Duo\AdminBundle\Entity\Page;
use Duo\AdminBundle\Form\Listing\PageType;
use Duo\BehaviorBundle\Controller\CloneInterface;
use Duo\BehaviorBundle\Controller\PublishInterface;
use Duo\BehaviorBundle\Controller\SoftDeleteInterface;
use Duo\BehaviorBundle\Controller\SortInterface;
use Duo\BehaviorBundle\Controller\VersionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageController extends AbstractController implements CloneInterface, PublishInterface, SoftDeleteInterface, SortInterface, VersionInterface
{
	use CloneTrait;
	use PublishTrait;
	use SoftDeleteTrait;
	use SortTrait;
	use VersionTrait;

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
		return PageType::class;
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
			->addField(new Field('duo.admin.listing.field.name', 'name'))
			->addField(new Field('duo.admin.listing.field.title', 'title'))
			->addField(new Field('duo.admin.listing.field.url', 'url'))
			->addField(new Field('duo.admin.listing.field.online', 'published'))
			->addField(new Field('duo.admin.listing.field.created_at', 'createdAt'))
			->addField(new Field('duo.admin.listing.field.modified_at', 'modifiedAt'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="duo_admin_listing_page_index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="duo_admin_listing_page_add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="duo_admin_listing_page_edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="duo_admin_listing_page_destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id)
	{
		return $this->doDestroyAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duo_admin_listing_page_duplicate", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function duplicateAction(Request $request, int $id)
	{
		return $this->doDuplicateAction($request, $id);
	}

    /**
     * {@inheritdoc}
     *
     * @Route("/version/{id}/", name="duo_admin_listing_page_version", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
     */
    public function versionAction(Request $request, int $id)
    {
        return $this->doVersionAction($request, $id);
    }

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/revert/{id}", name="duo_admin_listing_page_revert", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 *
	 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
	 */
    public function revertAction(Request $request, int $id)
	{
		return $this->doRevertAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="duo_admin_listing_page_delete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function deleteAction(Request $request, int $id)
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="duo_admin_listing_page_undelete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 *
	 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
	 */
	public function undeleteAction(Request $request, int $id)
	{
		return $this->doUndeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="duo_admin_listing_page_move_up", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveUpAction(Request $request, int $id)
	{
		return $this->doMoveUpAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-down/{id}", name="duo_admin_listing_page_move_down", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 *
	 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
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
	 *     name="duo_admin_listing_page_move_to",
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
	 * @Route("/publish/{id}", name="duo_admin_listing_page_publish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function publishAction(Request $request, int $id)
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/unpublish/{id}", name="duo_admin_listing_page_unpublish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function unpublishAction(Request $request, int $id)
	{
		return $this->doUnpublishAction($request, $id);
	}
}