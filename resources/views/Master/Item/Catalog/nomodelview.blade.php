<div class="tab-pane fade active show" id="gambar" role="tabpanel">
    <div class="row d-flex justify-content-center">
        <div class="col-xxl-3 col-xl-3 col-md-12 col-sm-12 col-xs-12">
            {{-- Items --}}
            <div class="list-group" id="listGroupModel">
                <div class="table-responsive">
                    @foreach ($product as $item)
                    <button type="button" onclick="DetailProductModel({{$loop->iteration}},'{{$item->SW}}')" class="list-group-item list-group-item-action" id="productItemModel_{{$loop->iteration}}">{{$item->SW}}</button>
                    @endforeach
                </div>
            </div>
            {{-- End Item --}}
        </div>
        <div class="col-xxl-9 col-xl-9 col-md-12 col-sm-12 col-xs-12 mt-2" id="detailProductModel">
            <h1>Please Select Item</h1>
        </div>
    </div>
</div>