<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\Exceptions\AffilinetProductWebserviceException;
use Affilinet\ProductData\Requests\ProductsRequest;
use Affilinet\ProductData\Responses\ProductsResponse;
use Affilinet\ProductData\Responses\ResponseElements\Product;
use Affilinet\Requests\Helper\Query;
use Affilinet\Responses\ResponseInterface;
use Gigabit\AffilinetBundle\Model\ProductImageType;


/**
 * Class ProductService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ProductService
{

    protected $clientService;

    /** @var  ProductsRequest */
    protected $productRequest;

    /** @var  int|array<int> */
    protected $productImageSettings = ProductImageType::TYPE_ORIGINAL;

    /** @var bool */
    protected $onlyWithImages = true;

    /** @var null|string */
    protected $sort = null;

    /** @var bool */
    protected $sortDescending = true;

    /** @var null|array<int> */
    protected $excludedShopIds = null;

    /**
     * ShopService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ProductClientService $clientService
     */
    public function __construct(ProductClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    protected function initiateRequest()
    {
        $this->productRequest = new ProductsRequest($this->clientService->getClient());
    }

    /**
     * @param $keyword string Keyword to search for - if null no keyword is set and all available products will be list
     * @param $minPrice float|int|string The minimum price of the listed products
     * @param $maxPrice float|int|string The maximum price of the listed products
     * @param int $pageSize The amount of results per page, default = 30
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function searchProductsForMinMaxPrice($keyword, $minPrice, $maxPrice, $pageSize = 30)
    {

        return $this->searchProducts($keyword, 1, $pageSize, $minPrice, $maxPrice);
    }

    /**
     * @param $keyword string Keyword to search for - if null no keyword is set and all available products will be list
     * @param $page int|null If you want a paginated result, define the current page number here
     * @param $pageSize int The amount of results per page, default = 20
     * @param $minPrice float|int|string|null The minimum price of the listed products
     * @param $maxPrice float|int|string|null The maximum price of the listed products
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function searchProducts($keyword, $page = 1, $pageSize = 30, $minPrice = null, $maxPrice = null)
    {

        $this->initiateRequest();

        $this->productRequest->addRawQuery($keyword);
        $this->productRequest->onlyWithImage($this->getOnlyWithImages());

        if (is_int($this->getProductImageSettings())) {
            $this->includeProductImage($this->getProductImageSettings());
        } else {
            if (is_array($this->getProductImageSettings())) {
                foreach ($this->getProductImageSettings() as $image) {
                    $this->includeProductImage($image);
                }
            }
        }

        if (is_array($this->excludedShopIds)) {
            $this->productRequest->excludeShopIds($this->excludedShopIds);
        }

        if ($maxPrice) {
            $this->productRequest->maxPrice($maxPrice);
        }

        if ($minPrice) {
            $this->productRequest->minPrice($minPrice);
        }

        $this->productRequest->page($page);
        $this->productRequest->pageSize($pageSize);

        if ($this->sort) {
            $this->productRequest->sort($this->sort, $this->sortDescending);
        }


        return $this->productRequest->send();
    }

    /**
     * @param $query Query
     * @return ResponseInterface
     */
    public function queryProducts($query)
    {
        $this->initiateRequest();

        return $this->productRequest->query($query)->send();
    }

    /**
     * @param $productIds array<int> The ids of the Products
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function getProducts($productIds)
    {
        $this->initiateRequest();

        $this->productRequest->find($productIds);

        return $this->productRequest->send();
    }

    /**
     * @param $productId int The id of the Product
     *
     * @return Product
     */
    public function getProduct($productId)
    {
        $this->initiateRequest();

        return $this->productRequest->findOne($productId);
    }

    /**
     * @return array|int
     */
    public function getProductImageSettings()
    {
        return $this->productImageSettings;
    }

    /**
     * @param array|int $productImageSettings
     *
     * @return ProductService
     */
    public function setProductImageSettings($productImageSettings)
    {
        $this->productImageSettings = $productImageSettings;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyWithImages()
    {
        return $this->onlyWithImages;
    }

    /**
     * @return bool
     */
    public function getOnlyWithImages()
    {
        return $this->onlyWithImages;
    }

    /**
     * @param bool $onlyWithImages
     *
     * @return ProductService
     */
    public function setOnlyWithImages($onlyWithImages)
    {
        $this->onlyWithImages = $onlyWithImages;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param null|string $sort
     *
     * @return ProductService
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortDescending()
    {
        return $this->sortDescending;
    }

    /**
     * @param bool $sortDescending
     *
     * @return ProductService
     */
    public function setSortDescending($sortDescending)
    {
        $this->sortDescending = $sortDescending;

        return $this;
    }

    /**
     * @return array<int>|null
     */
    public function getExcludedShopIds()
    {
        return $this->excludedShopIds;
    }

    /**
     * @param array<int>|null $excludedShopIds
     *
     * @return ProductService
     */
    public function setExcludedShopIds($excludedShopIds)
    {
        $this->excludedShopIds = $excludedShopIds;

        return $this;
    }

    /**
     * @param int $excludedShopId
     *
     * @return ProductService
     */
    public function addExcludedShopId($excludedShopId)
    {
        if ($this->excludedShopIds == null) {
            $this->excludedShopIds = array();
        }
        $this->excludedShopIds[] = $excludedShopId;

        return $this;
    }

    /**
     * @param $imageType int The size of the logo to include
     */
    protected function includeProductImage($imageType)
    {

        switch ($imageType) {
            case ProductImageType::TYPE_ORIGINAL:
                $this->productRequest->addProductImage();
                break;
            case ProductImageType::TYPE_ALL:
                $this->productRequest->addAllProductImages();
                break;
            case 180:
                $this->productRequest->addProductImageWithSize180px();
                break;
            case 120:
                $this->productRequest->addProductImageWithSize120px();
                break;
            case 90:
                $this->productRequest->addProductImageWithSize90px();
                break;
            case 60:
                $this->productRequest->addProductImageWithSize60px();
                break;
            case 30:
                $this->productRequest->addProductImageWithSize30px();
                break;
        }
    }


    public function __toString()
    {
        return 'ProductService';
    }

}