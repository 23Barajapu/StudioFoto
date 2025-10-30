<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Http\Resources\PackageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index(Request $request)
    {
        $query = Package::query()->with('galleries');

        // Filter by active status
        if ($request->has('active')) {
            $query->active();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $packages = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }

    /**
     * Display the specified package
     */
    public function show($id)
    {
        $package = Package::with(['galleries' => function($query) {
            $query->active()->ordered();
        }])->find($id);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $package,
        ]);
    }

    /**
     * Store a newly created package (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $validated['image'] = $path;
        }

        $package = Package::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil dibuat',
            'data' => $package,
        ], 201);
    }

    /**
     * Update the specified package (Admin only)
     */
    public function update(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_hours' => 'sometimes|required|integer|min:1',
            'photo_count' => 'sometimes|required|integer|min:1',
            'edited_photo_count' => 'sometimes|required|integer|min:0',
            'include_makeup' => 'sometimes|boolean',
            'include_outfit' => 'sometimes|boolean',
            'features' => 'sometimes|nullable|array',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $validated['image'] = $path;
        }

        $package->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil diperbarui',
            'data' => $package->fresh(),
        ]);
    }

    /**
     * Remove the specified package (Admin only)
     */
    public function destroy($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan',
            ], 404);
        }

        // Check if package has bookings
        if ($package->bookings()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak dapat dihapus karena sudah memiliki booking',
            ], 422);
        }

        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil dihapus',
        ]);
    }
}
