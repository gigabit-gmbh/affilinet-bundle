<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\PublisherData\Requests\ProgramsRequest;

/**
 * Class ProgramsService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class ProgramService {

    protected $clientService;
    protected $programsRequest;

    /**
     * ProgramsService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param PublisherClientService $clientService
     */
    public function __construct(PublisherClientService $clientService) {
        $this->clientService = $clientService;
        $this->programsRequest = new ProgramsRequest($clientService->getClient());
    }

    protected function initiateRequest() {
        $this->programsRequest = new ProgramsRequest($this->clientService->getClient());
    }

    /**
     * @return \Affilinet\PublisherData\Responses\ProgramsResponse
     */
    public function getPrograms($page = 1, $pageSize = 100, $partnerShipStatus = array('Active')) {
        $this->initiateRequest();

        $this->programsRequest->setProgramsQuery(array(
            'PartnershipStatus' => $partnerShipStatus,
        ));

        $this->programsRequest->setPageSize($pageSize);
        $this->programsRequest->setPage($page);

        return $this->programsRequest->getPrograms();
    }

    /**
     * @return \Affilinet\PublisherData\Responses\ProgramCategoriesResponse
     */
    public function getProgramCategories() {
        $this->initiateRequest();

        return $this->programsRequest->getProgramCategories();
    }


    public function __toString() {
        return 'ProgramsService';
    }

}

?>