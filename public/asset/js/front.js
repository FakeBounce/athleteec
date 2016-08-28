/**
 * Created by Max on 05/06/2016.
 */

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function() {
    var publicationDestroy = 0;
    $("body").on('click','#deletepost' ,function(e){
        parent = $(this).parents(".publicationJS");
        var splitID = parent.attr("id").split("-");
        console.log(splitID);
        if(splitID[0] == 'activite'){
            publicationDestroy = 'activity/'+splitID[1]+'/destroyAjax';
        }
        else{
            publicationDestroy = 'publication/'+splitID[1]+'/destroyAjax';
        }

        $('#modal-delete').modal('show');
    });
    $("body").on('click','#demandeStar' ,function(e){
        var elem = $(this);
        $.ajax({
            url: '/user/demandeStar',
            type: 'post',
            success: function(data) {
                if(data['success']){
                    var elemnext = elem.next();
                    elem.remove();
                    elemnext.html(data['message']);
                }
            }
        });
    });
    $("body").on('click','#demandeStarRemove' ,function(e){
        var elem = $(this);
        $.ajax({
            url: '/user/demandeStarRemove',
            type: 'post',
            success: function(data) {
                if(data['success']){
                    var elemnext = elem.next();
                    elem.remove();
                    elemnext.html(data['message']);
                }
            }
        });
    });

    $("body").on('click','#signalepost' ,function(e){
        var self = $(this);
        parent = $(this).parents(".publicationJS");
        var splitID = parent.attr("id").split("-");
        if(splitID[0] == 'activite'){
            publicationDestroy = 'activity/'+splitID[1]+'/signaleAjax';
        }
        else{
            publicationDestroy = 'publication/'+splitID[1]+'/signaleAjax';
        }
        $.ajax({
            url: "/"+publicationDestroy,
            type: 'post',
            data: {},
            success: function(data) {
                if(data['success'] == true)
                {
                    self.remove();
                    $('#modal-signal').modal('show');
                }
            }
        });
    });

    $("body").on('click','#addproduct' ,function(e){
        $('#modal-product').modal('show');
    });

    $("body").on('change','#file-input-modal',function(e){
        readURL(this);
    });

    $("body").on('click','#addphoto' ,function(e){
        $('#modal-photo').modal('show');
    });

    $('#delete-modal-post').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "/"+publicationDestroy,
            type: 'post',
            data: {},
            success: function(data) {
                if(data['success'] == true)
                {
                    $('#modal-delete').modal('hide');
                    location.reload();
                }
            }
        });
    });
    $("body").on('submit','#submit-modal-product' ,function(e){
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: "/product/addAjax",
            type: 'post',
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            data: new FormData($("#submit-modal-product")[0]),
            success: function(data) {
                if(data['success'] == true)
                {
                    console.log(data);
                    $('#modal-product').modal('hide');

                    $productname = data['productname'];
                    $picture = data['picture'];
                    $url = data['url'];
                    $price = data['price'];
                    $description = data['description'];
                    $productId = data['productId']

                    var parent = $('.tab-pane.active.equipement').first();
                    var div = parent.children().first() ;

                    div.append("<div class ='row'>" +
                        "<ul class='list-unstyled'></dd>" +
                            "<li>" +
                                "<div class='col-md-1'>" +
                                    "<div class='equipement-cadre'>" +
                                        "<div class='equipement-box'>"+
                                            "<img src='http://localhost/images"+$picture+"'"+"alt='Avatar' class='img-thumbnail img-responsive'>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='col-md-9'>"+
                                    "<a href='" + $url + "'>"+
                                        "<dd>" + $productname + "</dd>"+
                                    "</a>"+
                                    "<dd>" + $description + "</dd>"+
                                "</div>"+
                                "<div class='col-md-1 checkbox-correct'>"+
                                    "<input type='checkbox' id='"+ $productId +"' name='equipement[]' value='" + $productId + "'>"+
                                "</div>"+
                            "</li>"+
                        "</ul>"+
                    "</div>"+
                    "</div>");
                }
            },
            error:function(jqXHR)
            {
                console.log(jqXHR.responseText);
            }
        });
    });
    $("body").on('submit','#submit-modal-photo' ,function(e){
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: "/picture/addAjax",
            type: 'post',
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            data: new FormData($("#submit-modal-photo")[0]),
            success: function(data) {
                if(data['success'] == true)
                {
                    $('#modal-photo').modal('hide');

                    $picture = data['picture'];

                    var parent = $('.tab-pane.active.photos').first();
                    var div = parent.children().first() ;

                    div.append("<article class='col-md-4 isotopeItem webdesign'>"+
                                "<div class='section-portfolio-item'>" +
                                    "<div class='picture-cadre'>"+
                                        "<div class='picture-box'>"+
                                            "<img src='http://athleteec.razdragor.fr/public/images/users/"+$picture+"' alt='image'>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</article>");
                }
            }
        });
    });
    $('#submit-modal-post').submit(function(e){
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: "/" +$form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'json', // selon le retour attendu
            data: new FormData($("#submit-modal-post")[0]),
            success: function(data) {
                if(data['success'] == true)
                {
                    $publication1 = data['publication'];
                    $video1 = data['video'];
                    if($video1){
                        var video = $('#publication-' + $publication1.id + ' .timeline-body .post_picture_video .video-container iframe');
                        if(isEmpty(video)) {
                           video.attr('src', "https://www.youtube.com/embed/" + $video1);
                        }
                        else{
                            $('#publication-' + $publication1.id + ' .timeline-body .post_picture_video').html('<div class="video-container"><iframe src="https://www.youtube.com/embed/'+$video1+'"  frameborder="0" allowfullscreen></iframe></div>');
                        }
                    }
                    else if($publication1.picture){
                        var img = $('#publication-' + $publication1.id + ' .timeline-body .post_picture_video img');
                        if(isEmpty(img)) {
                            $("#publication-" + $publication1.id + " .timeline-body img").attr('src', $publication1.picture);
                        }
                        else{
                            $('#publication-' + $publication1.id + ' .timeline-body .post_picture_video').html('<img src="'+$publication1.picture+'" alt="" class="img-responsive">');
                        }
                    }
                    $("#publication-"+$publication1.id+" .timeline-body .post_activity_msg").text($publication1.message);
                    $('#modal-post').modal('hide');
                    $("#user-post-modal-post").text("");
                }
            }
        });
    });

    $('#submit-modal-activity').submit(function(e){
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: "/" + $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'json', // selon le retour attendu
            data: new FormData($("#submit-modal-activity")[0]),
            success: function(data) {
                if(data['success'] == true)
                {
                    $activity1 = data['activity'];
                    $sport1 = data['sport'];
                    $video1 = data['video'];
                    var act = $('#activite-' + $activity1.id);
                    if($video1){
                        var video = act.find('.timeline-body .post_picture_video .video-container iframe');
                        if(isEmpty(video)) {
                            video.attr('src', "https://www.youtube.com/embed/" + $video1);
                        }
                        else{
                            act.find('.timeline-body .post_picture_video').html('<div class="video-container"><iframe src="https://www.youtube.com/embed/'+$video1+'"  frameborder="0" allowfullscreen></iframe></div>');
                        }
                    }
                    else if($activity1.picture){
                        var img = act.find('.timeline-body .post_picture_video img');
                        if(isEmpty(img)) {
                            act.find(".timeline-body img").attr('src', $activity1.picture);
                        }
                        else{
                            act.find('.timeline-body .post_picture_video').html('<img src="'+$activity1.picture+'" alt="" class="img-responsive">');
                        }
                    }
                    act.find(".timeline-body img").attr('src',$activity1.picture);
                    act.find(".timeline-body .post_activity_msg").text($activity1.description);
                    act.find(".timeline-body .post_activity_img img").attr('src', '/images/icons/'+$sport1.icon);
                    act.find(".timeline-body .post_activity_img img").attr('alt', $sport1.name);
                    act.find(".timeline-body .post_activity_stats span:first").attr('data-text', $activity1.date_start);
                    act.find(".timeline-body .post_activity_stats span:first").html("<i aria-hidden='true' class='fa fa-calendar'></i>"+data['dateAct']);
                    act.find(".timeline-body .post_activity_stats span:last").attr('data-text', data['timeSec']);
                    act.find(".timeline-body .post_activity_stats span:last").html("Durée "+data['timeAct']);

                    $('#modal-activity').modal('hide');
                }
            }
        });
    });



    $("#datetimepicker1").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});
    $("#datetimepicker1-modal").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});
    
    
    $("#datetimepicker5").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});
    $("#datetimepicker5-modal").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});
    $("#datetimepicker6").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});
    $("#datetimepicker6-modal").datetimepicker({language: "fr",icons:{time:"fa fa-clock-o",date:"fa fa-calendar",up:"fa fa-arrow-up",down:"fa fa-arrow-down"}});

    $('#select-beast').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        },
        dropdownParent: 'body'
    });

    var load = false;
    $(window).scroll(function(){
        if(load == false){
            if(($(window).scrollTop() + 1) >= $(document).height()-$(window).height()){
                load = true;
                $class = 'timeline-inverted';
                $last = $('.timeline-2-cols li:last');
                $lastlast = $last.prev();
                if($lastlast.hasClass('timeline-inverted')){
                    $class = '';
                }
                var pageload = $('#loadAllPublication').val();
                $.ajax({
                    url: '/publication/loadAll',
                    data: {page: pageload, css: $class},
                    type: 'post',
                    success: function(data) {
                        if(data['success'] == true)
                        {
                            $publications = data['publications'];

                            $publications.forEach(function(a) {
                                $last = $('.timeline-2-cols li:last');
                                $last.before(a);
                            });
                            var p = +$('#loadAllPublication').val() +1;
                            $('#loadAllPublication').val(p);
                            load = false;
                        }
                    }
                });
            }
        }

    });

    $(document).on('keypress',"input#post-comment",function(e){
        if(e.keyCode == 13) {
            $value = this.value;
            $publication = this.name;
            $input = this;

            $.ajax({
                url: '/comment',
                data: {publication: $publication, value: $value},
                type: 'post',
                success: function (data) {
                    if (data['success'] == true) {
                        $user = data['user'];
                        $last = $('#comments-' + $publication + ' .comment:last');
                        $last.before(
                            "<div class='comment'>" +
                            "<a class='pull-left' href='#'>" +
                            "<img width='30' height='30' class='comment-avatar' alt='" + $user.firstname + " " + $user.lastname + "' src='" + $user.picture + "'>" +
                            "</a>" +
                            "<div class='comment-body'>" +
                            "<span class='message'><strong>" + $user.firstname + " " + $user.lastname + "</strong> " + data['message'] + "</span>" +
                            "<span class='time'>" + $user.created_at + "</span>" +
                            "</div>" +
                            "</div>");

                        $input.value = "";

                    }
                }
            });
        }
    });


    $(document).on('click',".moreComment",function(e){
        $elem = $(this);
        $page = $elem.attr("data-url");
        $parent = $elem.parent();
        $id = $parent.attr('id').split('-');
        $publication = $id[1];
        $.ajax({
            url: '/publication/'+$publication+'/loadComment',
            data: {page: $page},
            type: 'post',

            success: function(data) {
                if(data['success'] == true)
                {
                    $comments = data['comments'];
                    $comments.forEach(function(a){
                        $elem.before(a).fadeIn('slow');
                    });

                    if(data['page'] == false){
                        $elem.remove();
                    }else{
                        $elem.attr("data-url",data['page']);
                    }
                }
            }
        });

    });

    $('.dropdown.nav-notifications .dropdown-menu .nav-notifications-body a').one('mouseover', function(e){
        var id = $(this).attr('name');
        if(id){
            $.ajax({
             url: '/notifications/'+id+'/see',
             type: 'post',
             success: function(data) {}
             });
        }

    });

    $('#promouvoir').click(function(e){
        var id = $(this).data("text");
        var event = $(this);
        if(id){
            $.ajax({
                url: '/association/'+ id +'/promouvoir',
                type: 'post',
                success: function(data) {
                    if(data['success'] == true)
                    {
                        var destituer = event.prev();
                        event.css('display', 'none');
                        destituer.css('display', 'inline-block');
                    }
                }
            });
        }
    });

    $('#destituer').click(function(e){
        var id = $(this).data("text");
        var event = $(this);
        if(id){
            $.ajax({
                url: '/association/'+ id +'/destituer',
                type: 'post',
                success: function(data) {
                    if(data['success'] == true) {
                        var promouvoir = event.next();
                        event.css('display', 'none');
                        promouvoir.css('display', 'inline-block');
                    }
                }
            });
        }
    });

    $('#epromouvoir').click(function(e){
        var id = $(this).data("text");
        var event = $(this);
        if(id){
            $.ajax({
                url: '/event/'+ id +'/promouvoir',
                type: 'post',
                success: function(data) {
                    if(data['success'] == true)
                    {
                        var destituer = event.prev();
                        event.css('display', 'none');
                        destituer.css('display', 'inline-block');
                    }
                }
            });
        }
    });

    $('#edestituer').click(function(e){
        var id = $(this).data("text");
        var event = $(this);
        if(id){
            $.ajax({
                url: '/event/'+ id +'/destituer',
                type: 'post',
                success: function(data) {
                    if(data['success'] == true) {
                        var promouvoir = event.next();
                        event.css('display', 'none');
                        promouvoir.css('display', 'inline-block');
                    }
                }
            });
        }
    });

    $('body').on('click','#signalComment',function(e){
        var self = $(this);
        var parent = $(this).parents(".comment");
        var splitID = parent.attr("id").split("-");
        if(splitID){
            $.ajax({
                url: '/comment/'+ splitID[1] +'/signal',
                type: 'post',
                success: function(data) {
                    self.remove();
                    $('#modal-signal-comment').modal('show');
                }
            });
        }
    });

    $('body').on('click','#deleteComment',function(e){
        parent = $(this).parents(".comment");
        var splitID = parent.attr("id").split("-");
        if(splitID){
            $.ajax({
                url: '/comment/'+ splitID[1] +'/destroy',
                type: 'post',
                success: function(data) {
                    parent.remove();
                }
            });
        }
    });
});

