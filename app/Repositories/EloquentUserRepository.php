<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function searchForSelect(?string $query, int $perPage = 20, int $page = 1): array
    {
        $qb = $this->queryActive($query);
        $paginator = $qb->orderBy('name')
            ->paginate($perPage, ['id', 'name', 'email', 'username'], 'page', $page);

        $results = $paginator->getCollection()->map(function (User $user) {
            return [
                'id'   => $user->id,
                'text' => $this->formatUserLabel($user),
            ];
        })->values()->all();

        return [
            'results'    => $results,
            'pagination' => [
                'more' => $paginator->hasMorePages(),
            ],
        ];
    }

    public function findByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return User::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get();
    }

    public function queryActive(?string $search = null)
    {
        $qb = User::query()->active();

        if ($search !== null && $search !== '') {
            $qb->search($search);
        }

        return $qb;
    }

    /**
     * تنسيق تسمية المستخدم للعرض في القوائم.
     */
    protected function formatUserLabel(User $user): string
    {
        $parts = array_filter([
            $user->name,
            $user->username ? "(@{$user->username})" : null,
            $user->email,
        ]);

        return implode(' — ', $parts);
    }
}
