<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Subscription;

class SubscriptionRepository
{
    public function findDistinctPhonesByAuthorIds(array $authorIds): array
    {
        if ($authorIds === []) {
            return [];
        }

        return Subscription::find()
            ->select('phone')
            ->where(['author_id' => $authorIds])
            ->distinct()
            ->column();
    }

    public function save(Subscription $subscription): bool
    {
        return $subscription->save();
    }
}
