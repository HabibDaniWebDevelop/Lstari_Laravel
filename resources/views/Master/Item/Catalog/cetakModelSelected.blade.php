<!doctype html>
{{-- Source https://codepen.io/mamundev/pen/rNBRmVL --}}
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Cetak Selected Products</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.min.css') !!}">

    <!-- Style CSS -->
    <style>
        *,
        *:before,
        *:after {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        }
        :focus {
            outline: 0px;
        }
        .quiz_title{
            font-size: 30px;
            font-weight: 700;
            color: #292d3f;
            text-align: center;
            margin-bottom: 50px;
        }

        .quiz_card_area{position: relative;margin-bottom: 30px;}
        .single_quiz_card{
            border:1px solid #efefef;
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s linear;
            -o-transition: all 0.3s linear;
            -ms-transition: all 0.3s linear;
            -khtml-transition: all 0.3s linear;
            transition: all 0.3s linear;
        }
        .quiz_card_title{
            padding: 10px;
            text-align: center;
            background-color: #d6d6d6;
        }
        .quiz_card_title h3{
            font-size: 10px;
            font-weight: 400;
            color: #292d3f;
            margin-bottom: 0;
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s linear;
            -o-transition: all 0.3s linear;
            -ms-transition: all 0.3s linear;
            -khtml-transition: all 0.3s linear;
            transition: all 0.3s linear;
        }
        .quiz_card_title h3 i{opacity: 0;}
        .quiz_card_icon{
            max-width: 100%;
            min-height: 135px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quiz_icon {
            width: 70px;
            height: 75px;
            position: relative;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: auto;
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s linear;
            -o-transition: all 0.3s linear;
            -ms-transition: all 0.3s linear;
            -khtml-transition: all 0.3s linear;
            transition: all 0.3s linear;
        }
        .quiz_checkbox {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            z-index: 999;
            cursor: pointer;
        }  
        .quiz_checkbox:checked ~ .single_quiz_card{border: 1px solid #2575fc;}
        .quiz_checkbox:checked:hover ~ .single_quiz_card{border: 1px solid #2575fc;}

        .quiz_checkbox:checked ~ .single_quiz_card .quiz_card_content .quiz_card_title{background-color:#2575fc; color: #ffffff;}
        .quiz_checkbox:checked ~ .single_quiz_card .quiz_card_content .quiz_card_title h3{color: #ffffff;}
        .quiz_checkbox:checked ~ .single_quiz_card .quiz_card_content .quiz_card_title h3 i{opacity: 1;}
        .quiz_checkbox:checked:hover ~ .quiz_card_title{border: 1px solid #2575fc;}

        /*Icon Selector*/

        .quiz_card_icon{
            font-size: 50px;
            color: #000000;
        }

        .quiz_backBtn_progressBar{
            position: relative;
            margin-bottom: 60px;
        }
        .quiz_backBtn{
            background-color: transparent;
            border: 1px solid #d2d2d3;
            color: #8e8e8e;
            border-radius: 50%;
            position: absolute;
            top: -17px;
            left: 0px;
            width: 40px;
            height: 40px;
            text-align: center;
            vertical-align: middle;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none!important;
        }
        .quiz_backBtn:hover{color: #a9559b;border: 1px solid #2575fc;}
        .quiz_backBtn_progressBar .progress{margin-left: 50px;margin-top: 50px;height: 6px;}
        .quiz_backBtn_progressBar .progress-bar{
            background-color: #2575fc;
        }
        .quiz_next{
            text-align: center;
            margin-top: 50px;
        }
        .quiz_continueBtn{
            max-width: 315px;
            background-color: #2575fc;
            color: #ffffff;
            font-size: 18px;
            border-radius: 20px;
            padding: 10px 125px;
            border: 0;
        }
    </style>
    
  </head>
  <body>
    
    <div class="allWrapper">
        <header class="header" id="header">

        </header><!-- end of header -->

        <section class="quiz_section" id="quizeSection">
            <div class="container">
                <div class="row">
                    <form action="/Master/Item/Katalog/model/cetakselectedmodel" method="post">
                    @csrf
                    <div class="col-sm-12">
                        <div class="quiz_content_area">

                            <h1 class="quiz_title">Pilih Item yang Akan Di Cetak</h1>
                            <div class="row">
                                @foreach ($data_return['data'] as $item)
                                <div class="col-sm-3">
                                    <div class="quiz_card_area">
                                        <input class="quiz_checkbox" type="checkbox" value="{{$item->ID}}" name="product[]" checked="checked" />
                                        <div class="single_quiz_card">
                                            <div class="quiz_card_content">
                                                <div class="quiz_card_icon">
                                                    <img src="{{Session::get('hostfoto')}}/image2/{{$item->Photo}}.jpg" style="object-fit: cover; width: 100px; height: 100px;" class="card-img-top" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                </div><!-- end of quiz_card_media -->
        
                                                <div class="quiz_card_title">
                                                    <h3><i class="fa fa-check" aria-hidden="true"></i> {{$item->SW}}</h3>
                                                </div><!-- end of quiz_card_title -->
                                            </div><!-- end of quiz_card_content -->
                                        </div><!-- end of single_quiz_card -->
                                    </div><!-- end of quiz_card_area -->
                                </div><!-- end of col3  -->
                                @endforeach
                                <div class="col-sm-12">
                                    <div class="quiz_next">
                                        <button class="quiz_continueBtn">Cetak</button>
                                    </div><!-- end of quiz_next -->
                                </div><!-- end of col12 -->
                            </div><!-- end of quiz_card_area -->
                        </div><!-- end of quiz_content_area -->
                    </div><!-- end of col12 -->
                    </form>
                </div><!-- end of row -->
            </div><!-- end of container -->
        </section><!-- end of quiz_section -->
    </div><!-- end of allWrapper -->

    <br><br><br><br>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    {{-- Bootstrap --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.bundle.min.js') !!}"></script>
  </body>
</html>