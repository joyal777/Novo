<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserApiKey;
class MovieController extends Controller
{


    public function search(Request $request)
    {
        $query = $request->query('q');
        $user = auth()->user();

        if (!$query) {
            return response()->json(['error' => 'No query provided'], 400);
        }

        // Get the first saved API key for the user
        $apiKeyModel = $user->apiKeys()->first(); // Or filter by 'api_name' if needed

        if (!$apiKeyModel) {
            return response()->json(['error' => 'No API key found for this user'], 403);
        }

        $response = Http::get("http://www.omdbapi.com/", [
            'apikey' => $apiKeyModel->api_key,
            's' => $query
        ]);

        return response()->json($response->json());
    }



    public function saveFavorite(Request $request)
    {
        $validated = $request->validate([
            'imdbID' => 'required|string',
            'title' => 'required|string',
            'poster' => 'required|string',
            'year' => 'required|string',
        ]);

        $user = auth()->user();

        $user->favorites()->updateOrCreate(
            [
                'imdbID' => $validated['imdbID'],
                'user_id' => $user->id
            ],
            [
                'title' => $validated['title'],
                'poster' => $validated['poster'],
                'year' => $validated['year']
            ]
        );

        return redirect()->route('movies.favorites')->with('success', "🎉 '{$validated['title']}' added to favorites!");

    }

    public function showFavorites()
    {
        $user = auth()->user();
        $favorites = $user->favorites; // Make sure relation is defined

        return view('movies', compact('favorites'));
    }


}

