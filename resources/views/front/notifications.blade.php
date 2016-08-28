@extends('layouts.front')

@section('css')
   <link href="{{ asset('asset/css/social.admin.css') }}" rel="stylesheet">
   <link href="{{ asset('asset/css/font-awesome/font-awesome.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('asset/css/themes/admin/facebook.css') }}">
   <link href="{{ asset('asset/css/friends.css') }}" rel="stylesheet">
@endsection

@section('content')
   <div class="panel-body">
       <div class="tab-content">
           <h1>Actions sur le site</h1>
           <div id="activities-feed" class="tab-pane active activities-feed">
               <div class="scroll">
                   <ul class="list-group">
                           @forelse (Auth::user()->getallnotifications as $notification)
                               @if($notification->notification=='users_links')
                                   @if($notification->accepter==true)
                                       <li class="list-group-item">
                                           <a href="/user/{{$notification->userL_id}}">
                                               <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                               <span>&nbsp;Ajout de {{$notification->libelle}} à votre liste d'amis.&nbsp;</span>
                                               <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                           </a>
                                       </li>
                                   @else
                                       <li class="list-group-item">
                                           <a href="/friends">
                                               <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                               <span>&nbsp;Demande d'ami de&nbsp;{{$notification->libelle}}
                                                   <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                           </a>
                                       </li>
                                   @endif
                               @elseif($notification->notification=='events')
                                   @if($notification->accepter==0)
                                       <li class="list-group-item">
                                           <a href="/event/{{$notification->action_id}}">
                                               <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                               <span>&nbsp;<b>{{$notification->libelle}}</b> vous invite à rejoindre l'événement&nbsp;<b>{{$notification->action_name}}</b></span>
                                               <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                           </a>
                                       </li>
                                   @else
                                       <li class="list-group-item">
                                           <a href="/event/{{$notification->action_id}}">
                                               <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                               <span>&nbsp;<b>{{$notification->libelle}}</b> à rejoint l'événement&nbsp;<b>{{$notification->action_name}}</b></span>
                                               <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                           </a>
                                       </li>
                                   @endif
                               @elseif($notification->notification=='associations')

                                   <li class="list-group-item">
                                       <a href="/association/{{$notification->action_id}}">
                                           <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                           <span>&nbsp;<b>{{$notification->libelle}}</b> à rejoint l'association&nbsp;<b>{{$notification->action_name}}</b></span>
                                           <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                       </a>
                                   </li>
                               @elseif($notification->notification=='produitsajout')
                                   @if($notification->accepter == 1)
                                       <li class="list-group-item">
                                           <a href="/equipement/{{$notification->action_id}}">
                                               <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                               <span>&nbsp;<b>{{$notification->libelle}}</b> à valider votre produit : <b>{{$notification->action_name}}</b></span>
                                               <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                           </a>
                                       </li>
                                   @else
                                       <li class="list-group-item">
                                           <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                           <span>&nbsp;<b>{{$notification->libelle}}</b> à refuser votre produit : <b>{{$notification->action_name}}</b></span>
                                           <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                       </li>
                                   @endif
                               @elseif($notification->notification=='produitsajoutstar')
                               <li class="list-group-item">
                                   <a href="/equipement/{{$notification->action_id}}">
                                       <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                       <span>&nbsp;<b>{{$notification->libelle}}</b> avez ajouté votre équipement au catalogue avec succès : <b>{{$notification->action_name}}</b></span>
                                       <span class="feed-time"><em>{{$notification->timeAgo($notification->created_at)}}</em></span>
                                   </a>
                               </li>
                               @endif
                           @empty
                               <li class="list-group-item">
                                   <div class="label label-info feed-icon"><i class="fa fa-info"></i></div>
                                   <span>Il n'y a pas encore eut d'actions effectuée sur le site.</span>
                               </li>
                           @endforelse

                   </ul>
               </div>
           </div>
       </div>
   </div>
@endsection
