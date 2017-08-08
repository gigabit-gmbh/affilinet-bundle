<?php

namespace Gigabit\Affilinetbundle\Service;

use Affilinet\ProductData\Requests\CategoriesRequest;
use Affilinet\ProductData\Responses\CategoriesResponseInterface;

/**
 * Class CategoryService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class CategoryService {

    protected $clientService;
    protected $shopCategoriesRequest;

    /**
     * CategoryService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ProductClientService $clientService
     */
    public function __construct(ProductClientService $clientService) {
        $this->clientService = $clientService;
        $this->shopCategoriesRequest = new CategoriesRequest($clientService->getClient());
    }

    /**
     * @return CategoriesResponseInterface
     */
    public function getCategories() {

        $this->shopCategoriesRequest->getAffilinetCategories();

        return $this->shopCategoriesRequest->send();
    }

    /**
     * @param $shopId int The Shop ID to get the properties for
     *
     * @return CategoriesResponseInterface
     */
    public function getShopCategories($shopId) {

        $this->shopCategoriesRequest->setShopId($shopId);

        return $this->shopCategoriesRequest->send();
    }

    public function __toString() {
        return 'CategoryService';
    }

}

?>