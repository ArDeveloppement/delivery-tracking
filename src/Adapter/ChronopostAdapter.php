<?php

namespace ArDev\DeliveryTracking\Adapter;

use ArDev\DeliveryTracking\Behavior\ChronopostCodesTransformer;
use ArDev\DeliveryTracking\Behavior\ExceptionThrower;
use ArDev\DeliveryTracking\DeliveryEvent;
use ArDev\DeliveryTracking\DeliveryServiceInterface;
use ArDev\DeliveryTracking\DeliveryStatus;
use ArDev\DeliveryTracking\Exception\UnsupportedFeatureException;
use \DateTime;

/**
 * Class ChronopostAdapter
 */
class ChronopostAdapter implements DeliveryServiceInterface
{
    use ExceptionThrower, ChronopostCodesTransformer;

    const BASE_URL = <<<'EOT'
https://www.chronopost.fr/tracking-cxf/TrackingServiceWS/trackSkybill?language=fr_FR&skybillNumber=%s
EOT;

    /**
     * @param string $trackingNumber
     *
     * @return DeliveryStatus
     */
    public function getDeliveryStatus($trackingNumber)
    {
        return $this->getLastEvent($trackingNumber)->getStatus();
    }

    /**
     * @param string $trackingNumber
     *
     * @return DeliveryStatus
     */
    public function getDeliveredStatusIfExists($trackingNumber)
    {
        return $this->getDeliveredEventIfExists($trackingNumber)->getStatus();
    }

    /**
     * @param array $trackingNumbers
     *
     * @return array | DeliveryStatus[]
     */
    public function getDeliveryStatuses($trackingNumbers)
    {
        $statuses = [];

        foreach ($trackingNumbers as $trackingNumber) {
            $statuses[$trackingNumber] = $this->getDeliveryStatus($trackingNumber);
        }

        return $statuses;
    }

    /**
     * @param string $trackingNumber
     *
     * @return DeliveryEvent
     */
    public function getLastEvent($trackingNumber)
    {
        $fp = fopen(sprintf(self::BASE_URL, $trackingNumber), 'r');
        $xml = stream_get_contents($fp);
        fclose($fp);
        $xml = new \SimpleXMLElement($xml);

        /* Registering needed namespaces. See http://stackoverflow.com/questions/10322464/ */
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns1', 'http://cxf.tracking.soap.chronopost.fr/');

        /** @var null | DeliveryEvent $lastEvent */
        $lastEvent = null;

        $events = $xml->xpath('//soap:Body/ns1:trackSkybillResponse/return/listEvents/events');

        if (empty($events)) {
            $this->throwDataNotFoundException();
        }

        /* XPathing on namespaced XMLs can't be relative */
        foreach ($events as $event) {
            if (isset($event->eventDate) && isset($event->code)) {
                $currentEvent = new DeliveryEvent(
                    $trackingNumber,
                    new DateTime(trim($event->eventDate)),
                    $this->getStateFromCode(trim($event->code))
                );

                if ($lastEvent === null || $lastEvent->getEventDate() < $currentEvent->getEventDate()) {
                    $lastEvent = $currentEvent;
                }
            }
        }

        return $lastEvent;
    }

    /**
     * @param string $trackingNumber
     *
     * @return DeliveryEvent
     */
    public function getDeliveredEventIfExists($trackingNumber)
    {
        $fp = fopen(sprintf(self::BASE_URL, $trackingNumber), 'r');
        $xml = stream_get_contents($fp);
        fclose($fp);
        $xml = new \SimpleXMLElement($xml);

        /* Registering needed namespaces. See http://stackoverflow.com/questions/10322464/ */
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns1', 'http://cxf.tracking.soap.chronopost.fr/');


        $events = $xml->xpath('//soap:Body/ns1:trackSkybillResponse/return/listEvents/events');

        if (empty($events)) {
            $this->throwDataNotFoundException();
        }

        $event = $this->isDeliveredStateFromEvents($events);

        return new DeliveryEvent(
            $trackingNumber,
            new DateTime(trim($event->eventDate)),
            $this->getStateFromCode(trim($event->code))
        );
    }

    /**
     * @param array $trackingNumbers
     *
     * @return array | DeliveryEvent[]
     */
    public function getLastEventForMultipleDeliveries($trackingNumbers)
    {
        $events = [];

        foreach ($trackingNumbers as $trackingNumber) {
            $events[$trackingNumber] = $this->getLastEvent($trackingNumber);
        }

        return $events;
    }

    /**
     * @param string $reference
     *
     * @return void
     * @throws UnsupportedFeatureException
     */
    public function getTrackingNumberByInternalReference($reference)
    {
        $this->throwUnsupportedFeatureException();
    }

    /**
     * @param array $references
     *
     * @return void
     * @throws UnsupportedFeatureException
     */
    public function getTrackingNumbersByInternalReferences($references)
    {
        $this->throwUnsupportedFeatureException();
    }
}
