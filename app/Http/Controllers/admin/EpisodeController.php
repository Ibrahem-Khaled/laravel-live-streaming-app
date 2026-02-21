<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Content;
use App\Models\Episode;
use App\Services\ImageUploadService;
use App\Services\VideoUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EpisodeController extends Controller
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
     * عرض قائمة الحلقات.
     */
    public function index(Request $request, ?Content $content = null): View
    {
        $episodes = Episode::query()
            ->with('content')
            ->when($content, fn ($q) => $q->where('content_id', $content->id))
            ->when($request->filled('content_id'), fn ($q) => $q->where('content_id', $request->content_id))
            ->when($request->filled('season_number'), fn ($q) => $q->where('season_number', $request->season_number))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $contents = Content::series()->ordered()->get();

        return view('dashboard.episodes.index', compact('episodes', 'content', 'contents'));
    }

    /**
     * نموذج إضافة حلقة جديدة.
     */
    public function create(?Content $content = null): View
    {
        $contents = Content::series()->ordered()->get();
        return view('dashboard.episodes.create', compact('content', 'contents'));
    }

    /**
     * حفظ حلقة جديدة.
     */
    public function store(StoreEpisodeRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'episodes');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'episodes');
        } else {
            $data['image'] = null;
        }

        unset($data['image_url']);

        // معالجة رابط/ملف البث
        if ($request->hasFile('stream_file')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->file('stream_file'), 'episodes');
        } elseif ($request->filled('stream_url')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->stream_url, 'episodes');
        }

        unset($data['stream_file']);

        $episode = Episode::create($data);
        $episode->load('content');

        // إذا كان طلب AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحلقة بنجاح.',
                'episode' => $episode,
            ]);
        }

        return redirect()
            ->route('dashboard.contents.episodes.index', $episode->content_id)
            ->with('success', 'تم إنشاء الحلقة بنجاح.');
    }

    /**
     * نموذج تعديل حلقة.
     */
    public function edit(Episode $episode): View
    {
        $contents = Content::series()->ordered()->get();
        return view('dashboard.episodes.edit', compact('episode', 'contents'));
    }

    /**
     * تحديث بيانات الحلقة.
     */
    public function update(UpdateEpisodeRequest $request, Episode $episode): RedirectResponse|JsonResponse
    {
        $data = $request->validated();

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'episodes', $episode->image);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'episodes', $episode->image);
        } elseif ($request->has('remove_image')) {
            $this->imageUploadService->delete($episode->image);
            $data['image'] = null;
        }

        unset($data['image_url']);

        // معالجة رابط/ملف البث
        if ($request->hasFile('stream_file')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->file('stream_file'), 'episodes', $episode->stream_url);
        } elseif ($request->filled('stream_url')) {
            $data['stream_url'] = $this->videoUploadService->handle($request->stream_url, 'episodes', $episode->stream_url);
        } elseif ($request->has('remove_stream')) {
            $this->videoUploadService->delete($episode->stream_url);
            $data['stream_url'] = null;
        }

        unset($data['stream_file']);

        $episode->update($data);
        $episode->load('content');

        // إذا كان طلب AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحلقة بنجاح.',
                'episode' => $episode,
            ]);
        }

        return redirect()
            ->route('dashboard.contents.episodes.index', $episode->content_id)
            ->with('success', 'تم تحديث الحلقة بنجاح.');
    }

    /**
     * حذف حلقة.
     */
    public function destroy(Episode $episode): RedirectResponse|JsonResponse
    {
        $contentId = $episode->content_id;

        // حذف الصورة
        $this->imageUploadService->delete($episode->image);

        // حذف ملف البث
        $this->videoUploadService->delete($episode->stream_url);

        $episode->delete();

        // إذا كان طلب AJAX
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الحلقة بنجاح.',
            ]);
        }

        return redirect()
            ->route('dashboard.contents.episodes.index', $contentId)
            ->with('success', 'تم حذف الحلقة بنجاح.');
    }

    /**
     * إعادة ترتيب الحلقات.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:episodes,id'],
            'items.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($request->items as $item) {
            Episode::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * معاينة الحلقة.
     */
    public function preview(Episode $episode): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $episode->load('content');
        $streamUrl = $episode->stream_url ? $this->resolveStreamUrl($episode->stream_url) : null;
        if (!$streamUrl) {
            return redirect()->back()->with('error', 'لا يوجد رابط بث لهذه الحلقة.');
        }
        return view('dashboard.player.preview', [
            'title' => $episode->display_title,
            'streamUrl' => $streamUrl,
            'content' => $episode->content,
            'episode' => $episode,
            'episodes' => $episode->content->episodes()->ordered()->get(),
            'isSeries' => true,
        ]);
    }

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
