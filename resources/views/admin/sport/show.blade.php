@extends('layouts.admin')


@section('css')
    <link href="{{ asset('asset/css/plugins/datatables/datatables.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container" style="margin-top:40px;">
        <div class="row">
            <div class="col-md-6 padding" >
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Informations</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name">Nom</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $sport->name }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="existicon" class="col-sm-2 control-label">Icon :</label>
                            <div class="col-sm-10">
                                <img src="{{ asset("../images/icons/".$sport->icon) }}" alt="{{ $sport->name }}" class="img-responsive" width="45px" height="45px">
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
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                        <a href="{{ route('admin.sport.index') }}" class="btn btn-default">Retour</a>
                        <a href="{{ route('admin.sport.edit', ['sport' => $sport->id]) }}" class="btn btn-default">Modifier</a>
                        <a href="{{ route('admin.sport.delete', ['sport' => $sport->id]) }}" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('asset/js/jquery/jquery.js') }}"></script>
@endsection