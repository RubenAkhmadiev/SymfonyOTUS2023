<?php

namespace App\EventSubscriber\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class MigrationEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postGenerateSchema',
        );
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $Args)
    {
        $Schema = $Args->getSchema();

        if (!$Schema->hasNamespace('public')) {
            $Schema->createNamespace('public');
        }
    }
}
