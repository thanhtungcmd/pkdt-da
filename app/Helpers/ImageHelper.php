<?php
// app/Helpers/ImageHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Upload ảnh và trả về đường dẫn
     */
    public static function upload($file, $folder = 'uploads')
    {
        if (!$file) return null;
        
        // Tạo tên file unique
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Lưu vào public/images/{folder}
        $file->move(public_path('images/' . $folder), $fileName);
        
        // Trả về đường dẫn
        return '/images/' . $folder . '/' . $fileName;
    }
    
    /**
     * Xóa ảnh cũ
     */
    public static function delete($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}