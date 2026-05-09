<?php

namespace App\Http\Controllers;

use App\Actions\Materials\CreateMaterialAction;
use App\Actions\Materials\DeleteMaterialAction;
use App\Actions\Materials\ReorderMaterialAction;
use App\Actions\Materials\UpdateMaterialAction;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Course;
use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaterialController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly MaterialRepositoryInterface $materialRepository,
    ) {}

    public function create(Course $course): Response
    {
        $this->authorize('update', $course);

        return Inertia::render('Materials/Create', [
            'course' => $course,
        ]);
    }

    public function store(StoreMaterialRequest $request, Course $course, CreateMaterialAction $action): RedirectResponse
    {
        $this->authorize('update', $course);

        $action->execute($course->id, $request->toDto());

        return redirect()->route('courses.show', $course)
            ->with('success', 'Material created successfully.');
    }

    public function edit(Course $course, Material $material): Response
    {
        $this->authorize('update', $material);

        return Inertia::render('Materials/Edit', [
            'course' => $course,
            'material' => $material,
        ]);
    }

    public function update(UpdateMaterialRequest $request, Course $course, Material $material, UpdateMaterialAction $action): RedirectResponse
    {
        $this->authorize('update', $material);

        $action->execute($material, $request->toDto());

        return redirect()->route('courses.show', $course)
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Course $course, Material $material, DeleteMaterialAction $action): RedirectResponse
    {
        $this->authorize('delete', $material);

        $action->execute($material);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Material deleted successfully.');
    }

    public function reorder(Request $request, Course $course, ReorderMaterialAction $action): RedirectResponse
    {
        $this->authorize('update', Course::class);

        $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['integer'],
        ]);

        $action->execute($course->id, $request->input('ordered_ids'));

        return back()->with('success', 'Materials reordered.');
    }
}
