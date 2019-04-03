<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\VideoPagePart;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class VideoPagePartType extends AbstractPartType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * VideoPagePartType constructor
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('value', TextType::class, [
			'label' => false,
			'attr' => [
				'placeholder' => $this->translator->trans('app.form.video_page_part.value.placeholder')
			]
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return VideoPagePart::class;
	}
}
