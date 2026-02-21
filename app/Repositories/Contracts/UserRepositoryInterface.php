<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * البحث عن مستخدمين للعرض في قوائم (Select2 وغيرها).
     * يرجع مصفوفة من ['id' => id, 'text' => label] مع دعم الترقيم.
     *
     * @param string|null $query نص البحث (اسم، بريد، username)
     * @param int $perPage عدد النتائج في الصفحة
     * @param int $page الصفحة
     * @return array{results: array<int, array{id: int, text: string}>, pagination: array{more: bool}}
     */
    public function searchForSelect(?string $query, int $perPage = 20, int $page = 1): array;

    /**
     * الحصول على مستخدمين حسب IDs (للتحقق أو العرض).
     *
     * @param array<int> $ids
     * @return Collection<int, User>
     */
    public function findByIds(array $ids): Collection;

    /**
     * بناء استعلام المستخدمين النشطين مع دعم البحث.
     */
    public function queryActive(?string $search = null);
}
