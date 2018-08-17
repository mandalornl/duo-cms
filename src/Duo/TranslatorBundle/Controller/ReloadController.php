<?php

namespace Duo\TranslatorBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Duo\TranslatorBundle\Entity\Entry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class ReloadController extends Controller
{
	/**
	 * Reload action
	 *
	 * @Route("/settings/translation/reload", name="duo_translator_reload", methods={ "GET" })
	 *
	 * @param KernelInterface $kernel
	 *
	 * @return Response
	 */
	public function reloadAction(KernelInterface $kernel): Response
	{
		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(Entry::class);

		$repository
			->createQueryBuilder('e')
			->update()
			->set('e.flag', ':flag')
			->where('e.flag <> :flag')
			->setParameter('flag', Entry::FLAG_NONE)
			->getQuery()
			->execute();

		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput([
			'command' => 'duo:translator:cache',
			'--clear' => null
		]);

		try
		{
			$application->run($input);

			$this->addFlash('success', $this->get('translator')->trans('duo.translator.reload_success'));
		}
		catch (\Exception $e)
		{
			$this->addFlash('error', $this->get('translator')->trans('duo.translator.reload_error'));
		}

		return $this->redirectToRoute('duo_translator_listing_entry_index');
	}
}