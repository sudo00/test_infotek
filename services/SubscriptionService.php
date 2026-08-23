<?php

declare(strict_types=1);

namespace app\services;

use app\models\Author;
use app\models\Subscription;
use app\repositories\AuthorRepository;
use app\repositories\SubscriptionRepository;

class SubscriptionService
{
    public function __construct(
        private readonly AuthorRepository $authors,
        private readonly SubscriptionRepository $subscriptions,
    ) {
    }

    public function getAuthor(int $authorId): Author
    {
        return $this->authors->getById($authorId);
    }

    public function createForm(int $authorId): Subscription
    {
        return new Subscription(['author_id' => $authorId]);
    }

    public function subscribe(Subscription $subscription): bool
    {
        return $this->subscriptions->save($subscription);
    }
}
