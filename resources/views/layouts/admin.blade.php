<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Athleteec | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="OZ5EaazJ8k-vftAyTNRIeoewQVsBYfDNSkqTnlYwt-Y" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{ asset('asset/css/social.core.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/social.admin.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/font-awesome/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_free/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.halflings.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/glyphicons_pro/glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/jquery-ui/social/jquery.ui.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/themes/admin/facebook.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/jqvmap/jqvmap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/pnotify/pnotify.custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/themes/admin/facebook.css') }}" rel="stylesheet">

    @yield('css')
    <link href="{{ asset('asset/css/front.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/admin.css') }}" rel="stylesheet">

    <style>
        .wrapper .main {
            margin-top: 40px;
        }
        @media screen and (max-width: 480px) {
            .wrapper .main {
                margin-top: 80px;
            }
        }
    </style>

    <![endif]-->
</head>
<body>
<div class="wrapper">
    <aside class="social-sidebar">
        <div class="social-sidebar-content">
            <div class="user">
                    <img width="25" height="25" src="{{ Auth::user()->picture }}" alt="{{ Auth::user()->firstname.' '.Auth::user()->lastname }}" class="avatar">
                    <span>{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</span>
                    <i data-toggle="dropdown" class="trigger-user-settings fa fa-user"></i>
            </div>
            <div class="menu">
                <div class="menu-content">
                    <ul id="social-sidebar-menu">
                        <li>
                            <a href="/admin">
                                <!-- icon--><i class="fa fa-home"></i>
                                <span>Accueil admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.index') }}">
                                <!-- icon--><i class="fa fa-users"></i>
                                <span>Utilisateurs</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.publication.index') }}">
                                <!-- icon--><i class="fa fa-list-alt"></i>
                                <span>Publications</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.sport.index') }}">
                                <!-- icon--><i class="fa fa-star"></i>
                                <span>Sports</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.newsletter.index') }}">
                                <!-- icon--><i class="fa fa-envelope-o"></i>
                                <span>Newsletter</span>
                            </a>
                        </li>
                        <li>
                            <a href="/">
                                <!-- icon--><i class="fa fa-sign-in"></i>
                                <span>Passer sur le front</span>
                            </a>
                        </li>
    </aside>
    <!-- END SIDEBAR-->
    <header>
        <!-- BEGIN NAVBAR-->
        <nav role="navigation" class="navbar navbar-fixed-top navbar-super social-navbar">
            <div class="navbar-header">
                <a href="{{ url('/') }}" title="Social" class="navbar-brand">
                    <img class ="logo-front" width="25" height="25" src="{{ asset('asset/img/logo.svg') }}" alt="Social">
                </a>
            </div>
            <!-- /.navbar-collapse-->
        </nav>
        <!-- END NAVBAR-->
    </header>
    <div class="main">
        @yield('content')
    </div>
    <footer class="blanc">2016 - <a href="/" target="_blank" class="blanc">ATHLETEEC</a> / <a href="{{ route('front.obligatoire.confidentialite')}}" class="blanc">Confidentialité</a> / <a href="{{ route('front.obligatoire.mentionslegales')}}" class="blanc">Mentions Légales</a></footer>
</div>
<!-- Modal-->
<div id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
                <h4 id="myModalLabel" class="modal-title">Modal Settings</h4>
            </div>
            <div class="modal-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, deserunt!</div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- END THEME SWITCHER-->
<!-- jQuery-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    window.jQuery || document.write('<script src="../../assets/js/jquery/jquery.min.js"><\/script>')
</script>
<!-- Bootstrap JS-->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script>
    $.fn.modal || document.write('<script src="../../assets/js/plugins/bootstrap/bootstrap.min.js"><\/script>')
    // Prevent jQueryUI Conflicts
    var bootstrapTooltip = $.fn.tooltip.noConflict()
    $.fn.bootstrapTooltip = bootstrapTooltip
</script>
<!-- jQueryUI-->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
    window.jQuery.ui || document.write('<script src="../../assets/js/jquery-ui/jquery-ui.min.js"><\/script>')
</script>

@yield('js')

</body>
</html>
