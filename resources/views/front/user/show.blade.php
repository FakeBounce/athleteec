@extends('layouts.front')


@section('css')
    <link href="{{ asset('asset/css/layouts/timeline-facebook.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/timeline-2-cols.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/user-profile.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/user-cards.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/js/plugins/isotope/isotope.css') }}" rel="stylesheet">

    <link href="{{ asset('asset/css/plugins/selectizejs/selectize-default.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_free/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.halflings.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/friends.css') }}" rel="stylesheet">
    <style>
        .timeline-2-cols::before {
            content: normal;
        }
        .timeline-2-cols > li > .timeline-panel::before {
            content: normal;
        }

        .timeline-2-cols > li > .timeline-panel::after {
            content: normal;
        }
        .timeline-2-cols > li > .timeline-panel {
            width: 100%;
        }
        .timeline-2-cols > li {
            width: 100%;
            margin-top: 0 !important;
            margin-bottom: 40px;
        }

    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!--Notice .user-profile class-->
            <div class="user-profile">
                <div class="row">
                    <div class="col-md-2 col-md-2">
                        <div class="row">
                            <div class="col-md-12 text-center" style="position: relative">
                                @if($user->star == true)
                                    <img src="{{ asset('images/medal-1-s.png') }}" alt="medal" class="img-star">
                                @endif
                                <img src="{{$user->picture}}" alt="Avatar" class="img-thumbnail img-responsive">
                            </div>
                        </div>

                        @if(Auth::user()->id != $user->id)
                            <div>
                                <a href="{{ url('conversation/'.$user->id) }}" class="btn btn-block btn-success"><i class="fa fa-envelope-alt"></i>Envoyer un
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

                                <h3>
                                    {{ $user->firstname}} {{$user->lastname }}
                                </h3>
                            </div>
                            <!-- END USER STATUS-->
                            @if(Auth::user()->id == $user->id)
                                <div class="col-sm-2 col-md-2 hidden-xs">
                                    <a id="edit-profile-button" href="{{ route('user.edit',['user' => $user]) }}"
                                       class="btn btn-block btn-primary">Editer le profil</a>
                                </div>
                                <div class="col-sm-2 col-md-2 hidden-xs">
                                    <a id="" style="margin-top:20px;" href="{{ route('user.annonce',['user' => $user]) }}"
                                       class="btn btn-block btn-primary">Ajouter une annonce</a>
                                </div>
                            @endif
                        </div>
                        <p id="panoramic" class="hidden-xs">
                            <img src="{{ asset('asset/img/gallery/thecity_panoramic.jpg') }}" height="160"
                                 alt="Avatar" class="img-rounded img-responsive">
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-md-2"></div>
                    <div class="col-sm-10 col-md-8-2">
                        <!-- BEGIN TABS SELECTIONS-->
                        <div class="row">
                            <ul id="profileTab" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#pots" data-toggle="tab" aria-expanded="true">Actualités</a>
                                </li>
                                <li>
                                    <a href="#infos" data-toggle="tab" aria-expanded="false">Informations</a>
                                </li>
                                <li>
                                    <a href="#amis" data-toggle="tab" aria-expanded="false">{{count($user->friends)}} @if(count($user->friends)>1) Amis @else
                                            Ami @endif</a>
                                </li>
                                <li>
                                    <a href="#equipement" data-toggle="tab" aria-expanded="false">Equipements</a>
                                </li>
                                <li>
                                    <a href="#photo" data-toggle="tab" aria-expanded="false">Photos</a>
                                </li>
                                <li>
                                    <a href="#annonce" data-toggle="tab" aria-expanded="false">Annonces</a>
                                </li>

                            </ul>
                        </div>
                        <!-- END TABS SELECTIONS-->
                        <div class="row">
                            <!-- BEGIN TABS SECTIONS-->
                            <div id="profileTabContent" class="tab-content">
                                <div class="tab-pane fade active in" id="pots">
                                    <ul class="timeline-2-cols">
                                        <?php $i = 0; ?>
                                        @foreach($user->publications as $publication)
                                            <li id="<?php
                                            if(is_null($publication->activity)){
                                                echo "publication-".$publication->id;
                                            }else{
                                                echo "activite-".$publication->activity->id;
                                            }
                                            ?>" class="publicationJS">
                                                <div class="timeline-badge primary">
                                                </div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-heading row" style="margin: 0;">
                                                        <div style="margin:0 10px 0 0;float:left;">
                                                            <a href="{{ route("user.show", $publication->user->id ) }}">
                                                                <img src="<?php echo $publication->user->picture ?>" alt="Image" class="img-responsive" style="width: 50px;height:50px; margin: 5px;display: inline-block;">
                                                            </a>
                                                        </div>
                                                        <div style="margin: 10px;float:left;">
                                                            @if($publication->user->star == true)
                                                                <img src="{{ asset('images/medal-1.png') }}" alt="medal">
                                                            @endif
                                                            @if(!is_null($publication->association))
                                                                <span>{{$publication->user->firstname.' '.$publication->user->lastname}} - <a href="{{ route('association.show',['association' => $publication->association->id]) }}">{{ $publication->association->name }}</a></span><br>
                                                            @elseif(!is_null($publication->event))
                                                                <span>{{ $publication->user->firstname.' '.$publication->user->lastname}} - <a href="{{ route('event.show',['event' => $publication->event->id]) }}">{{ $publication->event->name }}</a></span><br>
                                                            @else
                                                                <span>{{ $publication->user->firstname.' '.$publication->user->lastname}}</span><br>
                                                            @endif
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
                                                    <div class="timeline-footer">
                                                        <div class="comments" id="comments-{{ $publication->id }}">
                                                            @foreach($publication->commentspost as $comment)
                                                                <div class="comment" id="comment-{{$comment->id}}">
                                                                    <a class="pull-left" href="{{ route("user.show", $comment->user->id ) }}">
                                                                        <img width="35" height="35" class="comment-avatar" alt="{{ $comment->user->firstname.' '.$comment->user->lastname }}" src="{{ $comment->user->picture }}">
                                                                    </a>
                                                                    <div class="comment-body">
                                                                        <span class="message"><strong>{{ $comment->user->firstname.' '.$comment->user->lastname }}</strong> {{ $comment->message }}</span>
                                                                        <span class="time">{{ $comment->timeago($comment->created_at) }}</span>
                                                                    </div>
                                                                    <span class="action">
                                                                        <i class="fa fa-warning" id="signalComment"></i>
                                                                        @if(Auth::user()->id == $comment->user->id)
                                                                            <i class="fa fa-close" id="deleteComment"></i>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                            @if($publication->comments->count() > 3)
                                                                <p class='moreComment' data-url="1">Plus de commentaires</p>
                                                            @endif
                                                            <div class="comment">
                                                                <a class="pull-left" href="{{ route("user.show", $publication->user->id ) }}">
                                                                    <img width="35" height="35" class="comment-avatar" alt="{{Auth::user()->name}}" src="{{ Auth::user()->picture }}">
                                                                </a>
                                                                <div class="comment-body">
                                                                    <input type="text" class="form-control" name="{{ $publication->id }}" id="post-comment" placeholder="Ecris un commentaire...">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="infos">
                                    <br>
                                    <dl class="dl-horizontal">
                                        <dt>Sexe</dt>
                                        <dd>{{ $user->sexe }}</dd>
                                        <dd class="divider"></dd>
                                        <dt>Poste actuel</dt>
                                        <dd>@if(!empty(($user->job)))
                                                {{ $user->job }}
                                            @else
                                                Non renseigné
                                            @endif
                                        </dd>
                                        <dd class="divider"></dd>
                                        <dt>Entreprise</dt>
                                        <dd>@if(!empty(($user->firm)))
                                                {{ $user->firm}}
                                            @else
                                                Non renseigné
                                            @endif
                                        </dd>
                                        <dd class="divider"></dd>
                                        <dt>Scolarité</dt>
                                        <dd>@if(!empty(($user->school)))
                                                {{ $user->school}}
                                            @else
                                                Non renseigné
                                            @endif
                                        </dd>
                                        <dd class="divider"></dd>
                                        <dt>Sports pratiqué</dt>

                                        @if((count($user->sports)) > 1)
                                            @foreach($user->sports as $sport)
                                                <dd>{{ $sport->name }}</dd>
                                            @endforeach
                                        @else
                                            <dd>Non renseigné</dd>
                                        @endif
                                                <dd class="divider"></dd>
                                        <dt>Adresse postal</dt>
                                        <dd>@if(!empty(($user->address)))
                                                {{ $user->address}}
                                            @else
                                                Non renseigné
                                            @endif
                                        </dd>

                                    </dl>
                                </div>
                                <div class="tab-pane fade" id="photo">
                                    @foreach($user->pictures as $picture)
                                        <article class="col-md-4 isotopeItem webdesign">
                                            <div class="section-portfolio-item">
                                                <div class="picture-cadre">
                                                    <div class="picture-box">
                                                        <img src="{{ $picture->link }}" alt="image">
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                                <div class="tab-pane fade" id="equipement">
                                    <div class="row">
                                        @foreach($user->products as $equipment)
                                            <?php $done = 0; ?>
                                            <div class="col-xs-6 col-md-4 eq_row">
                                                <div class="col-md-4">
                                                    <div class="equipement-cadre">
                                                        <div class="equipement-box">
                                                            <img src="{{asset('images/'.$equipment->picture)}}"
                                                                 alt="Avatar" class="img-thumbnail img-responsive">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <a href="{{ route('product.show',['product' => $equipment]) }}" target="_blank">
                                                        <dd>{{ $equipment->name }}</dd>
                                                    </a>
                                                    <dd>{{ $equipment->category->name }}</dd>
                                                    <dd>{{ $equipment->description }}</dd>
                                                    
                                                    @if(!empty(Session::get('produits')))
                                                        @if(Session::get('produits')[0]['category_id'] == $equipment->category->id)
                                                            @foreach(Session::get('produits') as $produit)
                                                                @if($produit->id == $equipment->id)
                                                                    <?php $done = 1; ?>
                                                                @endif
                                                            @endforeach
                                                            @if($done == 0)
                                                                <a href=" {{ route('product.comparator',['product' => $equipment]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <a href=" {{ route('product.comparator',['product' => $equipment]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>

                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="annonce">
                                    <div class="row">
                                        @foreach($user->products as $annonce)
                                            <?php $done = 0; ?>
                                            @if($annonce->sell == 1)
                                                <div class="col-xs-6 col-md-4 eq_row">
                                                    <div class="col-md-4">
                                                        <div class="equipement-cadre">
                                                            <div class="equipement-box">
                                                                <img src="{{asset('images/'.$annonce->picture)}}"
                                                                     alt="Avatar" class="img-thumbnail img-responsive">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <a href="{{ route('product.show',['id' => $equipment->id]) }}">
                                                            <dd>{{ $annonce->name }}</dd>
                                                        </a>
                                                        <dd>{{ $annonce->category->name }}</dd>
                                                        <dd>{{ $annonce->description }}</dd>
                                                        <dd>{{ $annonce->price }}€</dd>

                                                        @if(!empty(Session::get('produits')))
                                                            @if(Session::get('produits')[0]['category_id'] == $annonce->category->id)
                                                                @foreach(Session::get('produits') as $produit)
                                                                    @if($produit['id'] == $annonce->id)
                                                                        <?php $done = 1; ?>
                                                                    @endif
                                                                @endforeach
                                                                @if($done == 0)
                                                                    <a href=" {{ route('product.comparator',['product' => $annonce]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <a href=" {{ route('product.comparator',['product' => $annonce]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>

                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="amis">
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
                                                <a href="{{ route('front.friends.destroy', ['friend' => $friend]) }}">Retirer
                                                    de la liste d'amis</a>
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
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('asset/js/plugins/isotope/jquery.isotope.min.js') }}"></script>
@endsection
