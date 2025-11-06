<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index()
    {
        $packages = Package::withCount('bookings')
            ->ordered()
            ->paginate(12);

        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        $parentPackages = Package::whereNull('parent_id')
            ->with('children')
            ->ordered()
            ->get();
            
        $categories = Category::orderBy('name')->get();
            
        return view('packages.create', compact('parentPackages', 'categories'));
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'photo_count' => 'required|integer|min:1',
            'edited_photo_count' => 'required|integer|min:0',
            'include_makeup' => 'boolean',
            'include_outfit' => 'boolean',
            'features' => 'nullable|array',
            'parent_id' => 'nullable|exists:packages,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            $data = $request->except(['image', 'categories']);
            $data['slug'] = Str::slug($request->name);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('packages', $filename, 'public');
                $data['image'] = $path;
            }

            $package = Package::create($data);
            $package->categories()->sync($request->categories);
            
            return $package;
        });

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dibuat');
    }

    /**
     * Display the specified package
     */
    public function show(Package $package)
    {
        $package->load(['galleries', 'bookings' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(Package $package)
    {
        $parentPackages = Package::whereNull('parent_id')
            ->with('children')
            ->where('id', '!=', $package->id) // Prevent self-referencing
            ->ordered()
            ->get();
            
        $categories = Category::orderBy('name')->get();
        $package->load('categories');
            
        return view('packages.edit', compact('package', 'parentPackages', 'categories'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'photo_count' => 'required|integer|min:1',
            'edited_photo_count' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'include_makeup' => 'boolean',
            'include_outfit' => 'boolean',
            'features' => 'nullable|array',
            'parent_id' => [
                'nullable',
                'exists:packages,id',
                function ($attribute, $value, $fail) use ($package) {
                    // Prevent circular references
                    if ($package->children()->where('id', $value)->exists()) {
                        $fail('Cannot set a child package as parent.');
                    }
                },
            ],
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($request, $package) {
            $data = $request->except(['image', 'categories', '_method', '_token']);
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($package->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($package->image);
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('packages', $filename, 'public');
                $data['image'] = $path;
            }

            $package->update($data);
            $package->categories()->sync($request->categories);
            
            return $package;
        });

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil diperbarui');
    }

    /**
     * Remove the specified package
     */
    public function destroy(Package $package)
    {
        if ($package->bookings()->count() > 0) {
            return back()->with('error', 'Paket tidak dapat dihapus karena sudah memiliki booking');
        }

        DB::transaction(function () use ($package) {
            $package->categories()->detach();
            $package->delete();
        });

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus');
    }
}