function editpost(publication){
    var post = $('#publication-' + publication + ' .timeline-body .post_activity_msg');
    var video = $('#publication-' + publication + ' .timeline-body .video-container iframe');
    var src = video.attr('src');
    var texte = post.text().trim();
    if(src){
        texte += " " + src;
    }
    $("#user-post-modal-post").text(texte);
    $('#submit-modal-post').attr('action', 'publication/'+publication+'/updateAjax');
    $('#modal-post').modal('show');
}

function editact(activity){
    var act = $('#activite-' + activity);
    var post = act.find('.timeline-body .post_activity_msg');
    var video = act.find('.timeline-body .video-container iframe');
    var src = video.attr('src');
    var texte = post.text().trim();
    if(src){
        texte += " " + src;
    }
    $("#user-post-modal-act").text(texte);
    var sport = act.find('.post_activity_img img').attr('alt');
    $("#select-beast-act option").each(function(e){
        if($(this).text().trim() == sport){
            $(this).attr("selected", "selected");
        }
    });
    var time = act.find('.post_activity_stats span:last').data("text");
    $time = [];
    $temp = time % 3600;
    $time[0] = ( time - $temp ) / 3600 ;
    $time[2] = $temp % 60 ;
    $time[1] = ( $temp - $time[2] ) / 60;

    var date = act.find('.post_activity_stats span:first').data("text");
    date = date.replace(" ", "T");
    var date2 = new Date(date);
    var year = date2.getFullYear();
    var month = ("0" + (date2.getMonth() + 1)).slice(-2);
    var days = ("0" + date2.getDate()).slice(-2);
    var hours = date2.getHours();
    var minutes = date2.getMinutes();

    $("#time_h_act_modal").val($time[0]);
    $("#time_m_act_modal").val($time[1]);
    $("#time_s_act_modal").val($time[2]);
    $("#date_start_act_modal").val(days + "/" + month + "/" + year + " " + hours + ":" + minutes);
    $('#submit-modal-activity').attr('action', 'activity/'+activity+'/updateAjax');
    $('#modal-activity').modal('show');
}

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true && JSON.stringify(obj) === JSON.stringify({});
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

