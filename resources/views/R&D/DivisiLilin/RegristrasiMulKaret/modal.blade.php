<div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6 dropdown">
                        <h5> LEMARI</h5>
                        <select class="from-select btn btn-primary center col-12 px-auto" onchange="ClickLaci()"
                            id="lemari" name="lemari">
                            <option value="" selected disabled>--- Pilih ---</option>
                            @foreach ($lemari as $lemaris)
                            <option class="text-center bg-light text-dark border-none" value="{{ $lemaris->ID }}">
                                {{ $lemaris->SW }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 dropdown pb-2">
                        <h5> LACI</h5>
                        <select class="btn btn-primary col-12 mx-auto" onchange="ClickLaci()" id="laci" name="laci">
                            <option value="" selected disabled>--- Pilih ---</option>
                            @foreach ($laci as $lacis)
                            <option class="text-center bg-light text-dark border-0" value="{{ $lacis->ID }}">
                                {{ $lacis->SW }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="TabelLaci" class="text-center">

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>