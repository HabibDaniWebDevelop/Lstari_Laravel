<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/css/core2.css"
        class="template-customizer-core-css') !!}" />
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/css/theme-default2.css"
        class="template-customizer-theme-css') !!}" />
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/css/demo.css') !!}" />

</head>

<body>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron my-4">
                    <h1>Right click menu for Bootstrap 4 - Advanced Components</h1>
                    <p class="lead">by djibe.</p>
                    <p>Just right click in a table cell to test.</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="klik" id="2">
                                <th scope="row">1</th>
                                <td id="21">Mark</td>
                                <td id="22">Otto</td>
                                <td id="23">@mdo</td>
                            </tr>
                            <tr class="klik" id="3">
                                <th scope="row">2</th>
                                <td id="31">Jacob</td>
                                <td id="32">Thornton</td>
                                <td id="33">@fat</td>
                            </tr>
                            <tr class="klik" id="4">
                                <th scope="row">3</th>
                                <td id="41">Larry</td>
                                <td id="42">the Bird</td>
                                <td id="43">@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="menuklik" style="display:none">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>

                </div>
            </div>
            <div class="col-12">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita officia iure ipsum itaque, odit
                pariatur veniam quae quas. Qui soluta ipsa quaerat dignissimos totam architecto unde reprehenderit
                obcaecati quia quos.
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Commodi odit optio adipisci enim, architecto
                neque, vero numquam consequatur similique deserunt veritatis nesciunt qui tempore molestias recusandae
                repudiandae accusamus sit quaerat.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum perspiciatis, ex voluptatibus
                quaerat quasi quas a? Quos unde aut accusantium nobis eveniet. Earum quod labore aliquam necessitatibus
                iste, adipisci laborum.
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Et, nam illo perferendis neque reprehenderit
                minus officiis, voluptate at, ut porro accusantium pariatur in. Nesciunt sed nobis nulla quae,
                perspiciatis quas?

            </div>
        </div>
    </div>

</body>

</html>


<script src="{!! asset('assets/sneatV1/libs/jquery/jquery-3.6.1.min.js') !!}"></script>

<script>
    //contextmenu untuk klik kanan, click untuk klik kiri
    $(".klik").on('click', function(e) {
        $('.klik').css('background-color', 'white');

        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        } else {
            var top = e.pageY - 10;
            var left = e.pageX - 120;
            var id = $(this).attr('id');
            // alert('tes'+ id);

            $(this).css('background-color', '#f4f5f7');
            $("#menuklik").css({
                display: "block",
                top: top,
                left: left
            });
        }
        return false; //blocks default Webbrowser right click menu

    });

    $("body").on("click", function() {
        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        }
        $('.klik').css('background-color', 'white');
    });

    $("#menuklik a").on("click", function() {
        $(this).parent().hide();
    });
</script>