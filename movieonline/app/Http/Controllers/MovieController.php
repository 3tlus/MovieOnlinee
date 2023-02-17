<?php

namespace App\Http\Controllers;

use App\Models\Movie_Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Info;
use Carbon\Carbon;
use File;

class MovieController extends Controller
{

    public function index()
    {
        $list = Movie::with('category', 'movie_genre', 'country', 'genre')->orderBy('id', 'DESC')->get();

        $path = \public_path() . "/json_file/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        File::put($path . 'movies.json', \json_encode($list));

        return view('admincp.movie.index', compact('list'));
    }
    public function update_year(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->year = $data['year'];
        $movie->save();
    }
    public function update_season(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->season = $data['season'];
        $movie->save();
    }

    public function update_topview(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);
        $movie->topview = $data['topview'];
        $movie->save();
    }

    public function filter_topview(Request $request)
    {
        $data = $request->all();
        $movie = Movie::Where('topview', $data['value'])->orderBy('ngaycapnhat', 'DESC')->take(20)->get();
        $output = '';
        foreach ($movie as $key => $mov) {
            if ($movie->implode('resolution') == 0) {
                $text = 'HD';
            } elseif ($movie->implode('resolution') == 1) {
                $text = 'SD';
            } elseif ($movie->implode('resolution') == 2) {
                $text = 'HDcam';
            } elseif ($movie->implode('resolution') == 3) {
                $text = 'Cam';
            } elseif ($movie->implode('resolution') == 4) {
                $text = 'FullHD';
            } else {
                $text = 'Trailer';
            }

            $output .= ' 
            <div id="halim-ajax-popular-post" class="popular-post">
                <div class="item">
                <a href="' . url('phim/' . $mov->slug) . '" title="' . $mov->title . '">
                <div class="item-link">
                <img src="' . url('uploads/movie/' . $mov->image) . '"
                class="lazy post-thumb" alt="' . $mov->title . '"
                title="' . $mov->title . '" />
                <span class="is_trailer">' . $text . '</span>
                </div>
                <p class="title">' . $mov->title . '</p>
                </a>
                <div class="viewsCount" style="color: #9d9d9d;">3.2K lượt xem</div>
                <div style="float: left;">
                <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                <span style="width: 0%"></span>
                </span>
                </div>
            </div>
         </div>';
        }
        echo $output;
    }

    public function filter_default(Request $request)
    {
        $data = $request->all();
        $movie = Movie::Where('topview', 0)->orderBy('ngaycapnhat', 'DESC')->take(20)->get();
        $output = '';
        foreach ($movie as $key => $mov) {
            if ($mov->resolution == 0) {
                $text = 'HD';
            } elseif ($mov->resolution == 1) {
                $text = 'SD';
            } elseif ($mov->resolution == 2) {
                $text = 'HDcam';
            } elseif ($mov->resolution == 3) {
                $text = 'Cam';
            } elseif ($mov->resolution == 4) {
                $text = 'FullHD';
            } else {
                $text = 'Trailer';
            }

            $output .= ' 
                <div class="item">
                    <a href="' . url('phim/' . $mov->slug) . '" title="' . $mov->title . '">
                    <div class="item-link">
                    <img src="' . url('uploads/movie/' . $mov->image) . '"
                    class="lazy post-thumb" alt="' . $mov->title . '"
                    title="' . $mov->title . '" />
                    <span class="is_trailer">' . $text . '</span>
                    </div>
                    <p class="title">' . $mov->title . '</p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;">3.2K lượt xem</div>
                    <div style="float: left;">
                    <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                    <span style="width: 0%"></span>
                    </span>
                    </div>
                </div>';
        }
        echo $output;
    }

    public function create()
    {
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $list_genre = Genre::all();
        $country = Country::pluck('title', 'id');
        return view('admincp.movie.form', compact('category', 'genre', 'country', 'list_genre'));
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $movie = new Movie();
        $movie->title = $data['title'];
        $movie->trailer = $data['trailer'];
        $movie->tags = $data['tags'];
        $movie->thoiluong = $data['thoiluong'];
        $movie->sotap = $data['sotap'];
        $movie->phude = $data['phude'];
        $movie->resolution = $data['resolution'];
        $movie->slug = $data['slug'];
        $movie->name_eng = $data['name_eng'];
        $movie->phim_hot = $data['phim_hot'];
        $movie->description = $data['description'];
        $movie->status = $data['status'];
        $movie->category_id = $data['category_id'];
        $movie->belongingmo = $data['belongingmo'];
        $movie->country_id = $data['country_id'];
        $movie->count_views = rand(100, 99999);
        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');

        foreach ($data['genre'] as $key => $gen) {
            $movie->genre_id = $gen[0];
        }


        $get_image = $request->file('image');

        if ($get_image) {

            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 9999) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/movie/', $new_image);
            $movie->image = $new_image;
        }
        $movie->save();

        // thêm nhieu2 the loai
        $movie->movie_genre()->attach($data['genre']);
        toastr()->success('Thành công', 'Thêm movie thành công.');
        return redirect()->route('movie.index');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $list_genre = Genre::all();
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $country = Country::pluck('title', 'id');
        $movie = Movie::find($id);
        $movie_genre = $movie->movie_genre;
        return view('admincp.movie.form', compact('category', 'genre', 'country', 'movie', 'list_genre', 'movie_genre'));
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $movie = Movie::find($id);
        $movie->title = $data['title'];
        $movie->trailer = $data['trailer'];
        $movie->sotap = $data['sotap'];
        $movie->tags = $data['tags'];
        $movie->thoiluong = $data['thoiluong'];
        $movie->phude = $data['phude'];
        $movie->resolution = $data['resolution'];
        $movie->slug = $data['slug'];
        $movie->name_eng = $data['name_eng'];
        $movie->phim_hot = $data['phim_hot'];
        $movie->description = $data['description'];
        $movie->status = $data['status'];
        $movie->category_id = $data['category_id'];
        // $movie->count_views = rand(100, 99999);
        $movie->belongingmo = $data['belongingmo'];
        $movie->country_id = $data['country_id'];
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');

        foreach ($data['genre'] as $key => $gen) {
            $movie->genre_id = $gen[0];
        }

        $get_image = $request->file('image');

        if ($get_image) {
            if (\file_exists('uploads/movie/' . $movie->image)) {
                unlink('uploads/movie/' . $movie->image);
            } else {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = current(explode('.', $get_name_image));
                $new_image = $name_image . rand(0, 9999) . '.' . $get_image->getClientOriginalExtension();
                $get_image->move('uploads/movie/', $new_image);
                $movie->image = $new_image;
            }
        }
        $movie->save();
        $movie->movie_genre()->sync($data['genre']);
        toastr()->success('Thành công', 'cập nhật movie thành công.');
        return redirect()->route('movie.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        //xoa anh
        if ('uploads/movie/' . $movie->image) {
            unlink('uploads/movie/' . $movie->image);
        }
        //xoa the loai
        Movie_Genre::whereIn('movie_id', [$movie->id])->delete();
        $movie->delete();
        toastr()->info('Thành công', 'xóa movie thành công.');
        return redirect()->back();
    }
}