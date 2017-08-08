<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\ProductData\Requests\ShopPropertiesRequest;
use Affilinet\ProductData\Requests\ShopsRequest;
use Affilinet\ProductData\Responses\ShopPropertiesResponseInterface;
use Affilinet\ProductData\Responses\ShopsResponseInterface;

/**
 * Class ShopService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ShopService {

    protected $clientService;
    protected $shopsRequest;
    protected $shopPropertiesRequest;

    /**
     * ShopService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ProductClientService $clientService
     */
    public function __construct(ProductClientService $clientService) {
        $this->clientService = $clientService;
        $this->shopsRequest = new ShopsRequest($clientService->getClient());
        $this->shopPropertiesRequest = new ShopPropertiesRequest($clientService->getClient());
    }

    /**
     * @param $keyword null|string Keyword to search for - if null no keyword is set and all available shops will be list
     * @param $logoSizes null|array<int> The sizes of the logo to include
     *
     * @return ShopsResponseInterface
     */
    public function getShops($keyword = null, $logoSizes = array()) {

        if ($keyword != null && is_string($keyword)) {
            $this->shopsRequest->onlyShopsMatchingKeyword($keyword);
        }
        foreach ($logoSizes as $logoSize) {
            $this->includeShopLogo($logoSize);
        }

        return $this->shopsRequest->send();
    }

    /**
     * @param $shopId int The Shop ID to get the properties for
     *
     * @return ShopPropertiesResponseInterface
     */
    public function getShopProperties($shopId) {

        $this->shopPropertiesRequest->setShopId($shopId);

        return $this->shopPropertiesRequest->send();
    }

    /**
     * @param $keyword null|string Keyword to search for - if null no keyword is set and all available shops will be count
     *
     * @return integer
     */
    public function getShopsCount($keyword = null) {
        return $this->getShops($keyword)->totalRecords();
    }

    /**
     * @param $logoSize int The size of the logo to include
     */
    protected function includeShopLogo($logoSize) {

        switch ($logoSize) {
            case 468:
                $this->shopsRequest->addShopLogoWithSize468px();
                break;
            case 150:
                $this->shopsRequest->addShopLogoWithSize150px();
                break;
            case 120:
                $this->shopsRequest->addShopLogoWithSize120px();
                break;
            case 90:
                $this->shopsRequest->addShopLogoWithSize90px();
                break;
            case 50:
                $this->shopsRequest->addShopLogoWithSize50px();
                break;
        }
    }

    public function __toString() {
        return 'ShopService';
    }

}

?>