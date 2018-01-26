<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Configuration\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\BehaviorBundle\Controller\DeleteInterface;
use Duo\BehaviorBundle\Controller\DeleteTrait;
use Duo\BehaviorBundle\Controller\DuplicateInterface;
use Duo\BehaviorBundle\Controller\DuplicateTrait;
use Duo\BehaviorBundle\Controller\PublishInterface;
use Duo\BehaviorBundle\Controller\PublishTrait;
use Duo\BehaviorBundle\Controller\SortInterface;
use Duo\BehaviorBundle\Controller\SortTrait;
use Duo\BehaviorBundle\Controller\RevisionInterface;
use Duo\BehaviorBundle\Controller\RevisionTrait;
use Duo\PageBundle\Configuration\Filter\OnlineFilter;
use Duo\PageBundle\Form\PageListingType;
use Duo\PageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_page_listing_page_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageListingController extends AbstractListingController implements DuplicateInterface, PublishInterface, DeleteInterface, SortInterface, RevisionInterface
{
	use DeleteTrait;
	use DuplicateTrait;
	use PublishTrait;
	use SortTrait;
	use RevisionTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Page::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return PageListingType::class;
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
		$this
			->addFilter(new StringFilter('name', 'duo.page.listing.filter.name'))
			->addFilter(new StringFilter('title', 'duo.page.listing.filter.title', 't'))
			->addFilter(new StringFilter('url', 'duo.page.listing.filter.url', 't'))
			->addFilter(new OnlineFilter('online', 'duo.page.listing.filter.online', 't'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.page.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.page.listing.filter.modified'));
	}

	/**
	 * Define fields
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.page.listing.field.name'))
			->addField(new Field('title', 'duo.page.listing.field.title', true, null, 't'))
			->addField(new Field('url', 'duo.page.listing.field.url', true, '@DuoAdmin/Listing/Field/url.html.twig', 't'))
			->addField(new Field('published', 'duo.page.listing.field.online', false, '@DuoAdmin/Listing/Field/published.html.twig'))
			->addField(new Field('createdAt', 'duo.page.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.page.listing.field.modified_at'));
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
     * @Route("/revision/{id}/", name="revision", requirements={ "id" = "\d+" })
	 * @Method({"POST", "GET"})
     */
    public function revisionAction(Request $request, int $id)
    {
        return $this->doRevisionAction($request, $id);
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