<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\PublisherData\Requests\CreativeRequest;
use Affilinet\PublisherData\Responses\CreativeCategoryResponse;
use Affilinet\PublisherData\Responses\CreativesResponse;

/**
 * Class CreativeService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class CreativeService
{

    protected $clientService;

    /** @var CreativeRequest */
    protected $request;

    /**
     * CreativeService constructor.
     *
     * Initialize the Client with the Publisher ID & Publisher Password
     *
     * @param PublisherClientService $clientService
     */
    public function __construct(PublisherClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    protected function initiateRequest()
    {
        $this->request = new CreativeRequest($this->clientService->getClient());
    }

    /**
     * @param array $programIds
     * @param array $types
     * @param int $page
     * @param int $pageSize
     *
     * @return CreativesResponse
     */
    public function searchCreatives(
        $programIds = array(0),
        $types = array(CreativeRequest::TYPE_Text),
        $page = 1,
        $pageSize = 99
    ) {
        $this->initiateRequest();

        $this->request->setCreativeTypes($types);
        $this->request->setPage($page);
        $this->request->setPageSize($pageSize);
        $this->request->setProgramIds($programIds);

        return $this->request->searchCreatives();
    }


    /**
     * @param array $categories
     * @param array $programIds
     * @param array $types
     * @param int $page
     * @param int $pageSize
     *
     * @return CreativesResponse
     */
    public function searchCreativesForProgramAndCreativeCategories(
        $categories,
        $programIds,
        $types = array(CreativeRequest::TYPE_Text),
        $page = 1,
        $pageSize = 99
    ) {
        $this->initiateRequest();

        $this->request->setCreativeTypes($types);
        $this->request->setPage($page);
        $this->request->setPageSize($pageSize);
        $this->request->setProgramIds($programIds);
        $this->request->setCategoryIds($categories);

        return $this->request->searchCreatives();
    }

    /**
     * @param int $programId
     *
     * @return CreativeCategoryResponse
     */
    public function getCategories($programId)
    {
        $this->initiateRequest();

        return $this->request->getCreativeCategories($programId);
    }


    public function __toString()
    {
        return 'CreativeService';
    }

}