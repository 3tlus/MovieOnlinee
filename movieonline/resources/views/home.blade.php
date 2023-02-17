@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"
                        style="color: red; position: absolute; ; font-size: 50px;margin-left: 350px;font-family: Brush Script MT;">
                        Trang Quản Lý</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <img src="https://emoi.vn/wp-content/uploads/2020/12/xem-phim-tinh-cam-lang-man.jpg" alt=""
                            height="500%" width="155%">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
