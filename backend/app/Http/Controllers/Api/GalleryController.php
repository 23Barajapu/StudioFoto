<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries
     */
    public function index(Request $request)
    {
        $query = Gallery::with('package');

        // Filter by category
        if ($request->has('category')) {
            $query->category($request->category);
        }

        // Filter featured only
        if ($request->has('featured')) {
            $query->featured();
        }

        // Filter active only
        if ($request->has('active')) {
            $query->active();
        }

        // Filter by package
        if ($request->has('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        $galleries = $query->ordered()->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }

    /**
     * Display the specified gallery
     */
    public function show($id)
    {
        $gallery = Gallery::with('package')->find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $gallery,
        ]);
    }

    /**
     * Store a newly created gallery (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'package_id' => 'nullable|exists:packages,id',
            'category' => 'required|in:wedding,prewedding,portrait,product,event,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['image']);

            // Upload image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('galleries', $filename, 'public');
                $data['image_path'] = $path;

                // Create thumbnail (optional - requires intervention/image package)
                // For now, we'll use the same path
                $data['thumbnail_path'] = $path;
            }

            $gallery = Gallery::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil ditambahkan',
                'data' => $gallery,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah gambar',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified gallery (Admin only)
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'package_id' => 'nullable|exists:packages,id',
            'category' => 'sometimes|required|in:wedding,prewedding,portrait,product,event,other',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['image']);

            // Upload new image if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }

                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('galleries', $filename, 'public');
                $data['image_path'] = $path;
                $data['thumbnail_path'] = $path;
            }

            $gallery->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil diperbarui',
                'data' => $gallery->fresh(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui galeri',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified gallery (Admin only)
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri tidak ditemukan',
            ], 404);
        }

        try {
            // Delete image file
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil dihapus',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus galeri',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get gallery categories
     */
    public function categories()
    {
        $categories = [
            ['value' => 'wedding', 'label' => 'Pernikahan'],
            ['value' => 'prewedding', 'label' => 'Prewedding'],
            ['value' => 'portrait', 'label' => 'Potret'],
            ['value' => 'product', 'label' => 'Produk'],
            ['value' => 'event', 'label' => 'Acara'],
            ['value' => 'other', 'label' => 'Lainnya'],
        ];

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
