@extends('layouts.admin')


@section('css')
    <link href="{{ asset('asset/css/plugins/datatables/datatables.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container" style="margin-top:40px;">
        <form class="form-horizontal" method="post" action="{{ route("admin.sport.update", ['sport' => $sport->id]) }}" enctype="multipart/form-data">
            {{csrf_field()}}
        <div class="row">
            <div class="col-md-6 padding" >
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Informations</h4>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Nom</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $sport['name'] }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="existicon" class="col-sm-2 control-label">Icon :</label>
                                <div class="col-sm-10">
                                    <input type="file" name="picture" id="picture">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 padding">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Actions</h4>
                    </div>
                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <a href="{{ route('admin.sport.show', ['sport' => $sport['id']]) }}" class="btn btn-default">Retour</a>
                        <button type="submit" class="btn btn-default">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('asset/js/jquery/jquery.js') }}"></script>
@endsection