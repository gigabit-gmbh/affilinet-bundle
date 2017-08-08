<?php

namespace Gigabit\Affilinetbundle\Service;

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
class ProductService {

    protected $clientService;
    protected $productRequest;

    /**
     * ShopService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param ProductClientService $clientService
     */
    public function __construct(ProductClientService $clientService) {
        $this->clientService = $clientService;
        $this->productRequest = new ProductsRequest($clientService->getClient());
    }

    /**
     * @param $keyword string Keyword to search for - if null no keyword is set and all available products will be list
     * @param $onlyWithImages boolean if true, results include only products with images
     * @param $productImage int|array<int> The Product image(s) to load
     * @param $excludedShopIds array<int>|null This ShopIds will be excluded
     * @param $maxPrice float|int|string|null The maximum price of the listed products
     * @param $minPrice float|int|string|null The minimum price of the listed products
     * @param $sort string|null The sort parameter
     * @param $sortDescending boolean The direction to sort - true for descending, false for ascending
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function searchProducts($keyword, $onlyWithImages = true, $productImage = ProductImageType::TYPE_ORIGINAL, $maxPrice = null, $minPrice = null, $excludedShopIds = null, $sort = null, $sortDescending = true) {

        return $this->searchPaginatedProducts($keyword, null, null, $onlyWithImages, $productImage, $maxPrice, $minPrice, $excludedShopIds, $sort, $sortDescending);
    }

    /**
     * @param $keyword string Keyword to search for - if null no keyword is set and all available products will be list
     * @param $page int|null If you want a paginated result, define the current page number here
     * @param $pageSize int The amount of results per page, default = 20
     * @param $onlyWithImages boolean if true, results include only products with images
     * @param $productImage int|array<int> The Product image(s) to load
     * @param $excludedShopIds array<int>|null This ShopIds will be excluded
     * @param $maxPrice float|int|string|null The maximum price of the listed products
     * @param $minPrice float|int|string|null The minimum price of the listed products
     * @param $sort string|null The sort parameter
     * @param $sortDescending boolean The direction to sort - true for descending, false for ascending
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function searchPaginatedProducts($keyword, $page = null, $pageSize = 20, $onlyWithImages = true, $productImage = ProductImageType::TYPE_ORIGINAL, $maxPrice = null, $minPrice = null, $excludedShopIds = null, $sort = null, $sortDescending = true) {

        $this->productRequest->addRawQuery($keyword);
        $this->productRequest->onlyWithImage($onlyWithImages);

        if (is_int($productImage)) {
            $this->productRequest->addAllProductImages();
        } else {
            if (is_array($productImage)) {
                foreach ($productImage as $image) {
                    $this->includeProductImage($image);
                }
            }
        }
        if (is_array($excludedShopIds)) {
            $this->productRequest->excludeShopIds($excludedShopIds);
        }

        if ($maxPrice) {
            $this->productRequest->maxPrice($maxPrice);
        }

        if ($minPrice) {
            $this->productRequest->minPrice($minPrice);
        }

        if ($page && $pageSize) {
            $this->productRequest->page($page);
            $this->productRequest->pageSize($pageSize);
        }

        if ($sort) {
            $this->productRequest->sort($sort, $sortDescending);
        }


        return $this->productRequest->send();
    }

    /**
     * @param $query Query
     * @return ResponseInterface
     */
    public function queryProducts($query) {
        return $this->productRequest->query($query)->send();
    }

    /**
     * @param $productIds array<int> The ids of the Products
     *
     * @return ProductsResponse
     * @throws AffilinetProductWebserviceException
     */
    public function getProducts($productIds) {
        $this->productRequest->find($productIds);

        return $this->productRequest->send();
    }

    /**
     * @param $productId int The id of the Product
     *
     * @return Product
     * @throws AffilinetProductWebserviceException
     */
    public function getProduct($productId) {

        return $this->productRequest->findOne($productId);
    }

    /**
     * @param $imageType int The size of the logo to include
     */
    protected function includeProductImage($imageType) {

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


    public function __toString() {
        return 'ProductService';
    }

}

?>