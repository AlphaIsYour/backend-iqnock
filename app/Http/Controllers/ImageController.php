<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Upload gambar biasa
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // max 10MB
        ]);

        $result = $this->cloudinary->upload(
            $request->file('image'),
            'products' // folder name
        );

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'data' => $result
        ]);
    }

    /**
     * Upload dengan optimasi otomatis
     */
    public function uploadOptimized(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $result = $this->cloudinary->uploadOptimized(
            $request->file('image'),
            'products',
            1200, // max width
            85    // quality
        );

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Upload thumbnail
     */
    public function uploadThumbnail(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $result = $this->cloudinary->uploadThumbnail(
            $request->file('image'),
            'thumbnails',
            300, // width
            300  // height
        );

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Delete gambar
     */
    public function delete(Request $request)
    {
        $request->validate([
            'public_id' => 'required|string'
        ]);

        $deleted = $this->cloudinary->delete($request->public_id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    /**
     * Get responsive URLs
     */
    public function getResponsiveUrls(Request $request)
    {
        $request->validate([
            'public_id' => 'required|string'
        ]);

        $urls = $this->cloudinary->getResponsiveUrls($request->public_id);

        return response()->json([
            'success' => true,
            'data' => $urls
        ]);
    }

    /**
     * Update gambar (hapus lama, upload baru)
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'old_public_id' => 'required|string'
        ]);

        // Hapus gambar lama
        $this->cloudinary->delete($request->old_public_id);

        // Upload baru
        $result = $this->cloudinary->uploadOptimized(
            $request->file('image'),
            'products'
        );

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully',
            'data' => $result
        ]);
    }
}