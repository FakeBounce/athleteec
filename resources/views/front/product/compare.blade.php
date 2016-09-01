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
                                
                                @if(!empty(Session::get('produits')))

                                    <table style="width:100%;" class="panel-foo">
                                        
                                        
                        
                                        <h1 class="text-center" style="margin:0px;background:white;padding:15px;">
                                            Comparateur de produits <br><br>
                                        </h1>
                                        
                                    <?php 
                                        $produits = Session::get('produits');       
                                    ?>
                                                <tr>
                                                    <th>
                                                        Produit
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                    <td>
                                                        <h4><a href="{{ route('product.remove',['id' => $produits[$i]]) }}" > {{$produits[$i]['name']}} <i class="fa fa-minus-circle" aria-hidden="true"  style="cursor:pointer;margin-left:10px;"></i></a></h4>
                                                    </td>
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                    <td>
                                                        {{$produits[$i]['description']}}
                                                    </td>
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Marque
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                            @foreach($brands as $brand)
                                                                @if($brand->id == $produits[$i]['brand_id'])
                                                                    <td>
                                                                        {{$brand->name}}
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Catégorie
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                            @foreach($categories as $category)
                                                                @if($category->id == $produits[$i]['category_id'])
                                                                    <td>
                                                                        {{$category->name}}
                                                                        <?php 
                                                                            $cat = $category;
                                                                            break;
                                                                        ?>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>

                                                @foreach($caracs as $carac)
                                                    @if($carac->category_id == $cat->id)
                                                        <?php 
                                                            $done = 0;
                                                        ?>
                                                        <tr>
                                                            <th>
                                                                {{$carac->name}}
                                                            </th>
                                                            <?php
                                                                for($i = 0; $i<count($produits);$i++)
                                                                {
                                                            ?>
                                                                    @foreach($carac_vals as $carac_val)
                                                                        @if($carac_val->product_id == $produits[$i]['id'])
                                                                            <td>
                                                                                {{$carac_val->value}}
                                                                                <?php 
                                                                                    $done = 1;
                                                                                    break;
                                                                                ?>
                                                                            </td>
                                                                        @endif
                                                                    @endforeach
                                                                    @if($done == 0)
                                                                        <td>Non renseigné</td>
                                                                    @endif
                                                            <?php          
                                                                }
                                                            ?>
                                                        </tr>
                                                        
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <th>
                                                        Prix
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                    <td>
                                                        {{$produits[$i]['price']}}€
                                                    </td>
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Lien
                                                    </th>
                                                    <?php
                                                        for($i = 0; $i<count($produits);$i++)
                                                        {
                                                    ?>
                                                    <td>
                                                        <a href="{{$produits[$i]['url']}}">{{$produits[$i]['url']}}</a>
                                                    </td>
                                                    <?php          
                                                        }
                                                    ?>
                                                </tr>
                                        
                                        
                                    </table>
                                
                                    <div class="form-actions">
                                        <div class="btn-group" style="width:100%;">
                                            <div class="image-upload">
                                                <label for="file-input-modal">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="panel panel-default col-xs-8 col-xs-offset-2">
                                        <div class="conversation_div">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h4 class="text-center">
                                                        Vous n'avez pas encore de ajouter de produits. <br><br>
                                                        Cliquez sur l'icône <button class="btn btn-primary" style="cursor:none;"><i class="fa fa-balance-scale" aria-hidden="true"></i></button> à côté d'un produit pour l'ajouter au comparateur !<br>
                                                        Vous ne pouvez ajouter que des produits de la même catégorie !
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
            if(val_length != 0)
            {
                $(categories).each( function( i, category ) {
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