<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserSearchService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * بحث مستخدمين بصيغة مناسبة لـ Select2 (AJAX).
     * يمكن استخدامها من أي Controller أو مكان آخر.
     *
     * @param string|null $q نص البحث
     * @param int $perPage
     * @param int $page
     * @return array{results: array, pagination: array}
     */
    public function forSelect2(?string $q, int $perPage = 20, int $page = 1): array
    {
        return $this->userRepository->searchForSelect($q, $perPage, $page);
    }

    /**
     * الحصول على مستخدمين حسب معرفاتهم (للتحقق أو إرسال إشعارات وغيرها).
     *
     * @param array<int> $ids
     * @return Collection
     */
    public function getByIds(array $ids): Collection
    {
        return $this->userRepository->findByIds($ids);
    }
}
