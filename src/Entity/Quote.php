<?php

/**
 * Class Quote
 *
 */
class Quote
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var Site
     */
    private $site;
    /**
     * @var Destination
     */
    private $destination;
    /**
     * @var int
     */
    private $dateQuoted;

    /**
     * Quote constructor.
     * @param $id
     * @param $siteId
     * @param $destinationId
     * @param $dateQuoted
     * @throws Exception
     */
    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->site = SiteRepository::getInstance()->getById($siteId);;
        $this->destination = DestinationRepository::getInstance()->getById($destinationId);
        $this->dateQuoted = $dateQuoted;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     * @return Quote
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param Destination $destination
     * @return Quote
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return DateTime|int
     */
    public function getDateQuoted()
    {
        return $this->dateQuoted;
    }

    /**
     * @param $dateQuoted
     *
     * @return $this
     */
    public function setDateQuoted($dateQuoted)
    {
        $this->dateQuoted = $dateQuoted;

        return $this;
    }
}
