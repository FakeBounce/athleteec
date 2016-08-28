@extends('layouts.front')

@section('css')
    <link href="{{ asset('asset/css/layouts/timeline-facebook.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/timeline-2-cols.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/plugins/selectizejs/selectize-default.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_free/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.halflings.css') }}" rel="stylesheet">
    <style type="text/css">
        #map {height: 250px; }

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
    <div class="container user-profile" style="margin-bottom: 50px;">
        <div class="row row-eq-height">
            <div class="col-sm-4 col-md-3">
                <div class="col-md-12 text-center"  style="position: relative;">
                    <img src="{{ $association->picture}}" alt="Avatar" class="img-thumbnail img-responsive img-top">
                    <img src="{{ asset("../images/icons/".$association->sport->icon) }}" alt="{{ $association->sport->name }}" class="img-association">
                </div>
            </div>
            <div class="col-sm-8 col-md-9" style="position: relative">
                <div id="user-status" class="text-left col-sm-10 col-md-10" style="position: absolute;bottom: 10px;">
                    <h1>{{ $association->name }}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            @if($user->id != $association->user_id)
            <div class="col-sm-4">
                <a href="{{ url('conversation/'.$association->user_id) }}" class="btn btn-block btn-success"><i class="fa fa-envelope-alt"></i>Envoyer un message</a>
            </div>
            <div class="col-sm-8 col-md-9">
            @else
            <div class="col-sm-12">
            @endif
                @if($user->isAdminAssociation($association->id))
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <span class="btn btn-block btn-primary send_msg"><i class="fa fa-write"></i>Envoyer un message groupé</span>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <a class="btn btn-block btn-primary" href="{{ route('association.edit', ['association' => $association]) }}"><i class="fa fa-edit"></i>Editer</a>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <a class="btn btn-block btn-primary" href="{{ route('association.delete', ['association' => $association]) }}">Supprimer</a>
                    </div>
                @endif
                @if(!$user->isMemberAssociation($association->id) && !$user->isAdminAssociation($association->id))
                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <a class="btn btn-block btn-primary" href="{{ route('association.join', ['association' => $association]) }}">Rejoindre</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <!-- BEGIN SOCIAL ICONS-->
                <div class="text-center social-icons">
                    @if($association->facebook != "")
                        <a href="{{ $association->facebook }}">
                            <span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-facebook"></i>
                            </span>
                        </a>
                    @endif
                    @if($association->twitter != "")
                        <a href="{{ $association->twitter }}">
                                <span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitter"></i>
                                </span>
                        </a>
                    @endif
                    @if($association->google != "")
                        <a href="{{ $association->google }}">
                                <span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-google-plus"></i>
                                </span>
                        </a>
                    @endif
                </div>
                <div class="association-body-left">
                    <h4>
                        Evènement à venir 
                        @if($user->isAdminAssociation($association->id))
                            <a href="{{url('event/create/'.$association->id)}}" class="pull-right"><i class="fa fa-plus"></i></a>
                        @endif
                    </h4>
                    <ul class="list-unstyled">
                        @foreach($association->events as $event)
                            <li class="associations_events">
                                <a href="{{url('event/'.$event->id)}}"><i class="fa fa-calendar"></i> {{$event->name}} <span class="pull-right">{{$event->started_at}}</span></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="association-body-left">
                    <h4>Localisation</h4>
                    <div id="map"></div>
                </div>
                @if($user->isMemberAssociation($association->id))
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <a class="btn btn-block btn-primary" href="{{ route('association.quit', ['association' => $association]) }}">Quitter</a>
                    </div>
                @endif
            </div>
            <div class="col-sm-8 col-md-9">
                <div class="row" style="margin:0;">
                    <ul id="profileTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#anchorpost" data-toggle="tab" aria-expanded="true">Actualités</a>
                        </li>
                        <li>
                            <a href="#info" data-toggle="tab" aria-expanded="false">Description</a>
                        </li>
                        <li>
                            <a href="#member" data-toggle="tab" aria-expanded="false">Membres</a>
                        </li>
                        <li>
                            <a href="#video" data-toggle="tab" aria-expanded="false">Videos</a>
                        </li>
                    </ul>
                </div>
                <!-- END TABS SELECTIONS-->
                <div class="row" style="margin:0;border : 1px solid #dddddd;border-top: none;padding:20px">
                    <!-- END TABS SECTIONS-->
                    <!-- BEGIN TABS SECTIONS-->
                    <div id="profileTabContent" class="tab-content col-sm-12 col-md-12">
                        <div class="tab-pane fade active in" id="anchorpost">
                            @if(isset($isMember) && is_array($isMember) && count($isMember) > 0)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">Quoi de neuf ?</div>
                                        <div class="panel-tools pull-right">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#tab_home" aria-expanded="true">Statut</a>
                                                </li>
                                                <li class="">
                                                    <a data-toggle="tab" href="#tab_profile" aria-expanded="false">Activité</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" style="padding: 0px">
                                        <div class="tab-content">
                                            <div class="tab-pane fade active in" id="tab_home">
                                                <form role="form" style="position: relative" action="{{ route("association.post.store", ['assocation' => $association->id])}}" method="post" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <textarea id="user-post" name="message_status" placeholder="Partage ton statut" rows="3" class="form-control" style="resize: none;border: none;box-shadow: none" ></textarea>
                                                    <div class="form-actions panel-foo">
                                                        <div class="btn-group">
                                                            <div class="image-upload">
                                                                <label for="file-input">
                                                                    <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                                                </label>
                                                                <input id="file-input" name="picture_status" type="file"/>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary pull-right">Post</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="tab_profile">
                                                <form role="form" style="position: relative" action="{{ route("association.act.store", ['assocation' => $association->id])}}" method="post" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div style="padding: 10px;">
                                                        <div class="form-group">
                                                            <label for="select-beast" class="">Sport :</label>
                                                            <select id="select-beast" class="demo-default" name="sport_act">
                                                                @foreach($sports as $sport)
                                                                    <option value="{{ $sport->id }}">
                                                                        {{ $sport->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="datetimepicker1" class="">Date de début :</label>
                                                            <div class="input-group date" id="datetimepicker1">
                                                                <input type="text" class="form-control" placeholder="__/__/____ __:__" name="date_start_act">
                                                        <span class="input-group-addon">
                                                        <span class="fa-calendar fa"></span>
                                                        </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="datetimepicker2" class="">Durée</label>
                                                            <div id="datetimepicker2">
                                                                <div class="input-group time">
                                                                    <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon1" name="time_h_act">
                                                                    <span class="input-group-addon" id="basic-addon1">H</span>
                                                                </div>
                                                                <div class="input-group time">
                                                                    <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon2" name="time_m_act">
                                                                    <span class="input-group-addon" id="basic-addon2">MIN</span>
                                                                </div>
                                                                <div class="input-group time">
                                                                    <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon3" name="time_s_act">
                                                                    <span class="input-group-addon" id="basic-addon3">SEC</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="user-post">Donne ton ressenti</label>
                                                            <textarea id="user-post" name="message_status" placeholder="Message..." rows="3" class="form-control" style="resize: none;border: none;" ></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions panel-foo">
                                                        <div class="btn-group">
                                                            <div class="image-upload">
                                                                <label for="file-input2">
                                                                    <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                                                </label>
                                                                <input id="file-input2" name="picture_status" type="file"/>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary pull-right">Post</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <ul class="timeline-2-cols">
                                @foreach($association->publications as $publication)
                                    <li id="<?php
                                    if(is_null($publication->activity)){
                                        echo "publication-".$publication->id;
                                    }else{
                                        echo "activite-".$publication->activity->id;
                                    }
                                    ?>" class="publicationJS">
                                        <div class="timeline-badge primary">
                                            <a href="#"><i rel="tooltip" title="{{ $publication->date_start }}" class="glyphicon glyphicon-record>"></i>
                                            </a>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading row" style="margin: 0;">
                                                <div style="margin:0 10px 0 0;float:left;">
                                                    <a href="{{ route("user.show", $publication->user->id ) }}">
                                                        <img src="{{ $publication->user->picture }}" alt="Image" class="img-responsive" style="width: 50px;height:50px; margin: 5px;display: inline-block;border-radius:25px;">
                                                    </a>
                                                </div>
                                                <div style="margin: 10px;float:left;">
                                                    @if($publication->user->star == true)
                                                        <img src="{{ asset('images/medal-1.png') }}" alt="medal">
                                                    @endif
                                                    <span>{{ $publication->user->firstname.' '.$publication->user->lastname}}</span><br>
                                                    <small><i aria-hidden="true" class="fa fa-clock-o"></i> {{ $publication->timeAgo($publication->created_at) }}</small>

                                                    <div class="btn-group dropdown-post">
                                                        <button class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size: 8px;"><i class="fa fa-chevron-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right">
                                                            @if(Auth::user()->id == $publication->user_id)
                                                                <li>
                                                                    <a href="#" onclick="
                                                        <?php
                                                                    if(is_null($publication->activity)){
                                                                        echo "editpost(".$publication->id.")";
                                                                    }else{
                                                                        echo "editact(".$publication->activity->id.")";
                                                                    }
                                                                    ?>">
                                                                        <span class="fa fa-pencil"></span> Modifier</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" id="deletepost">
                                                                        <span class="fa fa-trash-o"></span> Supprimer</a>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <a href="#" id="signalepost">
                                                                    <span class="fa fa-exclamation-triangle"></span> Signaler</a>
                                                            </li>
                                                        </ul>
                                                    </div>
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
                                                                <img width="35" height="35" style="border-radius:20px;" class="comment-avatar" alt="{{ $comment->user->firstname.' '.$comment->user->lastname }}" src="{{ $comment->user->picture }}">
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
                                                            <img width="35" height="35" style="border-radius:20px;" class="comment-avatar" alt="{{Auth::user()->name}}" src="{{ Auth::user()->picture }}">
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
                        <div class="tab-pane fade" id="info">
                            <p>
                                <?php echo $association->description ?>
                            </p>
                        </div>
                        <div class="tab-pane fade" id="member">
                            @foreach($association->members as $member)
                                <div class="col-xs-3 col-sm-5 col-md-3 col-lg-2" style="text-align: center">
                                    <img src="{{ $member->user->picture }}" alt="{{$member->user->firstname}}" style="height: 100px;width: auto" style="border-radius:50px;"><br>
                                    {{ $member->user->firstname }} {{ $member->user->lastname }}
                                    @if($user->id == $association->user_id && $user->id != $member->user->id)
                                        <?php
                                            $dest = "";
                                            $promot = "";
                                            if($member->user->isAdminAssociation($association->id)){
                                                $promot = "display:none;";
                                            }
                                            else{
                                                $dest = "display:none;";
                                            }
                                        ?>
                                        <br>
                                        <button class="btn btn-default" style="padding:4px;{{$dest}}" id="destituer" data-text="{{$member->id}}">Destituer</button>
                                        <button class="btn btn-default" style="padding:4px;{{$promot}}" id="promouvoir" data-text="{{$member->id}}">Promouvoir</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="video">
                            @foreach($association->videos() as $video)
                                <div class="col-sm-6 col-md-6">
                                    <div class="video-container">
                                        <iframe src="https://www.youtube.com/embed/{{$video->url}}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-post" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-post">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Modifier votre publication</h4>
                </div>
                <div class="modal-body">
                    <form id="submit-modal-post" method="post" enctype="multipart/form-data">
                        <textarea id="user-post-modal-post" name="message_status_modal" placeholder="Partage ton statut" rows="3" class="form-control" style="resize: none;border: none;box-shadow: none" ></textarea>
                        <div class="form-actions panel-foo">
                            <div class="btn-group">
                                <div class="image-upload">
                                    <label for="file-input-modal">
                                        <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                    </label>
                                    <input id="file-input-modal" name="picture_status_modal" type="file" accept="image/*"/>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right" >Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-activity" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-activity">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Modifier votre activité</h4>
                </div>
                <div class="modal-body">
                    <form id="submit-modal-activity" method="post" enctype="multipart/form-data">
                        <div style="padding: 10px;">
                            <div class="form-group">
                                <label for="select-beast-act" class="">Sport :</label>
                                <select id="select-beast-act" class="demo-default" name="sport_act_modal">
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->id }}">
                                            {{ $sport->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="datetimepicker1-modal" class="">Date de début :</label>
                                <div class="input-group date" id="datetimepicker1-modal">
                                    <input type="text" class="form-control" placeholder="__/__/____ __:__" name="date_start_act_modal" id="date_start_act_modal">
                                <span class="input-group-addon">
                                    <span class="fa-calendar fa"></span>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="datetimepicker2-modal" class="">Durée</label>
                                <div id="datetimepicker2-modal">
                                    <div class="input-group time">
                                        <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon1" name="time_h_act_modal" id="time_h_act_modal">
                                        <span class="input-group-addon" id="basic-addon1">H</span>
                                    </div>
                                    <div class="input-group time">
                                        <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon2" name="time_m_act_modal" id="time_m_act_modal">
                                        <span class="input-group-addon" id="basic-addon2">MIN</span>
                                    </div>
                                    <div class="input-group time">
                                        <input type="text" class="form-control" placeholder="00" aria-describedby="basic-addon3" name="time_s_act_modal" id="time_s_act_modal">
                                        <span class="input-group-addon" id="basic-addon3">SEC</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user-post-modal">Donne ton ressenti</label>
                                <textarea id="user-post-modal-act" name="message_act_modal" placeholder="Message..." rows="3" class="form-control" style="resize: none;border: none;" ></textarea>
                            </div>
                        </div>
                        <div class="form-actions panel-foo">
                            <div class="btn-group">
                                <div class="image-upload">
                                    <label for="file-input2-modal">
                                        <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                    </label>
                                    <input id="file-input2-modal" name="picture_act_modal" type="file"/>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-delete">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Suppression de votre publication</h4>
                </div>
                <div class="modal-body">
                    Etes vous sur de vouloir supprimer cette publication ?
                </div>
                <form id="delete-modal-post" method="post">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                        <button type="submit" class="btn btn-primary" id="confirm">Oui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-signal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-signal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Signalement de la publication</h4>
                </div>
                <div class="modal-body">
                    Le signalement a bien été pris en compte! Merci
                </div>
                <form id="signal-modal-post" method="post">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-signal-comment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal-signal-comment">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Signalement du commentaire</h4>
                </div>
                <div class="modal-body">
                    Le signalement a bien été pris en compte! Merci
                </div>
                <form id="signal-modal-post" method="post">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="return"></div>
    <input id="loadAllPublication" value="1" type="hidden" />
@endsection

@section('js')
    <script src="{{ asset('asset/js/plugins/moment.js/moment-with-locales.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.fr.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/selectize.js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('asset/js/scroll.js') }}"></script>
    <script>
        function init(){
            initMap();
        }

        var latd = parseFloat({{ $association->lattitude }});
        var lngd= parseFloat({{ $association->longitude }});

        var myLatLng = {lat: 48.866667, lng: 2.333333};

        if(latd && lngd){
            myLatLng = {lat: latd, lng: lngd};
        }
        var map;
        var marker;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 11
            });

            marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            });
        }
        
        
        $('body').on('click','.send_msg',function(){
           $('.form_assoc').remove();
            $(this).parent().append('<form class="form_assoc"><textarea name="text"></textarea><a class="valid_form_assoc">Valider</a></form>');
        });
        
        $('body').on('click','.valid_form_assoc',function(){
            var fdata = $('.form_assoc').serialize();
            $.ajax({
                url: "{{ route('association.msg', ['association' => $association->id])}}",
                type: "post",
                dataType: 'json', // selon le retour attendu
                data: fdata,
                success: function(data) {
                   console.log('Success !');
                },
                error:function(jqXHR)
                {
                    console.log('Erreur');
                    $('.return').html(jqXHR.responseText);
                }
            });
        });
        // [END region_geolocation]
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWyHTNe168m9pt0cOiXjlIL9BBUaYT2SI&libraries=geometry,places&callback=init"
            async defer></script>
@endsection