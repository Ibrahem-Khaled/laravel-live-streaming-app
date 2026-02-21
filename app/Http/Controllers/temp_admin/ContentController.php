<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Category;
use App\Models\Content;
use App\Services\ImageUploadService;
use App\Services\VideoUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    protected ImageUploadService $imageUploadService;
    protected VideoUploadService $videoUploadService;

    public function __construct(
        ImageUploadService $imageUploadService,
        VideoUploadService $videoUploadService
    ) {
        $this->imageUploadService = $imageUploadService;
        $this->videoUploadService = $videoUploadService;
    }

    /**
     * عرض قائمة المحتويات مع فلترة وبحث.
     */
    public function index(Request $request): View
    {
        $contents = Content::query()
            ->with('category')
            ->search($request->input('q'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $category = Category::find($request->category_id);
                if ($category) {
                    return $q->inCategoryOrDescendants($category);
                }
                return $q->where('category_id', $request->category_id);
            })
            ->when($request->filled('quality'), fn ($q) => $q->where('quality', $request->quality))
            ->when($request->filled('year'), fn ($q) => $q->where('year', $request->year))
            ->when($request->filled('language'), fn ($q) => $q->where('language', $request->language))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->is_active == '1'))
            ->when($request->filled('is_featured'), fn ($q) => $q->where('is_featured', $request->is_featured == '1'))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Content::count(),
            'channels' => Content::channels()->count(),
            'movies' => Content::movies()->count(),
            'series' => Content::series()->count(),
            'live' => Content::live()->count(),
            'active' => Content::active()->count(),
            'featured' => Content::featured()->count(),
        ];

        $categories = Category::ordered()->get();

        return view('dashboard.contents.index', compact('contents', 'stats', 'categories'));
    }

    /**
     * نموذج إضافة محتوى جديد.
     */
    public function create(): View
    {
        $categories = Category::ordered()->get();
        return view('dashboard.contents.create', compact('categories'));
    }

    /**
     * حفظ محتوى جديد.
     */
    public function store(StoreContentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // معالجة الصور
        $imageFields = ['image', 'poster', 'banner'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->imageUploadService->handle($request->file($field), 'contents');
            } elseif ($request->filled($field . '_url')) {
                $data[$field] = $this->imageUploadService->handle($request->input($field . '_url'), 'contents');
            }
            unset($data[$field . '_url']);
        }

        // معالجة رابط/ملف البث
        if ($request->hasFile('stream_file')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->file('stream_file'), 'contents/videos');
        } elseif ($request->filled('stream_url')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->stream_url, 'contents/videos');
        }

        unset($data['stream_file']);

        Content::create($data);

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', 'تم إنشاء المحتوى بنجاح.');
    }

    /**
     * عرض تفاصيل محتوى.
     */
    public function show(Content $content): View
    {
        $content->load('category', 'episodes');
        return view('dashboard.contents.show', compact('content'));
    }

    /**
     * نموذج تعديل محتوى.
     */
    public function edit(Content $content): View
    {
        $categories = Category::ordered()->get();
        $content->load('episodes');
        return view('dashboard.contents.edit', compact('content', 'categories'));
    }

    /**
     * تحديث بيانات المحتوى.
     */
    public function update(UpdateContentRequest $request, Content $content): RedirectResponse
    {
        $data = $request->validated();

        // معالجة الصور
        $imageFields = ['image', 'poster', 'banner'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->imageUploadService->handle($request->file($field), 'contents', $content->$field);
            } elseif ($request->filled($field . '_url')) {
                $data[$field] = $this->imageUploadService->handle($request->input($field . '_url'), 'contents', $content->$field);
            } elseif ($request->has('remove_' . $field)) {
                $this->imageUploadService->delete($content->$field);
                $data[$field] = null;
            }
            unset($data[$field . '_url']);
        }

        // معالجة رابط/ملف البث
        if ($request->hasFile('stream_file')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->file('stream_file'), 'contents/videos', $content->stream_url);
        } elseif ($request->filled('stream_url')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->stream_url, 'contents/videos', $content->stream_url);
        } elseif ($request->has('remove_stream')) {
            $this->videoUploadService->delete($content->stream_url);
            $data['stream_url'] = null;
        }

        unset($data['stream_file']);

        $content->update($data);

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', 'تم تحديث المحتوى بنجاح.');
    }

    /**
     * حذف محتوى.
     */
    public function destroy(Content $content): RedirectResponse
    {
        // حذف الصور
        $this->imageUploadService->delete($content->image);
        $this->imageUploadService->delete($content->poster);
        $this->imageUploadService->delete($content->banner);

        // حذف ملف البث
        $this->videoUploadService->delete($content->stream_url);

        $content->delete();

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', 'تم حذف المحتوى بنجاح.');
    }

    /**
     * تغيير حالة المحتوى (تفعيل / إلغاء تفعيل).
     */
    public function toggleStatus(Request $request, Content $content): RedirectResponse
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $content->update(['is_active' => $request->is_active]);

        $message = $request->is_active ? 'تم تفعيل المحتوى بنجاح.' : 'تم إلغاء تفعيل المحتوى.';

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', $message);
    }

    /**
     * تغيير حالة التميز.
     */
    public function toggleFeature(Request $request, Content $content): RedirectResponse
    {
        $request->validate([
            'is_featured' => ['required', 'boolean'],
        ]);

        $content->update(['is_featured' => $request->is_featured]);

        $message = $request->is_featured ? 'تم تمييز المحتوى بنجاح.' : 'تم إلغاء تمييز المحتوى.';

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', $message);
    }

    /**
     * إعادة ترتيب المحتويات.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:contents,id'],
            'items.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($request->items as $item) {
            Content::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * إجراءات جماعية.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,feature,unfeature,move'],
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:contents,id'],
        ]);

        $contents = Content::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $contents->update(['is_active' => true]);
                $message = 'تم تفعيل المحتويات المحددة بنجاح.';
                break;

            case 'deactivate':
                $contents->update(['is_active' => false]);
                $message = 'تم إلغاء تفعيل المحتويات المحددة.';
                break;

            case 'feature':
                $contents->update(['is_featured' => true]);
                $message = 'تم تمييز المحتويات المحددة بنجاح.';
                break;

            case 'unfeature':
                $contents->update(['is_featured' => false]);
                $message = 'تم إلغاء تمييز المحتويات المحددة.';
                break;

            case 'move':
                $request->validate(['category_id' => ['required', 'exists:categories,id']]);
                $contents->update(['category_id' => $request->category_id]);
                $message = 'تم نقل المحتويات المحددة بنجاح.';
                break;

            case 'delete':
                foreach ($contents->get() as $content) {
                    $this->imageUploadService->delete($content->image);
                    $this->imageUploadService->delete($content->poster);
                    $this->imageUploadService->delete($content->banner);
                    $this->videoUploadService->delete($content->stream_url);
                }
                $contents->delete();
                $message = 'تم حذف المحتويات المحددة بنجاح.';
                break;
        }

        return redirect()
            ->route('dashboard.contents.index')
            ->with('success', $message);
    }

    /**
     * معاينة المحتوى (فيلم / قناة / بث مباشر) أو قائمة حلقات المسلسل.
     */
    public function preview(Content $content): View|RedirectResponse
    {
        $content->load('episodes');

        // للمسلسلات: إذا لم يكن هناك stream_url نعرض قائمة الحلقات للمعاينة
        if ($content->type === Content::TYPE_SERIES) {
            $streamUrl = $content->stream_url;
            $episodes = $content->episodes;
            $firstEpisode = $episodes->first();
            $streamUrl = $streamUrl ? $this->resolveStreamUrl($streamUrl) : ($firstEpisode ? $this->resolveStreamUrl($firstEpisode->stream_url) : null);
            return view('dashboard.player.preview', [
                'title' => $content->name,
                'streamUrl' => $streamUrl,
                'content' => $content,
                'episodes' => $episodes,
                'isSeries' => true,
            ]);
        }

        $streamUrl = $content->stream_url ? $this->resolveStreamUrl($content->stream_url) : null;
        if (!$streamUrl) {
            return redirect()->back()->with('error', 'لا يوجد رابط بث لهذا المحتوى.');
        }

        return view('dashboard.player.preview', [
            'title' => $content->name,
            'streamUrl' => $streamUrl,
            'content' => $content,
            'episodes' => collect(),
            'isSeries' => false,
        ]);
    }

    /**
     * تحويل مسار التخزين إلى URL كامل.
     */
    private function resolveStreamUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}
