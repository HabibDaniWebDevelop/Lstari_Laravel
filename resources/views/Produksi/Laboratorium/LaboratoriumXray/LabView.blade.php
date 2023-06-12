<div class="row">
    <div class="col-4">
        <p>No. NTHKO Lebur : {{$WorkAllocation}}</p>
        <p>Date : {{$date}}</p>
        <p>Operator : {{$operator}}</p>
    </div>
    <div class="col-4">
        <p>Block : {{$block}}</p>
        <p>Measuring Time : {{$measuringTime}}</p>
    </div>
</div>

@if (isset($laboratorium))
<h3>Hasil Test Laboratorium</h3>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NTHKO Lebur</th>
                        <th>Kadar</th>
                        <th>Batch No</th>
                        <th colspan="9">Berat</th>
                        <th colspan="2">Kadar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Awal</td>
                        <td>Timah</td>
                        <td>Perak</td>
                        <td>Emas</td>
                        <td>Tembaga</td>
                        <td>Hasil oven</td>
                        <td>Hasil Nitric</td>
                        <td>Sisa</td>
                        <td>Total</td>
                        <td>Emas</td>
                        <td>Perak</td>
                        <td></td>
                    </tr>
                    @foreach ($laboratorium as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->WorkAllocation}}-{{$item->Freq}}</td>
                        <td>{{$item->Kadar}}</td>
                        <td>{{$item->BatchNo}}</td>
                        <td>{{$item->WeightMelting}}</td>
                        <td>{{$item->WeightLead}}</td>
                        <td>{{$item->WeightSilver}}</td>
                        <td>{{$item->WeightGold}}</td>
                        <td>{{$item->WeightCopper}}</td>
                        <td>{{$item->WeightOven}}</td>
                        <td>{{$item->WeightNitric}}</td>
                        <td>{{$item->WeightLeft}}</td>
                        <td>{{$item->WeightTotal}}</td>
                        <td>{{$item->ContentGold}}</td>
                        <td>{{$item->ContentSilver}}</td>
                        <td>{{$item->Remarks}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
@endif

{{-- Lab Test Item --}}
<h3>Hasil Test X-Ray</h3>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        @foreach ($datalabtestitem as $key => $item)
                        <th>{{$key}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop Sebanyak Berapa baris data yang ada di test item. --}}
                    @for ($i = 0; $i < count(array_values($datalabtestitem)[0]); $i++)
                        <tr>
                            {{-- Loop Test Item Variable --}}
                            @foreach ($datalabtestitem as $key => $item)
                                <td>
                                    {{-- Get Test Data Item By its Variable and index --}}
                                    {{$datalabtestitem[$key][$i]}}
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>

{{-- Lab Result Item --}}
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        @foreach (array_values($datalabresultitem)[0] as $key => $value)
                        <th>{{$key}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datalabresultitem as $key => $value)
                    <tr>
                        <td>{{$key}}</td>
                        @foreach ($value as $item)
                            <td>{{$item}}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>