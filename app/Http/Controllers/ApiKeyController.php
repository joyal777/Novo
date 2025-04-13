<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserApiKey;
use Illuminate\Support\Facades\Http;
class ApiKeyController extends Controller
{


public function saveApiKey(Request $request)
{
    $validated = $request->validate([
        'api_key' => 'required|string',
        'api_name' => 'required|string',
    ]);

    $user = auth()->user(); // Get the currently authenticated user

    // Save the API key for the user
    UserApiKey::create([
        'user_id' => $user->id,
        'api_key' => $validated['api_key'],
        'api_name' => $validated['api_name'],
    ]);

    return back()->with('success', 'API Key saved successfully!');
}


public function updateApiKey(Request $request, $id)
{
    $validated = $request->validate([
        'api_name' => 'required|string',
        'api_key' => 'required|string',
    ]);

    // Make a test request to OMDb API to validate the key
    $response = Http::get('https://www.omdbapi.com/', [
        'apikey' => $validated['api_key'],
        't' => 'Inception' // Sample movie title to test the key
    ]);

    // Check if the key is valid (OMDb returns error in body if invalid)
    if (isset($response['Error']) && str_contains($response['Error'], 'Invalid API key')) {
        return redirect()->back()->withErrors([
            'api_key' => 'The provided API key is invalid. Please check and try again.',
        ]);
    }

    // Only proceed if the key is valid
    $apiKey = UserApiKey::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $apiKey->update($validated);

    return redirect()->back()->with('success', 'API Key updated successfully.');
}

public function deleteApiKey($id)
{
    $apiKey = UserApiKey::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $apiKey->delete();

    return redirect()->back()->with('success', 'API Key deleted successfully.');
}
public function showApiKeys()
{
    $user = auth()->user();
    $apiKeys = $user->apiKeys; // Fetch all API keys associated with the user

    return view('api-keys', compact('apiKeys'));
}


}
