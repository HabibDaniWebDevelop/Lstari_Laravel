@if (is_null($detailDesign->IDM))
    <h1>Detail Preview Spesifikasi 3D Belum Tersedia.</h1>
@else
    <div class="row text-center">
        <div class="col-6">
            Male-A <br>
            <a href="{{Session::get('hostfoto')}}/rnd2/Workshop/Dimensi Design/{{$detailDesign->ImageMALE_A}}" data-lightbox="imagePreview">
                <img src="{{Session::get('hostfoto')}}/rnd/Workshop/Dimensi Design/{{$detailDesign->ImageMALE_A}}" width="50%" height="50%" alt="">
            </a>
        </div>
        <div class="col-6">
            Male-B <br>
            <a href="{{Session::get('hostfoto')}}/rnd2/Workshop/Dimensi Design/{{$detailDesign->ImageMALE_B}}" data-lightbox="imagePreview">
                <img src="{{Session::get('hostfoto')}}/rnd/Workshop/Dimensi Design/{{$detailDesign->ImageMALE_B}}" width="50%" height="50%" alt="">
            </a>
        </div>
        <div class="col-6">
            Female-A <br>
            <a href="{{Session::get('hostfoto')}}/rnd2/Workshop/Dimensi Design/{{$detailDesign->ImageFEMALE_A}}" data-lightbox="imagePreview">
                <img src="{{Session::get('hostfoto')}}/rnd/Workshop/Dimensi Design/{{$detailDesign->ImageFEMALE_A}}" width="50%" height="50%" alt="">
            </a>
        </div>
        <div class="col-6">
            Female-B <br>
            <a href="{{Session::get('hostfoto')}}/rnd2/Workshop/Dimensi Design/{{$detailDesign->ImageFEMALE_B}}" data-lightbox="imagePreview">
                <img src="{{Session::get('hostfoto')}}/rnd/Workshop/Dimensi Design/{{$detailDesign->ImageFEMALE_B}}" width="50%" height="50%" alt="">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <h5>Spesifikasi Male</h5>
            <table class="table">
                <tr>
                    <td width="30%">Tebal Bahan</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalBahanMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tebal Penyenter Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalPenyenterProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lebar Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LebarProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lis Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LisProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tebal Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalRingStopperMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tinggi Penyenter Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TinggiPenyenterProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterRingStopperMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Radius Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->RadiusProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lebar Spee</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LebarSpeeMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tinggi Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TinggiProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tinggi Spee</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TinggiSpeeMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Profile Luar</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterProfileLuarMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Penyenter Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterPenyenterProfileMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->RingStopperMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Lubang Dalam</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterLubangDalamMALE}}</td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <h5>Spesifikasi Female</h5>
            <table class="table">
                <tr>
                    <td width="30%">Tebal Bahan</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalBahanFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tebal Penyenter Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalPenyenterProfileFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lebar Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LebarProfileFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lis Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LisProfileFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tebal Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TebalRingStopperFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tinggi Penyenter Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TinggiPenyenterProfileFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterRingStopperFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Radius Profile</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->RadiusProfileFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Lebar Spee</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->LebarSpeeFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Tinggi Spee</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->TinggiSpeeFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Profile Dalam 1</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterProfileDalamFEMALE_1}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Profile Dalam 2</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterProfileDalamFEMALE_2}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Profile Luar</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterProfileLuarFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Ring Stopper</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->RingStopperFEMALE}}</td>
                </tr>
                <tr>
                    <td width="30%">Diameter Lubang Dalam</td>
                    <td width="1%">:</td>
                    <td width="1%">{{$detailDesign->DiameterLubangDalamFEMALE}}</td>
                </tr>
            </table>
        </div>
    </div>
@endif