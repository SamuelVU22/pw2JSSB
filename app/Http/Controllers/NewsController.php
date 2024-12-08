<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected $maxNews = 20; // Maximum number of news articles to store

    public function show()
    {
        $currentUserId = Auth::id(); // Get the current user's ID
        // Ensure the database has at least $maxNews articles
        $currentCount = News::count();
        if ($currentCount < $this->maxNews) {
            $this->fetchAndSaveNews($this->maxNews - $currentCount);
        }

        // Fetch news articles and format them for display
        $news = News::all()->map(function ($article) use ($currentUserId) {
            //dd($article);
            // Check if the current user has liked this photo
            $isLikedByUser = DB::table('user_news')
                ->where('idUser', $currentUserId)
                ->where('idNews', $article->idNews)
                ->exists();
            return [
                'idNews' => $article->idNews,
                'title' => $article->title,
                'content' => $article->description,
                'urlPhoto' => $article->urlPhoto,
                'date' => $article->date,
                'numLikes' => $article->numLikes,
                'isLike' => $article->isLike,
                'urlNews' => $article->urlNews,
            ];
        });

        return view('news.news', ['news' => $news]);
    }



    private function fetchAndSaveNews($count)
    {
        try {
            // Fetch news articles from a news API
            $response = Http::get('https://api.spaceflightnewsapi.net/v4/articles?limit=30');

            //dd($response->object()->results);

            if ($response->failed()) {
                throw new \Exception('Failed to fetch data from News API');
            }

            $articles = $response->object()->results; // Adjust based on API response structure
            //dd($articles);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to fetch news at this time.');
        }

        //dd($articles);

        foreach ($articles as $article) {
            // Example usage
            $date = $article->published_at ?? now()->toDateString();
            $formattedDate = Carbon::parse($date)->toDateString(); // Converts to YYYY-MM-DD
            //dump($article);
            if (isset($article->title, $article->image_url, $article->summary)) {
                //dd($plainTextSummary);
                //dump($article->summary);
                News::create([
                    'title' => $article->title,
                    'description' => $article->summary, // Use formatted summary here
                    'date' => $formattedDate,
                    'numLikes' => 0,
                    'isLike' => false,
                    'urlPhoto' => $article->image_url,
                    'urlNews' => $article->url,
                ]);
            }
        }
    }


    /*
    public function showSavedNews()
    {

        $news = Auth::user()->savedNews()->get()->toArray();


        return view('news.news', ['news' => $news]);
    }
*/
    public function associateUser($idUser, $news)
    {

        $notice = DB::table('user_news')->where('idUser', $idUser)->where('idNews', $news->idNews)->first();
        if (is_null($notice)) {
            $news->userSaved()->attach($idUser);
        }
    }

    public function disassociateUser($idUser, $news)
    {

        $news->userSaved()->detach($idUser);
    }



    public function like(Request $req)
    {
        // Find the photo by date
        $news = News::where('idNews', $req->idNews)->firstOrFail(); // Throws 404 if not found

        // Update the isLike attribute to 1 if liked, or reset it to 0 if unliked
        if ($req->input('heart') === "true") {
            $news->isLike = 1;
            $news->numLikes++;
            $news->save(); // Persist the change in the database

            //dd("true");
            // Associate the photo with the user (like it)
            $this->associateUser($req->userId, $news);
        } elseif ($req->input('heart')  === "false") {
            $news->isLike = 0;
            $news->numLikes--;
            $news->save(); // Persist the change in the database

            //dd("false");
            // Disassociate the photo from the user (unlike it)
            $this->disassociateUser($req->userId, $news);
        }
        //dd($photo);

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Like status updated']);
    }
}
