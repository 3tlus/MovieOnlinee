<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{

    public function index()
    {
        $list_episode = Episode::with('movie')->orderBy('episodede', 'DESC')->get();
        // return \response()->json($list_episode);
        return \view('admincp.episode.index', \compact('list_episode'));
    }


    public function create()
    {
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        return \view('admincp.episode.form', \compact('list_movie'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $episode_check = Episode::where('episodede', $data['episodede'])->where('movie_id', $data['movie_id'])->count();
        if ($episode_check > 0) {
            toastr()->success('không Thành công', 'số tập phim trùng.');
        } else {
            $ep = new Episode();
            $ep->movie_id = $data['movie_id'];
            $ep->linkmovie = $data['link'];
            $ep->episodede = $data['episodede'];
            $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
            $ep->save();
            toastr()->success('Thành công', 'Thêm tập phim thành công.');
        }
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        $episode = Episode::find($id);
        return \view('admincp.episode.form', \compact('episode', 'list_movie'));
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $ep = Episode::find($id);
        $ep->movie_id = $data['movie_id'];
        $ep->linkmovie = $data['link'];
        $ep->episodede = $data['episodede'];
        $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->save();
        toastr()->success('Thành công', 'cập nhật tập phim thành công.');
        return redirect()->to('episode');
    }


    public function destroy($id)
    {
        $episode = Episode::find($id)->delete();
        toastr()->info('Thành công', 'xóa tập phim thành công.');
        return redirect()->to('episode');
    }

    public function select_movie()
    {
        $id = $_GET['id'];
        $movie = Movie::find($id);
        $output = '<option>--choose episode--</option>';
        if ($movie->belongingmo == 'phimbo') {
            for ($i = 1; $i <= $movie->sotap; $i++) {
                $output .= '<option value="' . $i . '">' . $i . '</option>';
            }
        } else {
            $output .= '<option value="HD">HD</option>
                 <option value="FULLHD">FULLHD</option>
                 <option value="FULLHD">Cam</option>
                 <option value="FULLHD">HDCam</option>';
        }
        echo $output;
    }
}