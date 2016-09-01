@extends('layouts.front')

@section('css')
    <link href="{{ asset('asset/css/layouts/timeline-facebook.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/layouts/timeline-2-cols.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/plugins/selectizejs/selectize-default.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_free/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.halflings.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="button-group filters-button-group text-center">
                <button class="button is-checked filter_b noborder btn-primary" data-filter="*" style="margin-bottom:15px;">Aucun filtre</button><br>
                @foreach($sports as $sport)
                <button class="button filter_b btn-primary noborder" data-filter=".sport<?php echo $sport->id; ?>"><?php echo $sport->name; ?></button>
                @endforeach
            </div>
                        <div class="col-md-12">
                <!-- //Notice .timeline-2-cols class-->
                <ul class="timeline-2-cols grid">
                    <li>
                        <!-- //Notice .timeline-badge class-->
                        <div class="timeline-badge primary">
                            <a href=""><i rel="tooltip" title="11 hours ago via Twitter" class="glyphicon glyphicon-record"></i>
                            </a>
                        </div>
                        <div class="timeline-panel panel panel-default">
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
                                        <form role="form" style="position: relative" action="{{ route("publication.store")}}" method="post" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                        <textarea id="user-post" name="message_status" placeholder="Partage ton statut" rows="3" class="form-control" style="resize: none;border: none;box-shadow: none"></textarea>
                                        <div class="form-actions panel-foo">
                                            <div class="btn-group">
                                                <div class="image-upload">
                                                    <label for="file-input">
                                                        <div class="btn btn-default"><i class="fa fa-camera"></i></div>
                                                    </label>
                                                    <input id="file-input" name="picture_status" type="file"/>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary pull-right">Publier</button>
                                        </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="tab_profile">
                                        <form role="form" style="position: relative" action="{{ route("activity.store")}}" method="post" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div style="padding: 10px;">
                                                <div class="form-group">
                                                    <label for="select-beast" class="" >Sport :</label>
                                                    <select id="select-beast" class="demo-default" name="sport_act" required>
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
                                                        <input type="text" class="form-control" placeholder="__/__/____ __:__" name="date_start_act" required>
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
                                                    <textarea id="user-post" name="message_status" placeholder="Message..." rows="3" class="form-control" style="resize: none;border: none;" required ></textarea>
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
                                                <button type="submit" class="btn btn-primary pull-right">Publier</button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    
                                    @if(count($publications)==0)
                        <li class="timeline-inverted">
                            <div class="timeline-panel">
                                <div class="timeline-body">
                                    <div class="post_activity_msg">
                                        <h1>C4est votre première fois?</h1>
                                        <div>Postez votre humeur sportive pour commencer l'aventure ATHLETEEC!</div>
                                        <br>
                                        <div>Recherchez vos amis sportifs, des événements, des associations, des produits via la barre de recherche ! C'est simple et efficace..</div>
                                        <br>
                                        <br>
                                        <div>Utilisez le menu à gauche pour créer vos propres événements, associations, discuter avec vos amis ou comparer des produits !</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    
                    
                    
                    
                    
                    
                    <?php 
                        if(!empty(Auth::user()->sports) && !empty($star_pub)) 
                        {
                    ?>
                            <li id="<?php
                                    if(is_null($star_pub->activity)){
                                        echo "publication-".$star_pub->id;
                                    }else{
                                        echo "activite-".$star_pub->activity->id;
                                    }
                                ?>" class="star 
                                    <?php 
                                           if(!is_null($star_pub->activity)) 
                                           { 
                                               echo "sport".$star_pub->activity->sport->id; 
                                           }
                                    ?> publicationJS">
                                <div class="timeline-panel">
                                    <div class="timeline-heading row" style="margin: 0;">
                                        <div style="margin:0 10px 0 0;float:left;">
                                            <a href="{{ route("user.show", $star_pub->user->id ) }}">
                                                <img src="<?php echo $star_pub->user->picture ?>" alt="Image" class="img-responsive" style="width: 50px;height:50px; margin: 5px;display: inline-block;">
                                            </a>
                                        </div>
                                        <div style="margin: 10px;float:left;">
                                            @if($star_pub->user->star == true)
                                                <img src="{{ asset('images/medal-1.png') }}" alt="medal">
                                            @endif
                                            @if(!is_null($star_pub->association))
                                                <span>{{$star_pub->user->firstname.' '.$star_pub->user->lastname}} - <a href="{{ route('association.show',['association' => $star_pub->association->id]) }}">{{ $star_pub->association->name }}</a></span><br>
                                            @elseif(!is_null($star_pub->event))
                                                <span>{{ $star_pub->user->firstname.' '.$star_pub->user->lastname}} - <a href="{{ route('event.show',['event' => $star_pub->event->id]) }}">{{ $star_pub->event->name }}</a></span><br>
                                            @else
                                                <span>{{ $star_pub->user->firstname.' '.$star_pub->user->lastname}}</span><br>
                                            @endif
                                            <small><i aria-hidden="true" class="fa fa-clock-o"></i> {{ $star_pub->timeAgo($star_pub->created_at) }}</small>
                                            <div class="btn-group dropdown-post">
                                                <button class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size: 8px;"><i class="fa fa-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right">
                                                    @if(Auth::user()->id == $star_pub->user_id)
                                                        <li>
                                                            <a href="#" onclick="
                                                                <?php
                                                                    if(is_null($star_pub->activity)){
                                                                        echo "editpost(".$star_pub->id.")";
                                                                    }else{
                                                                        echo "editact(".$star_pub->activity->id.")";
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
                                        @if(is_null($star_pub->activity))
                                            <div class="post_activity_msg">
                                                {{$star_pub->message}}
                                            </div>
                                            <div class="post_picture_video">
                                                @if(!is_null($star_pub->video))
                                                    <div class="video-container"><iframe src="https://www.youtube.com/embed/{{$star_pub->video->url}}" frameborder="0" allowfullscreen></iframe></div>
                                                @elseif(!is_null($star_pub->picture))
                                                    <img src="{{ asset($star_pub->picture) }}" alt="" class="img-responsive">
                                                @endif
                                            </div>

                                        @else
                                            <div class="post_picture_video">
                                                @if(!is_null($star_pub->video))
                                                    <div class="video-container"><iframe src="https://www.youtube.com/embed/{{$star_pub->video->url}}" frameborder="0" allowfullscreen></iframe></div>
                                                @elseif(!is_null($star_pub->picture))
                                                    <img src="{{ asset($star_pub->picture) }}" alt="" class="img-responsive">
                                                @endif
                                            </div>
                                            <div class="post_activity">
                                                <div class="post_activity_img">
                                                    <img src="{{ asset("../images/icons/".$star_pub->activity->sport->icon) }}" alt="{{ $star_pub->activity->sport->name }}" class="img-responsive">
                                                </div>
                                                <div class="post_activity_stats">
                                                    <span data-text="{{$star_pub->activity->date_start}}"><i aria-hidden="true" class="fa fa-calendar"></i>{{$star_pub->activity->getDateStartString() }}</span>
                                                    <span data-text="{{$star_pub->activity->getTimeSecondes() }}">Durée : {{$star_pub->activity->time }}</span>
                                                </div>

                                            </div>
                                            <div class="post_activity_msg">
                                                    {{$star_pub->message}}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="timeline-footer">
                                        <div class="comments" id="comments-{{ $star_pub->id }}">
                                            @foreach($star_pub->commentspost as $comment)
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
                                            @if($star_pub->comments->count() > 3)
                                                 <p class='moreComment' data-url="1">Plus de commentaires</p>
                                            @endif
                                            <div class="comment">
                                                <a class="pull-left" href="{{ route("user.show", $star_pub->user->id ) }}">
                                                    <img width="35" height="35" class="comment-avatar" alt="{{Auth::user()->name}}" src="{{ Auth::user()->picture }}">
                                                </a>
                                                <div class="comment-body">
                                                    <input type="text" class="form-control" name="{{ $star_pub->id }}" id="post-comment" placeholder="Ecris un commentaire...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                    
                   
                    <?php
                        }
                    ?> 
                    
                    
                    
                    
                    
                    
                    
                    <?php $i = 0;$y = 0; ?>
                    @for($z =0; $z < count($publications);$z++)
                        <?php
                            $publication = $publications[$z];
                        ?>
                        <li id="<?php
                                if(is_null($publication->activity)){
                                    echo "publication-".$publication->id;
                                }else{
                                    echo "activite-".$publication->activity->id;
                                }
                            ?>" class="
                                <?php 
                                       if($i%2){echo 'timeline-inverted';} 
                                       if(!is_null($publication->activity)) 
                                       { 
                                           echo " element-item sport".$publication->activity->sport->id; 
                                       } 
                                       
                                       else
                                       {
                                           echo " element-item ";
                                       }
                                ?> publicationJS">
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
                    @endfor
                    <li style="float: none;" class="clearfix"></li>
                </ul>
                <input id="loadAllPublication" value="1" type="hidden" />
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
                        <button type="submit" class="btn btn-primary pull-right" >Poster</button>
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
                        <button type="submit" class="btn btn-primary pull-right">Poster</button>
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

@endsection

@section('js')
    <script src="{{ asset('asset/js/plugins/moment.js/moment-with-locales.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/bootstrap-datetimepicker/locales/bootstrap-datetimepicker.fr.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/selectize.js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('asset/js/scroll.js') }}"></script>
    <script src="{{ asset('asset/js/isotope.pkgd.min.js') }}"></script>


    <script>

// init Isotope
        var $grid = $('.grid').isotope({
          itemSelector: '.element-item',
          layoutMode: 'fitRows'
        });
        // bind filter button click
        $('.filters-button-group').on( 'click', 'button', function() {
          var filterValue = $( this ).attr('data-filter');
          // use filterFn if matches value
          $grid.isotope({ filter: filterValue });
        });
        // change is-checked class on buttons
        $('.button-group').each( function( i, buttonGroup ) {
          var $buttonGroup = $( buttonGroup );
          $buttonGroup.on( 'click', 'button', function() {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $( this ).addClass('is-checked');
          });
        });
        
        /*        // bind filter button click
        $('.filters-button-group').on( 'click', 'button', function() {
          var filterValue = $( this ).attr('data-filter');
          // use filterFn if matches value
            $('.element-item').each( function( i, buttonGroup ) {
                if(filterValue == "*")
                {
                    $(this).show();
                }
                else if(!($(this).hasClass(filterValue)))
                {
                    $(this).hide();
                }
                else
                {
                    $(this).show();
                }
            });
          
        });*/
    </script>
@endsection
