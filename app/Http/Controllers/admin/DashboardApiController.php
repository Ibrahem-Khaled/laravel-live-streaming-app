<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * بحث المستخدمين لـ Select2 (AJAX).
     * يستخدم UserSearchService ليعيد نفس المنطق في أي مكان.
     */
    public function usersSearch(Request $request, UserSearchService $userSearchService): JsonResponse
    {
        $q = $request->input('q');
        $page = (int) $request->input('page', 1);
        $perPage = min(50, max(10, (int) $request->input('per_page', 20)));

        return response()->json(
            $userSearchService->forSelect2($q, $perPage, $page)
        );
    }
}
