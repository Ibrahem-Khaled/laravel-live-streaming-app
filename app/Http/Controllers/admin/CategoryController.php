<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * عرض قائمة الفئات مع فلترة وبحث.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->with('parent', 'children')
            ->when($request->filled('q'), function ($q) use ($request) {
                $like = '%' . $request->q . '%';
                return $q->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->when($request->filled('content_type'), fn ($q) => $q->where('content_type', $request->content_type))
            ->when($request->filled('parent_id'), function ($q) use ($request) {
                if ($request->parent_id === 'null') {
                    return $q->whereNull('parent_id');
                }
                return $q->where('parent_id', $request->parent_id);
            })
            ->ordered()
            ->get();

        // تنظيم الفئات في شجرة
        $rootCategories = $categories->whereNull('parent_id');

        $stats = [
            'total' => Category::count(),
            'active' => Category::active()->count(),
            'featured' => Category::featured()->count(),
        ];

        return view('dashboard.categories.index', compact('categories', 'rootCategories', 'stats'));
    }

    /**
     * نموذج إضافة فئة جديدة.
     */
    public function create(): View
    {
        $categories = Category::ordered()->get();
        return view('dashboard.categories.create', compact('categories'));
    }

    /**
     * حفظ فئة جديدة.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'categories');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'categories');
        } else {
            $data['image'] = null;
        }

        unset($data['image_url']);

        Category::create($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح.');
    }

    /**
     * عرض تفاصيل فئة.
     */
    public function show(Category $category): View
    {
        $category->load('parent', 'children', 'contents');
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * نموذج تعديل فئة.
     */
    public function edit(Category $category): View
    {
        $categories = Category::where('id', '!=', $category->id)
            ->ordered()
            ->get();
        return view('dashboard.categories.edit', compact('category', 'categories'));
    }

    /**
     * تحديث بيانات الفئة.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'categories', $category->image);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'categories', $category->image);
        } elseif ($request->has('remove_image')) {
            $this->imageUploadService->delete($category->image);
            $data['image'] = null;
        }

        unset($data['image_url']);

        $category->update($data);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    /**
     * حذف فئة.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // التحقق من وجود فئات فرعية
        if ($category->children()->exists()) {
            return redirect()
                ->route('dashboard.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على فئات فرعية.');
        }

        // حذف الصورة
        $this->imageUploadService->delete($category->image);

        $category->delete();

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }

    /**
     * تغيير حالة الفئة (تفعيل / إلغاء تفعيل).
     */
    public function toggleStatus(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $category->update(['is_active' => $request->is_active]);

        $message = $request->is_active ? 'تم تفعيل الفئة بنجاح.' : 'تم إلغاء تفعيل الفئة.';

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', $message);
    }

    /**
     * إعادة ترتيب الفئات.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:categories,id'],
            'items.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($request->items as $item) {
            Category::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * إجراءات جماعية.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:categories,id'],
        ]);

        $categories = Category::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $categories->update(['is_active' => true]);
                $message = 'تم تفعيل الفئات المحددة بنجاح.';
                break;

            case 'deactivate':
                $categories->update(['is_active' => false]);
                $message = 'تم إلغاء تفعيل الفئات المحددة.';
                break;

            case 'delete':
                // حذف الصور
                foreach ($categories->get() as $category) {
                    $this->imageUploadService->delete($category->image);
                }
                $categories->delete();
                $message = 'تم حذف الفئات المحددة بنجاح.';
                break;
        }

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', $message);
    }
}
