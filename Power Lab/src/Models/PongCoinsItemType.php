<?php

namespace App\Models;

use App\Interfaces\ItemType;

class PongCoinsItemType implements ItemType
{
    public function __construct(
    ) {
    }

    public function getRewardText(): string
    {
        return "Pong Coins";
    }

    public function getRewardSprite(): Image
    {
        return new Image("PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNiAxNiIgc2hhcGUtcmVuZGVyaW5nPSJjcmlzcEVkZ2VzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+CiAgPCEtLSAxNngxNiBQaXhlbCBBcnQgQ29pbiAtLT4KICA8cGF0aCBmaWxsPSIjMmExMDA0IiBkPSJNNCAwaDh2MUg0em0tMiAxaDJ2MUgyem0xMCAwaDJ2MUgxMnptLTMgMmg0djFNOTEgem0tOSAxdjhoMXYtOHptMTQgMHY4aDF2LTh6bS0xMyA4aDJ2MUgzem0xMCAwaDJ2MUgxMnptLTMgMmg0djFNOTEgem0tNSAxdDh2MUg0eiIvPgogIDxwYXRoIGZpbGw9IiNmZmQ3MDAiIGQ9Ik00IDFodDh2MUg0em0tMiAxaDJ2MUgyem0xMCAwaDJ2MUgxMnptLTMgMWgxdjFIN3ptLTV2OGgxdi04em0xMiAwdjhoMXYtOHptLTEwIDhoOHYxSDR6bTIgMWg0djFINnptLTQgMWgydjFIM3ptOCAwaDJ2MUgxMnoiLz4KICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNNCAyaDh2MUg0em0tMiAyaDF2NUgyem0xIDFoMXY0SDN6IiBvcGFjaXR5PSIwLjMiLz4KICA8cGF0aCBmaWxsPSIjZmZjMjAwIiBkPSJNNiA1aDR2MWgtNHptMCAxaDJ2M2gtMnptMiAyaDJ2MWgtMnoiLz4KICA8cGF0aCBmaWxsPSIjOTY0YjAwIiBkPSJNNyA5aDN2MWgtM3ptMy0zaDF2M2gtMXoiLz4KPC9zdmc+", "image/svg+xml");
    }
}
?>