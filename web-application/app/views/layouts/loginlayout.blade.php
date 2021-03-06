<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Ding Datt</title>
        <link rel="shortcut icon" href="{{ URL::to('assets/inner/img/dingdatt_favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ URL::to('assets/inner/img/dingdatt_favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="{{ URL::to('assets/css/demo.css') }}" />
        <script src="{{ URL::to('assets/lib/jquery-1.11.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/js/jquery.sublimeSlideshow.js') }}"></script>
        <script type="text/javascript">
        $(function () {
            $.sublime_slideshow({
                src: [
                    {url: "{{ URL::to('assets/images/1.jpg') }}"},
                    {url: "{{ URL::to('assets/images/2.jpg') }}"},
                    //{url:"images/3.jpg"},
                    {url: "{{ URL::to('assets/images/4.jpg') }}"},
                ],
                duration: 6,
                fade: 3,
                scaling: false,
                rotating: false,
                overlay: "{{ URL::to('assets/images/pattern.png') }}"
            });
        });
        </script>
        <script type="text/javascript">
            function goback()
            {
                window.history.back();
            }

        </script>

        <script type="text/javascript" >

            $(document).ready(function () {

                if ($('#sessionlanguage').val() == '')
                    changelanguage('value_en');
                else
                    changelanguage($('#sessionlanguage').val());


                $("#languageid").change(function (e) {
                    var languagename = $(this).val();
                    e.preventDefault();
                    changelanguage('value_' + languagename);

                });
            });

            function changelanguage(languagename)
            {
                var page_name = $('#pagename').val();
                var dataString = 'languagename=' + languagename + '&page_name=' + page_name;
                $.ajax({
                    type: "POST",
                    url: "changeLanguage",
                    data: dataString,
                    success: function (data) {
                        console.log(languagename);
                        for (var i = 0; i < data.length; i++)
                        {
                            console.log(data[i][languagename]);
                            $('#sessionlanguage').val(languagename);
                            if (data[i]['ctrlCaptionId'] == 'txt_submit')
                            {
                                $('#' + data[i]['ctrlCaptionId']).val(data[i][languagename]);
                                $('.' + data[i]['ctrlCaptionId']).val(data[i][languagename]);
                            }
                            else
                            {
                                $('#' + data[i]['ctrlCaptionId']).attr("placeholder", data[i][languagename]);
                                $('.' + data[i]['ctrlCaptionId']).attr("placeholder", data[i][languagename]);
                                $('#' + data[i]['ctrlCaptionId']).html(data[i][languagename]);
                                $('.' + data[i]['ctrlCaptionId']).html(data[i][languagename]);
                            }
                        }
                    }
                });
            }

        </script>
        <!-- Load jQuery from Google's CDN -->
        <!-- Load jQuery UI CSS  -->
        <link rel="stylesheet" href="{{ URL::to('assets/css/jquery-ui.css') }}" />

        <!-- Load jQuery JS -->
        <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
        <!-- Load jQuery UI Main JS  -->
        <script src="{{ URL::to('assets/js/jquery-ui.js') }}"></script>

        <script type="text/javascript">
             /*  jQuery ready function. Specify a function to execute when the DOM is fully loaded.  */
             $(document).ready(
                     /* This is the function that will get executed after the DOM is fully loaded */
                             function () {
                                 $("#datepicker").datepicker({
                                     changeMonth: true, //this option for allowing user to select month
                                     changeYear: true, //this option for allowing user to select from year range
                                     dateFormat: 'yy-mm-dd'
                                 });
                             }

                     );
        </script>

        <link rel="stylesheet" href="{{ URL::to('assets/css/style_white.css') }}" type="text/css" />
    </head>
    <body>
        <input type="hidden" id="sessionlanguage" value="<?php echo Session::get('language'); ?>">
        <?php
        $languageDetails = languagenameModel::lists('language_name', 'language_key');
        ?>
        @yield('body')
        <div class="fscn_footer fleft">
            <ul>
                <li><a href="#"><span id="txt_menu_about">About</span></a></li>
                <li><a href="#"><span id="txt_menu_support">Support</span></a></li>
                <li><a href="#"><span id="txt_menu_terms">Terms</span></a></li>
                <li><a href="#"><span id="txt_menu_privacy">Privacy</span></a></li>
            </ul>
        </div>
        <div class="dev_footer fright">
            <span id="txt_menu_developedby">Developed by</span> <a href="http://bizarresoftware.in">BIZARRE Software Solutions</a>
        </div>
    </div>
</body>

</html>
