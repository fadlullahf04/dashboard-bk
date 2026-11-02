<?php

namespace App\Http\Controllers;

use App\Models\CaseCategoryItem;
use App\Models\CounselingRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CaseCategoryController extends Controller
{
    /**
     * Display the Case Category Management page.
     */
    public function index(): View
    {
        $categories = CaseCategoryItem::allResolved();

        $caseCounts = CounselingRecord::query()
            ->selectRaw('case_category, count(*) as total')
            ->groupBy('case_category')
            ->pluck('total', 'case_category')
            ->all();

        return view('categories.index', [
            'categories' => $categories,
            'caseCounts' => $caseCounts,
            'colorThemes' => CaseCategoryItem::colorThemes(),
        ]);
    }

    /**
     * Store a newly created case category.
     */
    public function store(Request $request): RedirectResponse
    {
        CaseCategoryItem::allResolved();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:case_categories,name'],
            'color' => ['required', Rule::in(array_keys(CaseCategoryItem::colorThemes()))],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori case wajib diisi.',
            'name.unique' => 'Nama kategori case tersebut sudah terdaftar.',
        ]);

        $category = CaseCategoryItem::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori case \"{$category->name}\" berhasil ditambahkan.");
    }

    /**
     * Update an existing case category and synchronize existing counseling records.
     */
    public function update(Request $request, CaseCategoryItem $caseCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('case_categories', 'name')->ignore($caseCategory->id)],
            'color' => ['required', Rule::in(array_keys(CaseCategoryItem::colorThemes()))],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori case wajib diisi.',
            'name.unique' => 'Nama kategori case tersebut sudah digunakan.',
        ]);

        $oldName = $caseCategory->name;
        $caseCategory->update($validated);

        if ($oldName !== $validated['name']) {
            CounselingRecord::where('case_category', $oldName)
                ->update(['case_category' => $validated['name']]);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori case \"{$caseCategory->name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified case category if it is not currently in use.
     */
    public function destroy(CaseCategoryItem $caseCategory): RedirectResponse
    {
        $usageCount = CounselingRecord::where('case_category', $caseCategory->name)->count();

        if ($usageCount > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Kategori \"{$caseCategory->name}\" tidak dapat dihapus karena sedang digunakan oleh {$usageCount} data kasus siswa.");
        }

        if (CaseCategoryItem::count() <= 1) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Minimal harus terdapat 1 kategori case aktif di dalam sistem.');
        }

        $name = $caseCategory->name;
        $caseCategory->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori case \"{$name}\" berhasil dihapus.");
    }
}

