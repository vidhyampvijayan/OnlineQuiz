<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class QuizCategoryController extends Controller
{
    public function showCategories()
    {
        $response = Http::get('https://the-trivia-api.com/api/categories');
        if ($response->successful()) {
            $categories = collect($response->json());  
            return view('categories', compact('categories'));
        } else {
            return response()->json(['error' => 'Failed to fetch categories from the API'], $response->status());
        }
    }
    
   
 
  

}
