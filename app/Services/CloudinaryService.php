<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload gambar ke Cloudinary
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $folder - folder di Cloudinary (optional)
     * @param array $options - opsi tambahan (optional)
     * @return array|null
     */
public function upload($file, $folder = null, $options = [])
{
    try {
        $uploadOptions = array_merge([
            'folder' => $folder ?? 'uploads',
            'resource_type' => 'auto',
        ], $options);

        Log::info('Uploading to Cloudinary', [
            'file_path' => $file->getRealPath(),
            'options' => $uploadOptions
        ]);

        $result = Cloudinary::uploadApi()->upload($file->getRealPath(), $uploadOptions);

        Log::info('Upload Result', [
            'result' => $result
        ]);

    return [
        'url' => $result['secure_url'],
        'public_id' => $result['public_id'],
        'width' => $result['width'],
        'height' => $result['height'],
        'format' => $result['format'],
        'size' => $result['bytes'],
    ];

    } catch (\Exception $e) {
        Log::error('Cloudinary Upload Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return null;
    }
}

    /**
     * Upload dengan transformasi otomatis (resize, compress)
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $folder
     * @param int $width - lebar maksimal
     * @param int $quality - kualitas (1-100)
     * @return array|null
     */
    public function uploadOptimized($file, $folder = null, $width = 1200, $quality = 80)
    {
        return $this->upload($file, $folder, [
            'transformation' => [
                'width' => $width,
                'crop' => 'limit',
                'quality' => $quality,
                'fetch_format' => 'auto',
            ]
        ]);
    }

    /**
     * Upload thumbnail (resize & crop)
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $folder
     * @param int $width
     * @param int $height
     * @return array|null
     */
    public function uploadThumbnail($file, $folder = null, $width = 300, $height = 300)
    {
        return $this->upload($file, $folder, [
            'transformation' => [
                'width' => $width,
                'height' => $height,
                'crop' => 'fill',
                'gravity' => 'auto',
                'quality' => 'auto',
            ]
        ]);
        
    }

    /**
     * Delete gambar dari Cloudinary
     * 
     * @param string $publicId
     * @return bool
     */
    public function delete($publicId)
    {
        try {
            $result = Cloudinary::uploadApi()->destroy($publicId);
            return $result['result'] === 'ok';

        } catch (\Exception $e) {
            Log::error('Cloudinary Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple gambar
     * 
     * @param array $publicIds
     * @return bool
     */
    public function deleteMultiple(array $publicIds)
    {
        try {
            foreach ($publicIds as $publicId) {
                Cloudinary::destroy($publicId);
            }
            return true;

        } catch (\Exception $e) {
            Log::error('Cloudinary Delete Multiple Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate URL dengan transformasi
     * 
     * @param string $publicId
     * @param array $transformations
     * @return string
     */
    public function getUrl($publicId, $transformations = [])
    {
        return Cloudinary::getUrl($publicId, $transformations);
    }

    /**
     * Generate responsive image URLs (berbagai ukuran)
     * 
     * @param string $publicId
     * @return array
     */
    public function getResponsiveUrls($publicId)
    {
        return [
            'thumbnail' => $this->getUrl($publicId, ['width' => 150, 'height' => 150, 'crop' => 'fill']),
            'small' => $this->getUrl($publicId, ['width' => 400, 'crop' => 'limit']),
            'medium' => $this->getUrl($publicId, ['width' => 800, 'crop' => 'limit']),
            'large' => $this->getUrl($publicId, ['width' => 1200, 'crop' => 'limit']),
            'original' => $this->getUrl($publicId),
        ];
    }
}