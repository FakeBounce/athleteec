@extends('layouts.admin')


@section('css')

@endsection

@section('content')
<div class="container">
    <div class="row padding margin-top-admin ">
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Newsletter</h3>
                </div>
                <div class="panel-body">
                    <p>Objet : {{ $newsletter->objet }}</p>
                    <div>
                        <?php
                            echo $newsletter->text;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Actions</h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('admin.newsletter.edit', ['newsletter' => $newsletter->id]) }}" class="btn btn-default">Modifier</a>
                        <a href="{{ route('admin.newsletter.delete', ['newsletter' => $newsletter->id]) }}" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Envoyer</h3>
                    </div>
                    <div class="panel-body">
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                        <div class="row">
                            <form class="form_newsletter">
                                <div class="col-xs-12">
                                    Pour envoyer à des fans de sport particuliers, cochez les cases (envoi à tout le monde par défaut)
                                </div>
                                @foreach($sports as $sport)
                                    <div class="col-xs-4">
                                        <input type="checkbox" name="sport[]" value="<?php echo $sport->id; ?>"><?php echo $sport->name; ?>
                                    </div>

                                @endforeach
                                <input type="hidden" name="id" value="<?php echo $newsletter->id; ?>">
                                <div class="col-xs-12">
                                    <span class="btn btn-default send_newsletter">Envoyer</span>
                                </div>
                                <div class="col-xs-12 message_retour">
                                    
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
    <script src="{{ asset('asset/js/jquery/jquery.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('body').on('click','.send_newsletter', function(e){
            var fdata = $(this).parent().parent().serialize();
            console.log(fdata);
            console.log($(this).parent().parent());
            $.ajax({
                type:'POST',
                url:"{{route('admin.newsletter.send')}}",
                data:fdata,
                processData: false,
                success:function(data) {
                    console.log('Success !');
                    $('.message_retour').html(data.message);
                },
                error:function(jqXHR)
                {
                    $('.message_retour').html("Une erreur s'est produite. Veuillez recharger la page et recommencer.");
                }
            });
        });
    </script>
@endsection