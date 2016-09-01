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

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!--Notice .user-profile class-->
            <div class="user-profile">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                        <!-- BEGIN TABS SELECTIONS-->
                        <!-- END TABS SELECTIONS-->
                        <div class="row">
                            <!-- BEGIN TABS SECTIONS-->
                            <div id="profileTabContent" class="tab-content panel-default">
                                    
                                    <div class="col-md-12 padding-bottom-correct" style="margin-top:30px;">
                                        <div class="col-xs-2">
                                            <img class="img-responsive" src="{{asset('images/'.$product->picture)}}">
                                        </div>

                                        <div class="col-xs-10 text-center">
                                            <h1>
                                                {{ $product->name }}
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="brand" class="col-md-2">Marque de l'équipement</label>
                                        <div class="col-md-10">
                                            @foreach($brands as $brand)
                                                @if($brand->id == $product->brand_id)
                                                    {{ $brand->name }}
                                                    <?php 
                                                        $bra = $brand;
                                                        break;
                                                    ?>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="category" class="col-md-2">Catégorie</label>
                                        <div class="col-md-10">
                                            @foreach($categories as $category)
                                                @if($category->id == $product->category_id)
                                                    {{ $category->name }}
                                                    <?php 
                                                        $cat = $category;
                                                        break;
                                                    ?>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @foreach($caracs as $carac)
                                            <?php $done = 0; ?>
                                        @if($carac->category_id == $cat->id)
                                            <div class="col-md-12 padding-bottom-correct">
                                                <label for="brand" class="col-md-2">
                                                    {{ $carac->name }}
                                                </label>
                                                <div class="col-md-10">
                                                    @foreach($carac_vals as $carac_val)
                                                        @if($carac_val->carac_id == $carac->id && $carac_val->product_id == $product->id)
                                                            {{ $carac_val->value }}
                                                            <?php 
                                                                $done = 1;
                                                                break;
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                    @if($done == 0)
                                                        Non renseigné
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="description" class="col-md-2 control-label">Description</label>
                                        <div class="col-md-10">
                                            {{ $product->description }}
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="price" class="col-md-2 control-label">Prix</label>
                                        <div class="col-md-10">
                                            {{ $product->price }}
                                        </div>
                                    </div>
                                    <div class="col-md-12 padding-bottom-correct">
                                        <label for="url" class="col-md-2 control-label">Lien vers l'équipement</label>
                                        <div class="col-md-10">
                                            <a href="{{ $product->url }}">Lien vers l'équipement</a>
                                        </div>
                                    </div>
                                    <div class="form-actions panel-foo">
                                        <div class="btn-group" style="width:100%;">
                                            <div class="image-upload">
                                                <label for="file-input-modal">
                                                </label>
                                            </div>                                    <?php $done = 0; ?>
                                    @if(!empty(Session::get('produits')))
                                        @if(Session::get('produits')[0]['category_id'] == $product->category->id)
                                            @foreach(Session::get('produits') as $produit)
                                                @if($produit['id'] == $product->id)
                                                    <?php $done = 1; ?>
                                                @endif
                                            @endforeach
                                            @if($done == 0)
                                                <a href=" {{ route('product.comparator',['product' => $product]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>
                                            @endif
                                        @endif
                                    @else
                                        <a href=" {{ route('product.comparator',['product' => $product]) }}"><button type="submit" class="btn btn-primary pull-left" ><i class="fa fa-balance-scale" aria-hidden="true"></i></button></a>
                                    
                                    @endif
                                            @if(Auth::user()->id == $product->user_id)
                                                <a href=" {{ route('product.destroy',['product' => $product]) }}"> <button type="submit" class="btn btn-primary pull-right" >Supprimer</button></a> 
                                                <a href=" {{ route('product.edit',['id' => $product->id]) }}"><button type="submit" class="btn btn-primary pull-right" >Modifier</button></a>&nbsp;
                                            @endif
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
                        $("#prod_input").append('<span class="category_select">'+category.name+'</span> ');
                    }
                });
            }
        });
    
        $('body').on('click','.category_select',function(e){
            $('.carac').remove();
            $('#prod_category').val($(this).html());
            
            $(categories).each( function( i, category ) {
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
                    if (category.name.toLowerCase() == val.toLowerCase()) {  
                        cat = 1;
                        $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
                        $(caracs).each( function( i, carac ) {
                            if(carac.category_id == category.id)
                            {
                                    $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><label class="col-md-2 control-label">'+carac.name+'</label><div class="col-md-10"><input type="text" class="form-control" name="carac_'+carac.category_id+'" placeholder="..."></div></div>');
                            }
                        });
                        }
                    else
                    {
                                            
                        $("#prod_input").parent().parent().after('<div class="col-md-12 padding-bottom-correct carac"><button class="btn btn-primary carac_add">Ajouter une caractéristique à la catégorie</button><div>');
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
                        $("#brand_input").append('<span class="brand_select">'+brand.name+'</span> ');
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


