<?php

namespace Gigabit\Affilinetbundle\Service;

use Affilinet\ProductData\AffilinetProductClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProductClientService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ProductClientService {

    protected $container;
    protected $affilinetClient;

    /**
     * ClientService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ContainerInterface $container
     * @param $publisherId
     * @param $password
     */
    public function __construct(ContainerInterface $container, $publisherId, $password) {
        $this->container = $container;

        $config = [
            'publisher_id' => $publisherId,
            'webservice_password' => $password,
        ];

        $this->affilinetClient = new AffilinetProductClient($config);
    }

    /**
     * @return AffilinetProductClient
     */
    public function getClient() {
        return $this->affilinetClient;
    }

    public function __toString() {
        return 'ClientService';
    }

}

?>