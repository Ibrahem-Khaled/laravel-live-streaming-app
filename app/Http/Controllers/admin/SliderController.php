<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageUploadService
    ) {}

    public function index(Request $request): View
    {
        $sliders = Slider::query()
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->is_active == '1'))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'  => Slider::count(),
            'active' => Slider::active()->count(),
        ];

        return view('dashboard.sliders.index', compact('sliders', 'stats'));
    }

    public function create(): View
    {
        return view('dashboard.sliders.create');
    }

    public function store(StoreSliderRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'sliders');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'sliders');
        } else {
            $data['image'] = null;
        }

        unset($data['image_url']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = (int) ($data['order'] ?? 0);

        Slider::create($data);

        return redirect()
            ->route('dashboard.sliders.index')
            ->with('success', 'تم إنشاء السلايدر بنجاح.');
    }

    public function show(Slider $slider): View
    {
        return view('dashboard.sliders.show', compact('slider'));
    }

    public function edit(Slider $slider): View
    {
        return view('dashboard.sliders.edit', compact('slider'));
    }

    public function update(UpdateSliderRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->handle($request->file('image'), 'sliders', $slider->image);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $this->imageUploadService->handle($request->image_url, 'sliders', $slider->image);
        } elseif ($request->filled('remove_image') || $request->boolean('remove_image')) {
            $this->imageUploadService->delete($slider->image);
            $data['image'] = null;
        }

        unset($data['image_url'], $data['remove_image']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = (int) ($data['order'] ?? $slider->order);

        $slider->update($data);

        return redirect()
            ->route('dashboard.sliders.index')
            ->with('success', 'تم تحديث السلايدر بنجاح.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $this->imageUploadService->delete($slider->image);
        $slider->delete();

        return redirect()
            ->route('dashboard.sliders.index')
            ->with('success', 'تم حذف السلايدر بنجاح.');
    }

    public function toggleStatus(Request $request, Slider $slider): RedirectResponse
    {
        $request->validate(['is_active' => ['required', 'boolean']]);
        $slider->update(['is_active' => $request->boolean('is_active')]);

        $msg = $slider->is_active ? 'تم تفعيل السلايدر.' : 'تم إيقاف السلايدر.';

        return redirect()
            ->route('dashboard.sliders.index')
            ->with('success', $msg);
    }
}
