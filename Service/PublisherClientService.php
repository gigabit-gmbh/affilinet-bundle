<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\PublisherData\AffilinetPublisherClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PublisherClientService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class PublisherClientService {

    protected $container;
    protected $affilinetClient;

    /**
     * PublisherClientService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ContainerInterface $container
     * @param $publisherId
     * @param $password
     *
     * @throws
     */
    public function __construct(ContainerInterface $container, $publisherId, $password) {
        $this->container = $container;

        $config = [
            'publisher_id' => $publisherId,
            'webservice_password' => $password,
        ];

        $this->affilinetClient = new AffilinetPublisherClient($config);
    }

    /**
     * @return AffilinetPublisherClient
     */
    public function getClient() {
        return $this->affilinetClient;
    }

    public function __toString() {
        return 'PublisherClientService';
    }

}