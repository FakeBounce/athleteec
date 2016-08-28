@extends('layouts.admin')


@section('css')
    <link href="{{ asset('asset/css/plugins/datatables/datatables.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <div class="row padding margin-top-admin">
        <div class="text-right" style="margin-bottom: 10px;">
            <a href="{{ route('admin.sport.create') }}" class="btn btn-default">Ajouter un sport</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Liste des sports</h3>
            </div>
            <div class="panel-body">
                <table id="example" class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Nb Utilisateurs</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sports as $sport)
                        <tr>
                            <td>{{ $sport->id }}</td>
                            <td>{{ $sport->name }}</td>
                            <td>
                                @if(!is_null($sport->icon))
                                    <img src="{{ asset("../images/icons/".$sport->icon) }}" alt="{{ $sport->name }}" class="img-responsive" width="45px" height="45px">
                                @endif
                            </td>
                            <td>
                                {{ $sport->userSport->count() }}
                            </td>
                            <td>
                                <a href="{{ route('admin.sport.show', ['sport' => $sport->id]) }}">Consulter</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('asset/js/jquery/jquery.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script>
        $(function(){
            $('#example').DataTable();
        });
    </script>
@endsection