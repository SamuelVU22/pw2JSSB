<?php

namespace App\Http\Controllers;

use App\Models\Photos; // For accessing the Photos model
use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Support\Facades\Http; // For making API requests
use Illuminate\Support\Facades\Auth; // For user authentication
use Illuminate\Support\Facades\DB; // For direct database queries
use Illuminate\Support\Facades\Log; // For logging errors (if needed)


class PhotosController extends Controller
{

    protected $maxPictures = 10;

    public function show()
    {

        $currentCount = Photos::count();
        if ($currentCount < $this->maxPictures) {
            $this->fetchAndSavePictures($this->maxPictures - $currentCount);
        }

        $pictures = Photos::all();

        return view('gallery.gallery', compact('pictures'));
    }

    public function delete($id)
    {
        // Delete the specified picture
        Photos::findOrFail($id)->delete();

        // Check if we need to fetch new pictures
        $currentCount = Photos::count();
        if ($currentCount < $this->maxPictures) {
            $this->fetchAndSavePictures($this->maxPictures - $currentCount);
        }

        return back()->with('success', 'Picture deleted successfully!');
    }

    private function fetchAndSavePictures($count)
    {
        try {
            // Fetch pictures from the NASA API
            $response = Http::get('https://api.nasa.gov/planetary/apod', [
                'api_key' => env('NASA_API_KEY'),
                'count' => $count,
            ]);
            if ($response->failed()) {
                throw new \Exception('Failed to fetch data from NASA API');
            }

            $pictures = $response->object();
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch pictures at this time.');
        }

        $pictures = $response->object();

        foreach ($pictures as $picture) {
            if (property_exists($picture, 'url') && property_exists($picture, 'title') && property_exists($picture, 'explanation')) {
                Photos::create([
                    'title' => $picture->title,
                    'date' => $picture->date,
                    'explanation' => $picture->explanation,
                    'url' => $picture->url,
                ]);
            }
        }
    }
/*
    public function associateUser($idUser, $picture)
    {

        $photo = DB::table('userpicture')->where('idUser', $idUser)->where('idPicture', $picture->idPicture)->first();
        if (is_null($photo)) {
            $picture->userSaved()->attach($idUser);
        }
    }
*/
/*
    public function disassociateUser($idUser, $picture)
    {

        $picture->userSaved()->detach($idUser);
    }
*/
    public function like(Request $req)
    {
        $photo = Photos::firstOrCreate(
            ['date' => $req->date],
            [
                'title' => $req->title,
                'explanation' => $req->explanation,
                'url' => $req->picture,
                'heart' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $user = Auth::user();
    
        // Manage the like/unlike action
        if ($req->heart === "true") {
            // Associate the picture with the user if liked
            $user->savedPictures()->syncWithoutDetaching([$photo->id]);
        } elseif ($req->heart === "false") {
            // Disassociate the picture from the user if unliked
            $user->savedPictures()->detach($photo->id);
        }
    
        return response()->json();


    }


    public function watch(Request $req, $date)
    {

        $response = HTTP::get('https://api.nasa.gov/planetary/apod?api_key=GPNcKV3zG4IC74Q0e2esJjoWSLDDXgIXwVRadTcq&date=' . $date);
        // dd($response->object());
        return view('gallery.picture', ['picture' => $response->object()]);
    }
}
