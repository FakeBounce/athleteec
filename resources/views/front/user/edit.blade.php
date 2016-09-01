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
                    <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('user.update',['user' => $user]) }}">
                        {{ csrf_field() }}
                        <!--Notice .user-profile class-->
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-sm-2 col-md-2">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <img src="{{$user->picture}}" alt="Avatar"
                                                 class="img-thumbnail img-responsive">
                                            <div class="image-upload">
                                                <label for="picture">
                                                    <div class="btn btn-default">
                                                        <i class="fa fa-camera"></i>
                                                    </div>
                                                </label>
                                                <input type="file" name="picture" id="picture" class="filehide"/>

                                            </div>
                                            @if ($errors->has('picture'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('picture') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

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
                                        <div class="col-sm-2 col-md-2 hidden-xs">
                                         <button type="submit" class="btn btn-block btn-primary">Modifier</button>
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
                                    <!-- BEGIN TABS SELECTIONS-->
                                    <div class="row">
                                        <ul id="profileTab" class="nav nav-tabs">
                                            <li id="pots" class="ok">
                                                <a href="#">Dernières publications</a>
                                            </li>
                                            <li class="active ok" id="infos">
                                                <a href="#" data-toggle="tab">Information</a>
                                            </li>
                                            <li id="amis" class="ok">
                                                <a href="#">{{count($user->friends)}} @if(count($user->friends)>1) Amis @else
                                                        Ami @endif</a>
                                            </li>
                                            <li id="equipement" class="ok">
                                                <a href="#">Equipements</a>
                                            </li>
                                            <li id="photo" class="ok">
                                                <a href="#">Photos</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <!-- END TABS SELECTIONS-->
                                    <div class="row">
                                        <!-- BEGIN TABS SECTIONS-->
                                        <div id="profileTabContent" class="tab-content">
                                            <div class="tab-pane active infos">
                                                <br>
                                                <dl class="dl-horizontal">
                                                    <dt>Prénom</dt>
                                                    <dd><input type="text" class="form-control" name="firstname" value="{{ $user->firstname }}">
                                                    @if ($errors->has('firstname'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('firstname') }}</strong>
                                                        </span>
                                                    @endif

                                                    <dd class="divider"></dd>
                                                    <dt>Nom</dt>
                                                    <dd><input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}">
                                                    @if ($errors->has('lastname'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('lastname') }}</strong>
                                                    </span>
                                                    @endif

                                                    <dd class="divider"></dd>
                                                    <dt>Sexe</dt>
                                                    <dd><select id="select-beast" class="form-control" name="sexe">
                                                        <option value="Homme" @if($user->sexe == "Homme") selected="selected" @endif>
                                                            Homme
                                                        </option>
                                                        <option value="Femme" @if($user->sexe == "Femme") selected="selected" @endif>
                                                            Femme
                                                        </option>
                                                        <option value="Autre" @if($user->sexe == "Autre") selected="selected" @endif>
                                                            Autre
                                                        </option>
                                                    </select></dd>
                                                    <dd class="divider"></dd>
                                                    <dt>Status</dt>
                                                    <dd>
                                                        @if($user->star == true)
                                                            Personnalité <br>
                                                            <button type="button" class="btn btn-default" id="demandeStarRemove">Ne plus être reconnu en tant que personnalité</button>
                                                            <span id="responseDemandeStar"></span>
                                                        @else
                                                            Athlète <br>
                                                            <button type="button" class="btn btn-default" id="demandeStar">Etre reconnu en tant que personnalité</button>
                                                            <span id="responseDemandeStar"></span>
                                                        @endif
                                                    </dd>
                                                    <dd class="divider"></dd>
                                                    <dt>Poste actuel</dt>
                                                    <dd><input type="text" class="form-control" name="job" value="{{ $user->job }}">
                                                    <dd class="divider"></dd>
                                                    <dt>Entreprise</dt>
                                                    <dd><input type="text" class="form-control" name="firm" value="{{ $user->firm }}">
                                                    <dd class="divider"></dd>
                                                    <dt>Scolarité</dt>
                                                    <dd><input type="text" class="form-control" name="school" value="{{ $user->school }}">
                                                    <dd class="divider"></dd>
                                                    <dt>Sports pratiqué</dt>
                                                    <dd>(Cocher pour supprimer)</dd>
                                                    <ul class="list-unstyled"></dd>

                                                    @foreach($user->sports as $sport)
                                                        <dd>
                                                            <li>
                                                                <input type="checkbox" id="{{$sport->id}}" name="sportsuppr[]" value="{{$sport->id}}">{{ $sport->name }}
                                                            </li>
                                                        </dd>

                                                    @endforeach
                                                    </ul>
                                                    <dd class="divider"></dd>

                                                    <dt>Sports disponibles</dt>
                                                    <dd>(Cocher pour selectionner)</dd>
                                                    <ul class="list-unstyled">
                                                        @foreach($sports as $sport)
                                                        <dd>
                                                            <li>
                                                                <input type="checkbox" id="{{$sport->id}}" name="sport[]" value="{{$sport->id}}">{{ $sport->name }}
                                                            </li>
                                                        </dd>
                                                        @endforeach
                                                    </ul>
                                                    <dd class="divider"></dd>
                                                    <dt>Adresse postal</dt>
                                                    <dd><input type="text" class="form-control" name="address" value="{{ $user->address }}">
                                                    <dd class="divider"></dd>
                                                    <dt>Newsletter</dt>
                                                    @if($user->newsletter == true)
                                                        <dd><input type="checkbox" class="" name="newsletter" checked="checked"></dd>
                                                    @else
                                                        <dd><input type="checkbox" class="" name="newsletter"></dd>
                                                    @endif


                                                </dl>
                                            </div>
                                            <div class="tab-pane active equipement" style="display: none;">
                                                <div class="row equip">
                                                @foreach($user->products as $equipment)
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
                                                                    <div class="col-md-9">
                                                                        <a href="{{ route('product.show',['product' => $equipment]) }}" target="_blank">
                                                                            <dd>{{ $equipment->name }}</dd>
                                                                        </a>
                                                                        <dd>{{ $equipment->category->name }}</dd>
                                                                        <dd>{{ $equipment->description }}</dd>
                                                                        <dd>{{ $equipment->price }}€</dd>
                                                                    </div>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endforeach

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                    <a href="#" id="addproduct">
                                                        <span class="fa fa-plus"></span> Ajouter un équipement</a>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane active pots" style="display: none;">
                                                <ul class="timeline-2-cols">
                                                    <?php $i = 0; ?>
                                                    @foreach($user->publications as $publication)
                                                        <li id="<?php
                                                        if(is_null($publication->activity)){
                                                            echo "publication-".$publication->id;
                                                        }else{
                                                            echo "activite-".$publication->activity->id;
                                                        }
                                                        ?>" class="<?php if($i%2){echo 'timeline-inverted';} ?> publicationJS">
                                                            <div class="timeline-badge primary">
                                                                <a href="#"><i rel="tooltip" title="{{ $publication->date_start }}" class="glyphicon glyphicon-record <?php if($i%2){echo 'timeline-inverted';} $i++; ?>"></i>
                                                                </a>
                                                            </div>
                                                            <div class="timeline-panel">
                                                                <div class="timeline-heading row" style="margin: 0;">
                                                                    <div style="margin:0 10px 0 0;float:left;">
                                                                        <a href="{{ route("user.show", $publication->user->id ) }}">
                                                                            <img src="<?php echo $publication->user->picture ?>" alt="Image" class="img-responsive" style="width: 50px;height:50px; margin: 5px;display: inline-block;">
                                                                        </a>
                                                                    </div>
                                                                    <div style="margin: 10px;float:left;">
                                                                        <span>{{ $publication->user->firstname.' '.$publication->user->lastname}}</span><br>
                                                                        <small><i aria-hidden="true" class="fa fa-clock-o"></i> {{ $publication->timeAgo($publication->created_at) }}</small>
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    @if(is_null($publication->activity))
                                                                        <div class="post_activity_msg">
                                                                            {{$publication->message}}
                                                                        </div>
                                                                        <div class="post_picture_video">
                                                                            @if(!is_null($publication->video))
                                                                                <div class="video-container"><iframe src="https://www.youtube.com/embed/{{$publication->video->url}}" frameborder="0" allowfullscreen></iframe></div>
                                                                            @elseif(!is_null($publication->picture))
                                                                                <img src="{{ asset($publication->picture) }}" alt="" class="img-responsive">
                                                                            @endif
                                                                        </div>

                                                                    @else
                                                                        <div class="post_picture_video">
                                                                            @if(!is_null($publication->video))
                                                                                <div class="video-container"><iframe src="https://www.youtube.com/embed/{{$publication->video->url}}" frameborder="0" allowfullscreen></iframe></div>
                                                                            @elseif(!is_null($publication->picture))
                                                                                <img src="{{ asset($publication->picture) }}" alt="" class="img-responsive">
                                                                            @endif
                                                                        </div>
                                                                        <div class="post_activity">
                                                                            <div class="post_activity_img">
                                                                                <img src="{{ asset("../images/icons/".$publication->activity->sport->icon) }}" alt="{{ $publication->activity->sport->name }}" class="img-responsive">
                                                                            </div>
                                                                            <div class="post_activity_stats">
                                                                                <span data-text="{{$publication->activity->date_start}}"><i aria-hidden="true" class="fa fa-calendar"></i>{{$publication->activity->getDateStartString() }}</span>
                                                                                <span data-text="{{$publication->activity->getTimeSecondes() }}">Durée : {{$publication->activity->time }}</span>
                                                                            </div>

                                                                        </div>
                                                                        <div class="post_activity_msg">
                                                                            {{$publication->message}}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="tab-pane active photo"  style="display: none;">
                                                @foreach($user->pictures as $picture)
                                                    <article class="col-md-4 isotopeItem webdesign">
                                                        <div class="section-portfolio-item">
                                                            <div class="picture-cadre">
                                                                <div class="picture-box">
                                                                    <img src="{{ asset('images/users/'.$picture->link) }}" alt="image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </article>
                                                @endforeach
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <a href="#" id="addphoto">
                                                                <span class="fa fa-plus"></span> Ajouter une photo</a>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="tab-pane active amis" style="display: none;">
                                                <div class="row">
                                                    @forelse($user->friends as $friend)
                                                        <div class="col-md-2 onefriend">
                                                            <div class="team-member">
                                                                <a href="/user/{{ $friend->id }}">
                                                                    <figure class="member-photo">
                                                                        <img class="imgonefriend" src="{{ $friend->picture }}"
                                                                             alt="{{ $friend->firstname }} {{ $friend->lastname }}"
                                                                             width="100px" height="100px">
                                                                    </figure>
                                                                    <div class="team-detail">
                                                                        <h4>{{ $friend->firstname }} {{ $friend->lastname }}</h4>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="onefriend">Vous n'avez pas encore ajoutés d'amis.</p>
                                                    @endforelse
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
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
                                        <label for="category" class="col-md-2">Catégorie</label>
                                        <div class="col-md-10">
                                            <input type="text" autocomplete="off" id="prod_category" class="form-control" name="category" placeholder="...">
                                            <p id="prod_input"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="brand" class="col-md-2">Marque de l'équipement</label>
                                        <div class="col-md-10">
                                            <input type="text" autocomplete="off" id="brand_category" class="form-control" name="brand" placeholder="...">
                                            <p id="brand_input"></p>
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
                                                <img id="preview2" class="picture-size" src="http://placehold.it/200x200" alt="your image" style="max-width:200px;max-height:200px;"/>
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
    
    
    
    
        var i = 0;
        var caracs = new Array();
        @foreach($caracs as $carac)
            caracs[i] = {!! json_encode($carac) !!};
            i++;
        @endforeach
        
        $('body').on('keyup','#carac_category',function(e){
            var val_length = $(this).val().length;
            var val = $(this).val();
            var input = $(this);
            $("#carac_input").html('');
            
            if(val_length != 0)
            {
                $(caracs).each( function( i, carac ) {
                    if (carac.name.toLowerCase().substring(0, val_length) == val.toLowerCase()) {
                        $("#carac_input").append('<span class="carac_select">'+carac.name+'</span> ');
                    }
                });
            }
        });
    
    
        $('body').on('click','.carac_select',function(e){
            $('#carac_category').val($(this).html());
            $("#carac_input").html('');
        });
    
    
        var i = 0;
        var categories = new Array();
        @foreach($categories as $category)
            categories[i] = {!! json_encode($category) !!};
            i++;
        @endforeach
        
        $('body').on('keyup','#prod_category',function(e){
            var val_length = $(this).val().length;
            var val = $(this).val();
            var input = $(this);
            $("#prod_input").html('');
            
            if(val_length != 0)
            {
                $(categories).each( function( i, category ) {
                    if (category.name.toLowerCase().substring(0, val_length) == val.toLowerCase()) {
                        $("#prod_input").append('<span class="category_select btn btn-primary">'+category.name+'</span> ');
                    }
                });
            }
        });
    
        $('body').on('click','.category_select',function(e){
            $('.carac').remove();
            $('#prod_category').val($(this).html());
            var cat = 0;
            $(categories).each( function( i, category ) {
                cat = 1;
                if (category.name.toLowerCase() == $('#prod_category').val().toLowerCase()) {
                    $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
                    $(caracs).each( function( i, carac ) {
                        if(carac.category_id == category.id)
                        {
                                $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><label class="col-md-2 control-label">'+carac.name+'</label><div class="col-md-10"><input type="text" class="form-control" name="carac_'+carac.category_id+'" placeholder="..."></div></div>');
                        }
                    });
                }
            });
                if(cat == 0)
                {
                                       
                        $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
                }
            
            $("#prod_input").html('');
        });
    
    
        $('body').on('click','.carac_add',function(e){
            $(this).parent().remove();
            $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
            $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><label class="col-md-2 control-label">Valeur de la caractéristique</label><div class="col-md-10"><input type="text" class="form-control" name="new_carac_val[]" placeholder="42"></div></div>');
            $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><label class="col-md-2 control-label">Nom de la caractéristique</label><div class="col-md-10"><input type="text" class="form-control" name="new_carac_name[]" placeholder="Pointure, Taille, Longueur..."></div></div>');
        });
    
        $('body').on('blur','#prod_category',function(e){
            $('.carac').remove();
            var val_length = $(this).val().length;
            var val = $(this).val();
            var input = $(this);
            var cat = 0;
            if(val_length != 0)
            {
                $(categories).each( function( i, category ) {
                    cat = 1;
                    if (category.name.toLowerCase() == val.toLowerCase()) {                    
                        $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
                        $(caracs).each( function( i, carac ) {
                            if(carac.category_id == category.id)
                            {
                                    $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><label class="col-md-2 control-label">'+carac.name+'</label><div class="col-md-10"><input type="text" class="form-control" name="carac_'+carac.category_id+'" placeholder="..."></div></div>');
                            }
                        });
                        }
                });
                if(cat == 0)
                {
                                       
                        $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
                }
            }
        });
    
        var i = 0;
        var brands = new Array();
        @foreach($brands as $brand)
            brands[i] = {!! json_encode($brand) !!};
            i++;
        @endforeach
        
        $('body').on('keyup','#brand_category',function(e){
            var val_length = $(this).val().length;
            var val = $(this).val();
            var input = $(this);
            $("#brand_input").html('');
            
            if(val_length != 0)
            {
                $(brands).each( function( i, brand ) {
                    if (brand.name.toLowerCase().substring(0, val_length) == val.toLowerCase()) {
                        $("#brand_input").append('<span class="brand_select btn btn-primary">'+brand.name+'</span> ');
                    }
                });
            }
        });
    
    
        $('body').on('click','.brand_select',function(e){
            $('#brand_category').val($(this).html());
            $("#brand_input").html('');
        });
</script>
@endsection