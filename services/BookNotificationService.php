<?php

declare(strict_types=1);

namespace app\services;

use app\models\Book;
use app\repositories\SubscriptionRepository;

class BookNotificationService
{
    public function __construct(
        private readonly SubscriptionRepository $subscriptions,
        private readonly SmsPilotService $smsPilot,
    ) {
    }

    /**
     * @param int[] $authorIds
     * @return bool true, если все SMS отправлены успешно (или подписчиков нет)
     */
    public function notifyAboutNewBook(Book $book, array $authorIds): bool
    {
        $authorIds = array_values(array_unique(array_map('intval', $authorIds)));
        $phones = $this->subscriptions->findDistinctPhonesByAuthorIds($authorIds);

        if ($phones === []) {
            return true;
        }

        $message = sprintf(
            'Новая книга: «%s» (%d). ISBN: %s',
            $book->title,
            $book->year,
            $book->isbn,
        );

        $allSent = true;
        foreach ($phones as $phone) {
            if (!$this->smsPilot->send($phone, $message)) {
                $allSent = false;
            }
        }

        return $allSent;
    }
}
