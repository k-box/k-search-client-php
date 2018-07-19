<?php

namespace KSearchClient\Serializer;

use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

/**
 * Pre-Deserialize event for @see \KSearchClient\Model\Error\Error
 * 
 * It make sure that data before deserialization into the Error class 
 * is in the expected format
 */
class DeserializeErrorEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'class' => 'KSearchClient\\Model\\Error\\Error',
                'format' => 'json',
                'priority' => 0,
            ),
        );
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();

        if(!is_array($data['data'])){
            // make sure the data property is an array, 
            // as in the serialized object can be 
            // either a string or an array, but 
            // we want it to be an array
            $data['data'] = [$data['data']];
        }

        $event->setData($data);

        return true;
    }
}