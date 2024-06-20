<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Helpers\ResponseFormatter;

class BlogController extends Controller
{
    private function calculateReadTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200); // Assuming 200 words per minute reading speed

        return $minutes;
    }

    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);

        // if have image
        $blogs->getCollection()->transform(function ($blog) {
            if ($blog->image) {
                $blog->image = asset('storage/' . $blog->image);
            }
            return $blog;
        });

        // Add read time calculation for each blog
        $blogs->getCollection()->transform(function ($blog) {
            $blog->readTime = $this->calculateReadTime($blog->content);
            return $blog;
        });

        return ResponseFormatter::success($blogs, 'Data list blog berhasil diambil');
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);

        // if have image
        if ($blog->image) {
            $blog->image = asset('storage/' . $blog->image);
        }

        $blog->increment('views'); // Tambah jumlah views
        $readTime = $this->calculateReadTime($blog->content); // Hitung estimasi waktu baca

        return response()->json([
            'blog' => $blog,
            'readTime' => $readTime,
        ]);
    }
}
