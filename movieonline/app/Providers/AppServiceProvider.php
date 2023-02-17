<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Episode;
use App\models\Movie_Genre;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('20')->get();
        // $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('10')->get();
        // $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        // $genre = Genre::orderBy('id', 'DESC')->get();
        // $country = Country::orderBy('id', 'DESC')->get();
        //total view
        $category_total = Category::all()->count();
        $genre_total = Genre::all()->count();
        $country_total = Country::all()->count();
        $movie_total = Movie::all()->count();
        $episode_total = Episode::all()->count();

        View::share([
            'episode_current_list_count' => 'episode_current_list_count',
            'movie_total' => $movie_total,
            'episode_total' => $episode_total,
            'category_total' => $category_total,
            'genre_total' => $genre_total,
            'country_total' => $country_total,

            // 'phimhot_sidebar' => $phimhot_sidebar,
            // 'phimhot_trailer' => $phimhot_trailer,
            // 'category' => $category,
            // 'genre' => $genre,
            // 'country' => $country
        ]);
    }
}