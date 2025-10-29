<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('packages.create');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $data['image'] = $path;
        }

        Package::create($data);

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
        return view('packages.edit', compact('package'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($package->image_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($package->image_url);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $data['image'] = $path;
        }

        $package->update($data);

        return redirect()->route('packages.show', $package)
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

        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus');
    }
}
