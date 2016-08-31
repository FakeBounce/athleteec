@extends('layouts.front')


@section('css')
    <link href="{{ asset('asset/css/layouts/timeline-facebook.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/timeline-2-cols.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/user-profile.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/user-cards.css') }}" rel="stylesheet">

    <link href="{{ asset('asset/css/plugins/selectizejs/selectize-default.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_free/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.halflings.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/friends.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                        <!--Notice .user-profile class-->
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-sm-2 col-md-2">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <img src="{{$user->picture}}" alt="Avatar"
                                                 class="img-thumbnail img-responsive">
                                        </div>
                                    </div>
                                    @if(Auth::user() != $user)
                                        <div>
                                            <a href="{{ url('conversation/'.$user->user_id) }}" class="btn btn-block btn-success"><i class="fa fa-envelope-alt"></i>Envoyer un
                                                message</a>
                                        </div>

                                        <br>
                                        <!-- BEGIN SOCIAL ICONS-->
                                        @if(Auth::user()->isfriend(Auth::user()->id,$user->id)==='demandsfrom')
                                            <div>
                                                <a href="{{ route('front.friends.cancel', ['friend' => $user->id]) }}" class="btn btn-block btn-success">Annuler la demande</a>
                                            </div>
                                        @elseif(Auth::user()->isfriend(Auth::user()->id,$user->id)==='demandsto')
                                            <div>
                                                <a href="{{ route('front.friends.accept', ['friend' => $user->id]) }}" class="btn btn-block btn-success">Accepter la demande</a>
                                            </div>
                                            <div>
                                                <a href="{{ route('front.friends.cancel', ['friend' =>$user->id]) }}" class="btn btn-block btn-success">Refuser la demande</a>
                                            </div>
                                        @elseif(Auth::user()->isfriend(Auth::user()->id,$user->id)==='estami')
                                            <div>
                                                <a href="{{ route('front.friends.destroy', ['friend' => $user->id]) }}" class="btn btn-block btn-success">Retirer de la liste d'amis</a>
                                            </div>
                                        @else
                                            <div>
                                                <a href="{{ route('front.friends.add', ['friend' => $user->id]) }}" class="btn btn-block btn-success">Ajouter un ami</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-sm-10 col-md-10">
                                    <div class="row">
                                        <!-- BEGIN USER STATUS-->
                                        <div id="user-status" class="text-left col-sm-10 col-md-10">
                                            <h3>{{ $user->firstname}} {{$user->lastname }}</h3>
                                        </div>
                                        <!-- END USER STATUS-->
                                        <div class="col-sm-2 col-md-2 hidden-xs  padding-bottom-correct">
                                            <a id="edit-profile-button" href="{{ route('user.show',['user'=>$user])}}" class="btn btn-block btn-primary">Annuler</a>
                                        </div>
                                    </div>
                                    <!-- BEGIN USER PANORAMIC-->
                                    <p id="panoramic" class="hidden-xs">
                                        <img src="{{ asset('asset/img/gallery/thecity_panoramic.jpg') }}" height="160" alt="Avatar" class="img-rounded img-responsive">
                                    </p>
                                    <!-- END USER PANORAMIC-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 col-md-2"></div>
                                <div class="col-sm-10 col-md-8-2">
                                    <ul id="profileTab" class="nav nav-tabs">
                                            <li id="pots" class="ok active">
                                                <a href="#">Annonces</a>
                                            </li>
                                        </ul>
                                    <div class="row"><br>
                                        <!-- BEGIN TABS SECTIONS-->                                
                                        <div id="profileTabContent" class="tab-content">
                                            <div class="tab-pane active equipement">
                                                <div class="row equip">
                                                    @if(!empty($user->products->first()))
                                                        <dt>Equipements en vente</dt>
                                                    @endif
                                                    <br>
                                                    @foreach($user->products as $equipment)
                                                        @if($equipment->sell == 1)
                                                            <div class="row">
                                                                <ul class="list-unstyled"></dd>
                                                                    <li>
                                                                        <div class="col-md-1">
                                                                            <div class="equipement-cadre">
                                                                                <div class="equipement-box">
                                                                                    <img src="{{asset('images/'.$equipment->picture)}}"
                                                                                         alt="Avatar" class="img-thumbnail img-responsive">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <a href="{{ $equipment->url }}">
                                                                                <dd>{{ $equipment->name }}</dd>
                                                                            </a>
                                                                            <dd>{{ $equipment->category->name }}</dd>
                                                                            <dd>{{ $equipment->description }}</dd>
                                                                            <dd>{{ $equipment->price }}€</dd>
                                                                        </div>
                                                                        @if($user->id == Auth::user()->id)
                                                                        <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('user.annonce_add',['user' => $user]) }}">
                                                                            {{ csrf_field() }}
                                                                            <div class="col-md-3 checkbox-correct">
                                                                                Enlever des annonces
                                                                                <input type="hidden" id="{{$equipment->id}}" name="annonce[]" value="{{$equipment->id}}">
                                                                            </div>
                                                                        </form>
                                                                        @else
                                                                        <div class="col-md-3">
                                                                            <a href="{{ route('product.show',['id' => $equipment->id]) }}">
                                                                                <dd>Voir les caractéristiques</dd>
                                                                            </a>
                                                                        </div>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>
                                
                                        @if($user->id == Auth::user()->id)
                                        <div id="profileTabContent" class="tab-content">
                                            <div class="tab-pane active equipement">
                                                <div class="row equip">
                                                    @if(!empty($user->products->first()))
                                                        <dt>Equipements disponibles</dt>
                                                    <br>
                                                    @endif
                                                    @foreach($user->products as $equipment)
                                                        @if($equipment->sell == 0)
                                                            <div class="row">
                                                                <ul class="list-unstyled"></dd>
                                                                    <li>
                                                                        <div class="col-md-1">
                                                                            <div class="equipement-cadre">
                                                                                <div class="equipement-box">
                                                                                    <img src="{{asset('images/'.$equipment->picture)}}"
                                                                                         alt="Avatar" class="img-thumbnail img-responsive">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <a href="{{ $equipment->url }}">
                                                                                <dd>{{ $equipment->name }}</dd>
                                                                            </a>
                                                                            <dd>{{ $equipment->category->name }}</dd>
                                                                            <dd>{{ $equipment->description }}</dd>
                                                                            <dd>{{ $equipment->price }}€</dd>
                                                                        </div>

                                                                        @if($user->id == Auth::user()->id)
                                                                        <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('user.annonce_add',['user' => $user]) }}">
                                                                            {{ csrf_field() }}
                                                                            <div class="col-md-3 checkbox-correct">
                                                                                Mettre une annonce pour cet équipement
                                                                                <input type="hidden" id="{{$equipment->id}}" name="annonce[]" value="{{$equipment->id}}">
                                                                            </div>
                                                                        </form>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                    <a href="#" id="addproduct">
                                                        <span class="fa fa-plus"></span> Ajouter un équipement</a>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="modal fade modal-photo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-photo">
                        <div class="modal-dialog modal-sm ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Ajouter une nouvelle photo</h4>
                                </div>
                                <div class="modal-body">

                                    <form id="submit-modal-photo" enctype="multipart/form-data">
                                        <div class="row" style="text-align: center">
                                            <div class="picture-size-box">

                                                <img id="preview" class="picture-size" src="http://placehold.it/200x200" alt="your image" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="image-upload" style="text-align: center;">
                                                <label for="file-input-modal">
                                                    <div class="btn btn-default"><i class="fa fa-camera fa-3x"></i></div>
                                                </label>
                                                <input id="file-input-modal" name="userpicture" type="file"/>
                                            </div>
                                            <button type="submit" class="btn btn-primary pull-right semi">Ajouter</button>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade modal-product" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-product">
                    <div class="modal-dialog modal-sm ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4>Ajouter un équipement</h4>
                            </div>
                            <div class="modal-body">
                                <form id="submit-modal-product" enctype="multipart/form-data">

                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="productname" class="col-md-2">Nom de l'équipement</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="productname" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="description" class="col-md-2 control-label">Description</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="description" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="price" class="col-md-2 control-label">Prix</label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" name="price" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="url" class="col-md-2 control-label">Lien vers l'équipement</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="url" placeholder="...">
                                        </div>
                                    </div>
                                    <div class="form-actions panel-foo">
                                        <div class="btn-group">
                                            <div class="image-upload">
                                                <label for="file-input-modal">
                                                    <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                                </label>
                                                <input id="file-input-modal" name="productpicture" type="file" accept="image/*"/>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary pull-right" >Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('body').on('click','.checkbox-correct',function(){
            $(this).parent().submit();
        });
    </script>
@endsection