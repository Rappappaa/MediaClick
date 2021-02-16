@extends('layouts.master')
@section('content')
    <h1 class="text-center">MediaClick - ToDo List</h1>
    <div class="card card-default">
        <div class="card-header">Provider</div>
        <div class="card-body">
            <form action="{{ route('redirectprovider') }}" METHOD="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Verinin çekileceği link</label>
                    <input type="text" class="form-control" id="link" name="link">
                </div>
                <div class="form-group">
                    <label for="text">Provider Seçimi</label>
                    <select class="form-control" id="providerselection" name="providerselection">
                        <option value="Provider1">Provider 1</option>
                        <option value="Provider2">Provider 2</option>
                    </select>
                </div>
                <br/>
                <button type="submit" class="btn btn-sm float-right btn-primary">Veriyi Al</button>
            </form>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}"  class="btn btn-sm float-right btn-success">Geri Dön</a>
        </div>
    </div>
@endsection
