<?php

namespace App\Http\Controllers;

use App\Models\Photos; // For accessing the Photos model
use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Support\Facades\Http; // For making API requests
use Illuminate\Support\Facades\Auth; // For user authentication
use Illuminate\Support\Facades\DB; // For user authentication

class PhotosController extends Controller
{

    protected $maxPictures = 20;

    public function show()
    {

        $currentCount = Photos::count();
        if ($currentCount < $this->maxPictures) {
            $this->fetchAndSavePictures($this->maxPictures - $currentCount);
        }

        $pictures = Photos::all()->map(function ($photo) {
            return [
                'idPhoto' => $photo->idPhoto,
                'title' => $photo->title,
                'explanation' => $photo->description,
                'url' => $photo->urlPhoto,
                'date' => $photo->date,
                'numLikes' => $photo->numLikes,
                'isLike' => $photo->isLike,
            ];
        });

        //dd($pictures);

        return view('gallery.gallery', ['pictures' => $pictures]);
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
            //dd($picture);
            if (property_exists($picture, 'url') && property_exists($picture, 'title') && property_exists($picture, 'explanation')) {
                //dd($picture->date);
                Photos::create([
                    'title' => $picture->title,
                    'date' => $picture->date,
                    'numLikes' => 0,
                    'isLike' => 0,
                    'description' => $picture->explanation,
                    'urlPhoto' => $picture->url,
                ]);
            }
        }
    }

    public function associateUser($idUser, $picture)
    {

        $photo = DB::table('user_photos')->where('idUser', $idUser)->where('idPhoto', $picture->idPicture)->first();
        if (is_null($photo)) {
            $picture->userSaved()->attach($idUser);
        }
    }


    public function disassociateUser($idUser, $picture)
    {

        $picture->userSaved()->detach($idUser);
    }

    public function like(Request $req)
    {
        // Find the photo by date
        $photo = Photos::where('date', $req->date)->firstOrFail(); // Throws 404 if not found

        //dd($req->input('heart'));
        //dd($photo->title);
    
        // Update the isLike attribute to 1 if liked, or reset it to 0 if unliked
        if ($req->input('heart') === "true") {
            $photo->isLike = 1;
            $photo->numLikes++;
            $photo->save(); // Persist the change in the database
    
            //dd("true");
            // Associate the photo with the user (like it)
            $this->associateUser($req->userId, $photo);
        } elseif ($req->input('heart')  === "false") {
            $photo->isLike = 0;
            $photo->numLikes--;
            $photo->save(); // Persist the change in the database
    
            //dd("false");
            // Disassociate the photo from the user (unlike it)
            $this->disassociateUser($req->userId, $photo);
        }
        //dd($photo);
    
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Like status updated']);
    }
    


    public function watch(Request $req, $date)
    {

        $response = HTTP::get('https://api.nasa.gov/planetary/apod?api_key=GPNcKV3zG4IC74Q0e2esJjoWSLDDXgIXwVRadTcq&date=' . $date);
        // dd($response->object());
        return view('gallery.picture', ['picture' => $response->object()]);
    }
}
