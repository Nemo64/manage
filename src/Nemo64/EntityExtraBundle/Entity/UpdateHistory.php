<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:08
 */

namespace Nemo64\EntityExtraBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Nemo64\EntityExtraBundle\Annotation as Extra;

trait UpdateHistory
{
    /**
     * @var UpdateHistory\UpdateHistoryEntry[]|Collection|Selectable
     *
     * @ORM\ManyToMany(targetEntity="Nemo64\EntityExtraBundle\Entity\UpdateHistory\UpdateHistoryEntry", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\OrderBy({"createTime": "ASC"})
     * @JMS\Exclude()
     * @JMS\ReadOnly()
     * @JMS\AccessType("property")
     *
     * @Extra\UpdateHistory()
     */
    private $updateHistoryEntries;

    /**
     * @return UpdateHistory\UpdateHistoryEntry[]|Collection|Selectable
     */
    private function accessUpdateHistoryEntries()
    {
        if (!$this->updateHistoryEntries instanceof Collection) {
            $this->updateHistoryEntries = new ArrayCollection();
        }

        return $this->updateHistoryEntries;
    }

    /**
     * @param UpdateHistory\UpdateHistoryEntry $updateHistoryEntry
     * @return bool
     */
    public function addUpdateHistoryEntry(UpdateHistory\UpdateHistoryEntry $updateHistoryEntry)
    {
        $updateHistoryEntries = $this->accessUpdateHistoryEntries();

        if ($updateHistoryEntries->contains($updateHistoryEntry)) {
            throw new \LogicException("$updateHistoryEntry was already added");
        }

        return $updateHistoryEntries->add($updateHistoryEntry);
    }

    /**
     * @param \DateTime $dateTime
     * @return UpdateHistory\UpdateHistoryEntry[]
     */
    public function getUpdateHistoryEntriesSince(\DateTime $dateTime)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->gte('createTime', $dateTime));
        return $this->accessUpdateHistoryEntries()->matching($criteria)->toArray();
    }
}