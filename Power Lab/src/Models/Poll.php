<?php

namespace App\Models;

use DateTime;
use App\Models\ItemPoll;

class Poll
{
    public function __construct(
        private int $id,
        private DateTime $startTime,
        private DateTime $finishTime,
        /**
         * @var ItemPoll[]
         */
        private array $itemPoll
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getFinishTime(): DateTime
    {
        return $this->finishTime;
    }

    public function getItemPoll(): array
    {
        return $this->itemPoll;
    }

    public function addItemInThisPoll(ItemPoll $itemInThisPoll): void
    {
        if ($itemInThisPoll == null) {
            throw new \InvalidArgumentException("item poll is null.");
        }
        $this->itemPoll[] = $itemInThisPoll;
    }
}
?>