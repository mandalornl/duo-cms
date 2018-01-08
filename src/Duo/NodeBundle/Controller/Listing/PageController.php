<?php

namespace Duo\NodeBundle\Controller\Listing;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Controller\CloneInterface;
use Duo\BehaviorBundle\Controller\CloneTrait;
use Duo\BehaviorBundle\Controller\DeleteTrait;
use Duo\BehaviorBundle\Controller\PublishInterface;
use Duo\BehaviorBundle\Controller\DeleteInterface;
use Duo\BehaviorBundle\Controller\PublishTrait;
use Duo\BehaviorBundle\Controller\SortInterface;
use Duo\BehaviorBundle\Controller\SortTrait;
use Duo\BehaviorBundle\Controller\VersionInterface;
use Duo\BehaviorBundle\Controller\VersionTrait;
use Duo\NodeBundle\Entity\Page;
use Duo\NodeBundle\Form\Listing\PageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_node_listing_page_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageController extends AbstractController implements CloneInterface, PublishInterface, DeleteInterface, SortInterface, VersionInterface
{
	use CloneTrait;
	use PublishTrait;
	use DeleteTrait;
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
	protected function getType(): string
	{
		return 'page';
	}

	/**
	 * Define filters
	 */
	protected function defineFilters(): void
	{
		// TODO: Implement defineFilters() method.
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('duo.node.listing.field.name', 'name'))
			->addField(new Field('duo.node.listing.field.title', 'title'))
			->addField(new Field('duo.node.listing.field.url', 'url', true, '@DuoAdmin/Listing/Field/url.html.twig'))
			->addField(new Field('duo.node.listing.field.online', 'published', true, '@DuoAdmin/Listing/Field/published.html.twig'))
			->addField(new Field('duo.node.listing.field.created_at', 'createdAt'))
			->addField(new Field('duo.node.listing.field.modified_at', 'modifiedAt'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index")
	 * @Method("GET")
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="add")
	 * @Method({"POST", "GET"})
	 */
	public function addAction(Request $request)
	{
		return $this->doAddAction($request);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function editAction(Request $request, int $id)
	{
		return $this->doEditAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function destroyAction(Request $request, int $id = null)
	{
		return $this->doDestroyAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duplicate", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function duplicateAction(Request $request, int $id)
	{
		return $this->doDuplicateAction($request, $id);
	}

    /**
     * {@inheritdoc}
     *
     * @Route("/version/{id}/", name="version", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
     */
    public function versionAction(Request $request, int $id)
    {
        return $this->doVersionAction($request, $id);
    }

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/revert/{id}", name="revert", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
    public function revertAction(Request $request, int $id)
	{
		return $this->doRevertAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="delete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function deleteAction(Request $request, int $id = null)
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="undelete", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function undeleteAction(Request $request, int $id = null)
	{
		return $this->doUndeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="move_up", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function moveUpAction(Request $request, int $id)
	{
		return $this->doMoveUpAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-down/{id}", name="move_down", requirements={ "id" = "\d+" })
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
	 *     name="move_to",
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
	 * @Route("/publish/{id}", name="publish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function publishAction(Request $request, int $id)
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/unpublish/{id}", name="unpublish", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
	 */
	public function unpublishAction(Request $request, int $id)
	{
		return $this->doUnpublishAction($request, $id);
	}
}