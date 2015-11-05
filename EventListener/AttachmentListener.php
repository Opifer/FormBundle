<?php

namespace Opifer\FormBundle\EventListener;

use Opifer\EavBundle\Entity\AttachmentValue;
use Opifer\FormBundle\Event\Events;
use Opifer\FormBundle\Event\FormSubmitEvent;
use Opifer\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AttachmentListener implements EventSubscriberInterface
{
    /** @var MediaManagerInterface */
    protected $mediaManager;

    function __construct(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::POST_FORM_SUBMIT => 'postFormSubmit',
        ];
    }

    public function postFormSubmit(FormSubmitEvent $event)
    {
        $post = $event->getPost();
        $values = $post->getValueSet()->getValues();

        foreach ($values as $value)
        {
            if ($value instanceof AttachmentValue) {
                $media = $this->mediaManager->createMedia();
                $media->setFile($value->getFile());
                $media->setProvider('file');

                $value->setAttachment($media);
            }
        }
    }
}
