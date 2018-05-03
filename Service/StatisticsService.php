<?php

namespace Gigabit\AffilinetBundle\Service;

use Affilinet\PublisherData\Requests\StatisticsRequest;

/**
 * Class StatisticService
 *
 * @author Thomas Helmrich <thomas@gigabit.de>
 */
class StatisticsService
{

    protected $clientService;
    protected $statisticRequest;

    /** @var \DateTime */
    protected $partnerShipStart;


    /**
     * ProgramsService constructor.
     *
     * Initialize the Client with the Publisher ID & Webservice Password
     *
     * @param PublisherClientService $clientService
     * @param string $partnerShipStart
     */
    public function __construct(PublisherClientService $clientService, $partnerShipStart)
    {
        $this->clientService = $clientService;
        $this->statisticRequest = new StatisticsRequest($clientService->getClient());
        $this->partnerShipStart = new \DateTime($partnerShipStart." 00:00:00");
    }

    protected function initiateRequest()
    {
        $this->statisticRequest = new StatisticsRequest($this->clientService->getClient());
    }

    /**
     * @param
     *
     * @return \Affilinet\PublisherData\Responses\SubIdStatisticResponse
     */
    public function getSubIdStatsAllTime($subId = "")
    {
        $this->initiateRequest();

        return $this->statisticRequest->getSubIdStatistics($this->partnerShipStart, new \DateTime(), $subId);
    }

    public function __toString()
    {
        return 'StatisticsService';
    }

}