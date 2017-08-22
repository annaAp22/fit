<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    {!! Meta::render() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @include('blocks.favicons')
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/admin/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="/assets/admin/css/jquery-ui.custom.min.css" />
    <link rel="stylesheet" href="/assets/admin/css/datepicker.min.css" />
    <link rel="stylesheet" href="/assets/admin/css/daterangepicker.min.css" />
    <link rel="stylesheet" href="/assets/admin/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="/assets/admin/css/chosen.min.css" />



    <link rel="stylesheet" href="{{ elixir('assets/admin/css/admin.css') }}" />

    <link rel="stylesheet" href="/assets/admin/css/colorbox.min.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" type="text/css" href="/assets/admin/css/imgareaselect/imgareaselect-default.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="/assets/admin/fonts/fonts.googleapis.com.css" />

    <link rel="stylesheet" href="/assets/admin/css/select2.min.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/admin/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

    <link rel="stylesheet" href="/assets/admin/css/palette-color-picker.css">

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/admin/css/ace-part2.min.css" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/admin/css/ace-ie.min.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="/assets/admin/js/ace-extra.min.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="/admin/js/html5shiv.min.js"></script>
    <script src="/admin/js/respond.min.js"></script>

    <!--[if !IE]> -->
    <script src="/assets/admin/js/jquery.2.1.1.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="/assets/admin/js/jquery.1.11.1.min.js"></script>
    <![endif]-->

    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='/assets/admin/js/jquery.min.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='/assets/admin/js/jquery1x.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='/assets/admin/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="/assets/admin/js/bootstrap.min.js"></script>


    <script src="/assets/admin/js/jquery-ui.custom.min.js"></script>
    <script src="/assets/admin/js/jquery-ui.js"></script>

    <script src="/assets/admin/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/admin/js/markdown.min.js"></script>
    <script src="/assets/admin/js/bootstrap-markdown.min.js"></script>
    <script src="/assets/admin/js/jquery.hotkeys.min.js"></script>
    <script src="/assets/admin/js/bootbox.min.js"></script>
    <script src="/assets/admin/js/jquery.colorbox.min.js"></script>
    <script src="/assets/admin/js/jquery.maskedinput.min.js"></script>
    <script src="/assets/admin/js/select2.min.js"></script>
    <script src="/assets/admin/js/moment.min.js"></script>
    <script src="/assets/admin/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/assets/admin/js/daterangepicker.min.js"></script>
    <script src="/assets/admin/js/jquery.nestable.min.js"></script>
    <script src="/assets/admin/js/fuelux.spinner.min.js"></script>
    <script src="/assets/admin/js/jquery.imgareaselect.pack.js"></script>
    <script src="/assets/admin/js/chosen.jquery.min.js"></script>
    <script src="/assets/admin/js/jquery.inputlimiter.1.3.1.min.js"></script>


    <script src="/assets/admin/js/ckeditor/ckeditor.js"></script>

    <!-- ace scripts -->
    <script src="/assets/admin/js/ace-elements.min.js"></script>
    <script src="/assets/admin/js/ace.min.js"></script>

    <!-- color picker -->
    <script src="/assets/admin/js/palette-color-picker.min.js"></script>

    <script src="{{elixir('assets/admin/js/admin.js')}}"></script>

    <![endif]-->
</head>

<body class="no-skin">
@include('admin.navbar')

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    @include('admin.sidebar')

    <div class="main-content">
        <div class="main-content-inner" id="app">
            @yield('main')
        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        @include('admin.footer')
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

    {{--<script src="{{ elixir('js/admin/app.js') }}"></script>--}}
</body>
</html>
