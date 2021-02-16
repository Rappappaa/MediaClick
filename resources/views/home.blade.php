@extends('layouts.master')
@section('content')
    <h1 class="text-center">MediaClick - ToDo List</h1>
    <div class="card card-default">
        <div class="card-header">
                <a href="{{ route('getlink') }}" class="btn btn-sm float-right btn-success">To-Do Listesini Oluştur</a>
                <a href="{{ route('temizle') }}" class="btn btn-sm float-right btn-danger">To-Do Listesini Temizle</a>
        </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($developers as $developer)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="col-md-3">
                                {{ $developer->name }} ({{ $developer->level }}.Seviye)
                            </div>
                            <div class="col-md-3">
                                <?php
                                $numberoftasks = DB::table('task')->where('ref_dev',$developer->id)->Count();
                                echo $numberoftasks . " Görevlendirme";
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $totalhours = DB::table('task')->where('ref_dev',$developer->id)->Sum('duration');
                                echo $totalhours . " Saat";
                                ?>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('goruntule') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" id="id" value="{{ $developer->id }}">
                                    <button type="submit" class="btn btn-sm float-right btn-primary">Görüntüle</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        <div class="card-footer">
            @if($maxweek == 0)
                Henüz görev atanmamıştır.
            @else
                {{ $maxweek }} hafta sonra görevler tamamlanacaktır.
            @endif
        </div>
    </div>
@endsection
