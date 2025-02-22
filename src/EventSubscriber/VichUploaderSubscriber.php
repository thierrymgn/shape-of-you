<?php

namespace App\EventSubscriber;

use App\Entity\Outfit;
use App\Entity\WardrobeItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;

class VichUploaderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return ['vich_uploader.pre_upload' => 'onPreUpload'];
    }

    public function onPreUpload(Event $event): void
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        if ($object instanceof Outfit) {
            $fileName = $mapping->getFileName($object);
            $object->setImage('/uploads/outfits/'.$fileName);
        }

        if ($object instanceof WardrobeItem) {
            $fileName = $mapping->getFileName($object);
            $object->setImage('/uploads/wardrobe/'.$fileName);
        }
    }
}
