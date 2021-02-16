@extends('layouts.master')
@section('content')
    <h1 class="text-center">MediaClick - ToDo List</h1>
    <div class="card card-default">
        <div class="card-header">{{ $developer->name }} - Haftalık To-Do Planlaması</div>
        <div class="card-body">
            <ul class="list-group">
                @if(count($tasks) > 0)
                @foreach($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="col-md-3">
                            {{ $task->task }}
                        </div>
                        <div class="col-md-3">
                            {{ $task->week }}. Hafta
                        </div>
                        <div class="col-md-3">
                            {{ $task->duration }} Saat
                        </div>
                    </li>
                @endforeach
                @else
                    {{ $developer->name }} için task atanmamıştır.
                @endif
            </ul>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}"  class="btn btn-sm float-right btn-success">Geri Dön</a>
        </div>
    </div>
@endsection
