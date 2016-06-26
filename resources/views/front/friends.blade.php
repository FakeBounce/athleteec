@extends('layouts.front')

@section('css')
    <link href="{{ asset('asset/css/social.core.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/social.admin.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/font-awesome/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/themes/frontend/blue.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/themes/admin/facebook.css') }}">
    <link href="{{ asset('asset/css/friends.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="section-about">
  <div class="panel panel-default section">
    <div class="container">
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <h2 data-animation="bounceInUp" class="section-heading animated titleoffriends">Rechercher des amis</h2>
          </div>
          <form method="GET" action="{{ url('search/autocomplete') }}">
              {{ csrf_field() }}
              <input id="terme" placeholder="Rechercher un utilisateur" name="terme" type="text" value="">
              <input class="btn btn-default" type="submit" value="Search">
          </form>
        </div>
      </div>


    <div class="row">
        @forelse($user->friends as $friend)
            <div class="col-md-2 onefriend">
                <div class="team-member">
                    <a href="/user/{{ $friend->id }}">
                        <figure class="member-photo">
                            <img class="imgonefriend" src="{{ $friend->picture }}" alt="{{ $friend->firstname }} {{ $friend->lastname }}" width="100px" height="100px">
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








      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <h2 data-animation="bounceInUp" class="section-heading animated titleoffriends">Mes amis</h2>
          </div>
        </div>
      </div>
      <div class="row">
        @forelse($user->friends as $friend)
        <div class="col-md-2 onefriend">
          <div class="team-member">
             <a href="/user/{{ $friend->id }}">
                 <figure class="member-photo">
                     <img class="imgonefriend" src="{{ $friend->picture }}" alt="{{ $friend->firstname }} {{ $friend->lastname }}" width="100px" height="100px">
                 </figure>
                 <div class="team-detail">
                     <h4>{{ $friend->firstname }} {{ $friend->lastname }}</h4>
                 </div>
             </a>
          </div>
        </div>
        @empty
        <p>Vous n'avez pas encore ajoutés d'amis.</p>
        @endforelse
      </div>
    </div>
  </div>
</section>
@endsection

@section('js')
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
        $(function(){
             $( "#terme" ).autocomplete({
                  source: "{{ url('search/autocomplete') }}",
                  minLength: 3,
                  select: function(event, ui) {
                    $('#terme').val(ui.item.value);
                    console.log('passe dans la fonction?');
              }
            });
        });
    </script>
@endsection
