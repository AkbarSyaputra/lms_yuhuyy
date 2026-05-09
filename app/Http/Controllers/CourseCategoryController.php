<?php

namespace App\Http\Controllers;

use App\Actions\Categories\CreateCategoryAction;
use App\Actions\Categories\DeleteCategoryAction;
use App\Actions\Categories\UpdateCategoryAction;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\CourseCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CourseCategoryController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index(): Response
    {
        $this->authorize('viewAny', CourseCategory::class);

        return Inertia::render('Categories/Index', [
            'categories' => $this->categoryRepository->getAllPaginated(),
            'filters' => request()->only('filter', 'sort'),
        ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): RedirectResponse
    {
        $this->authorize('create', CourseCategory::class);

        $action->execute($request->toDto());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(UpdateCategoryRequest $request, CourseCategory $category, UpdateCategoryAction $action): RedirectResponse
    {
        $this->authorize('update', $category);

        $action->execute($category, $request->toDto());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(CourseCategory $category, DeleteCategoryAction $action): RedirectResponse
    {
        $this->authorize('delete', $category);

        $action->execute($category);

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
