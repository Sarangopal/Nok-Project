<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        // Get all published gallery items, ordered by display_order
        $galleries = Gallery::published()
            ->ordered()
            ->get();
        
        // Get unique categories for filter buttons
        $categories = Gallery::published()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
        
        // Get category labels
        $categoryLabels = [
            'aaravam' => 'Aaravam',
            'nightingales2024' => 'Nightingales 2024',
            'nightingales2023' => 'Nightingales 2023',
            'sports' => 'Sports Events',
            'cultural' => 'Cultural Events',
            'others' => 'Others',
        ];
        
        return view('gallery', compact('galleries', 'categories', 'categoryLabels'));
    }
}




