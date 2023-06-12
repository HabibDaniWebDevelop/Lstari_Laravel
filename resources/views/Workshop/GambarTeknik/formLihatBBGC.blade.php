<table class="table table-borderless table-sm text-center" id="tabelForm" style="vertical-align: top">
    <thead class="table-secondary">
        <tr>
            <th width="5%">#</th>
            <th width="15%">Product</th>
            <th width="15%">Tipe Matras</th>
            <th width="12%">File Autocad</th>
            <th width="12%">Foto Gambar Teknik</th>
            <th>Bahan Baku </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($gambarTeknik->GambarTeknik as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>
                @foreach ($item->Items as $itemGambarTeknik)
                <select name="product" disabled id="product_{{$loop->parent->iteration}}" class="form-select product">
                    @foreach ($BBGCProduct as $itemProduct)
                    <option value="{{$itemProduct->ID}}" @if ($itemGambarTeknik->IDWIPWorkshop == $itemProduct->ID) selected @endif>{{$itemProduct->SWProduct}}</option>
                    @endforeach
                </select>
                @endforeach
            </td>
            <td>
                <select name="tipeMatras" disabled id="tipeMatras_{{$loop->iteration}}" class="form-select tipeMatras">
                    <option value="Samir" @if ($item->Matras->TipeMatras == "Samir") selected @endif>Samir</option>
                    <option value="Roll" @if ($item->Matras->TipeMatras == "Roll") selected @endif>Roll</option>
                </select>
            </td>
            <td><input type="file" disabled class="form-control fileAutocad" name="fileAutocad_{{$loop->iteration}}" id="fileAutocad_{{$loop->iteration}}"></td>
            <td><input type="file" disabled class="form-control fotoGambarTeknik" name="fotoGambarTeknik_{{$loop->iteration}}" id="fotoGambarTeknik_{{$loop->iteration}}"><input type="hidden" class="form-control fotoGambarTeknik64" name="fotoGambarTeknik64_{{$loop->iteration}}" id="fotoGambarTeknik64_{{$loop->iteration}}"></td>
            <td>
                <table class="table table-borderless table-sm text-center" disabled id="tabelBahanBaku_{{$loop->iteration}}">
                    <thead class="table-secondary">
                        <tr>
                            <th>Nama Bahan</th>
                            <th width="20%">Qty</th>
                            <th width="20%">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->Matras->Materials as $itemMatrasMaterial)
                        <tr class="rowBahanBaku rowBahanBaku_{{$loop->parent->iteration}}" id="rowBahanBaku_{{$loop->parent->iteration}}_{{$loop->iteration}}">
                            <td>
                                <select disabled name="namaBahan_{{$loop->parent->iteration}}_{{$loop->iteration}}" id="namaBahan_{{$loop->parent->iteration}}_{{$loop->iteration}}" class="form-select namaBahan">
                                    @foreach ($rawMaterial as $itemMaterial)
                                        <option value="{{$itemMaterial->ID}}" @if ($itemMatrasMaterial->IDRawMaterialWorkshop == $itemMaterial->ID) selected @endif>{{$itemMaterial->Name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" max="10" disabled value="{{$itemMatrasMaterial->Qty}}" name="qtyBahan_{{$loop->parent->iteration}}_{{$loop->iteration}}" id="qtyBahan_{{$loop->parent->iteration}}_{{$loop->iteration}}" class="form-control qtyBahan"></td>
                            <td>
                                <button disabled onclick="newRow({{$loop->parent->iteration}})" class="btn btn-primary btn_add_row">+</button> <button disabled onclick="removeRow2({{$loop->parent->iteration}},{{$loop->iteration}})" class="btn btn-primary btn_remove">-</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    function newRow(indexMatras) {
        // Count row inside table bahan baku
        let jumlahRow = $('#tabelBahanBaku_'+indexMatras+' tbody tr').length+1;
        
        // Create New Row
        let rowOpen = '<tr class="rowBahanBaku rowBahanBaku_'+indexMatras+'" id="rowBahanBaku_'+indexMatras+'_'+jumlahRow+'">'
        let rowClose = '</tr>'
        let selectElement = '<td><select name="namaBahan_'+indexMatras+'_'+jumlahRow+'" id="namaBahan_'+indexMatras+'_'+jumlahRow+'" class="namaBahan form-select"></select></td>'
        let qtyElement = '<td><input type="number" min="1" max="10" name="qtyBahan_'+indexMatras+'_'+jumlahRow+'" id="qtyBahan_'+indexMatras+'_'+jumlahRow+'" class=" qtyBahan form-control"></td>'
        let buttonElement = '<td><button onclick="newRow('+indexMatras+')" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2('+indexMatras+','+jumlahRow+')" class="btn btn-primary btn_remove">-</button></td>'
        let rowElement = ""
        rowElement = rowElement.concat(rowOpen, selectElement, qtyElement, buttonElement, rowClose)

        // add rowElement to bahanBaku
        $('#tabelBahanBaku_'+indexMatras+' > tbody').append(rowElement)

        // Get rawMaterial from localstorage
        let rawMaterial = JSON.parse(localStorage.rawMaterial)
        // insert rawMaterial to selectElement
        $.each(rawMaterial, function(key, value) {   
            $('#namaBahan_'+indexMatras+'_'+jumlahRow)
                .append($("<option></option>")
                .attr("value", value.ID)
                .text(value.Name)); 
        });
        return
    }

    function removeRow2(index, nomorUrut) {
        // check if removeAction allowed
        let removeAction = $('#removeAction').val()
        if (removeAction === 'false'){
            return false;
        }

        // get row length
        let number = $('#tabelBahanBaku_'+index+' tbody tr').length;
        if (number !== 1){
            // If row length is more than 1 it will remove last row
            $("#rowBahanBaku_"+index+"_"+nomorUrut).remove()
            // loop row
            $("#tabelBahanBaku_"+index+" tbody tr").each((i, elem) => {
                newIndex = i+1
                // Set new row index
                elem.id = "rowBahanBaku_"+index+"_"+newIndex
                
                // Set namaBahan
                $(elem).find('.namaBahan').attr('name','namaBahan_'+index+'_'+newIndex)
                $(elem).find('.namaBahan').attr('id','namaBahan_'+index+'_'+newIndex)

                // Set qtyBahan
                $(elem).find('.qtyBahan').attr('name','qtyBahan_'+index+'_'+newIndex)
                $(elem).find('.qtyBahan').attr('id','qtyBahan_'+index+'_'+newIndex)
                
                // Set new btn_remove properties
                $(elem).find('.btn_remove').attr('onclick',"removeRow2("+index+","+newIndex+")")
            })
        } else{
            // If row length is less than 1 or 0 it will show alert because that row is last item. it can't be deleted.
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "This is last item in this transaction. You can't Delete it.",
            })
            return;
        }
        
    }

    async function saveGambarTeknik() {
        $(".preloader").show();
        let jenisMatras = $('#jenisMatras').val()
        let ukuranMatras = $('#ukuranMatras').val()
        let jumlahMatras = $('#jumlahMatras').val()
        let jumlahItemMatras = $('#jumlahItemMatras').val()
        let pakaiPisau = $('#pakaiPisau').val()

        // Build all data to become Form Data
        let form = new FormData()
        form.append("jenisMatras",jenisMatras)
        form.append("ukuranMatras",ukuranMatras)
        form.append("jumlahMatras",jumlahMatras)
        form.append("jumlahItemMatras",jumlahItemMatras)
        form.append("pakaiPisau",pakaiPisau)

        for (let index = 0; index < jumlahMatras; index++) {
            let indexMatras = index+1
            let produkMatras = $('#product_'+indexMatras).val()
            let tipeMatras = $('#tipeMatras_'+indexMatras).val()
            
            // Get GambarTeknik
            if ($('#fotoGambarTeknik_'+indexMatras).get(0).files.length === 0) {
                $(".preloader").fadeOut(); 
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Foto Gambar Teknik "+indexMatras+" Belum Terpilih",
                })
                return;
            }
            let extension = $('#fotoGambarTeknik_'+indexMatras).val().slice(($('#fotoGambarTeknik_'+indexMatras).val().lastIndexOf(".") - 1 >>> 0) + 2);
            let fotoGambarTeknik = $('#fotoGambarTeknik_'+indexMatras).prop('files')[0]
            var filereader = new FileReader();
            filereader.readAsDataURL(fotoGambarTeknik);
            filereader.onload = function (evt) {
                var base64 = evt.target.result;
                $('#fotoGambarTeknik64_'+indexMatras).val(base64);
            }
            await new Promise(r => setTimeout(r, 2000));
            let fotoGambarTeknik64 = $('#fotoGambarTeknik64_'+indexMatras).val()
            // Check File Autocad
            if ($('#fileAutocad_'+indexMatras).get(0).files.length === 0) {
                $(".preloader").fadeOut(); 
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "File Matras "+indexMatras+" Belum Terpilih",
                })
                return;
            }else{
                // Check with File
                let fileAutocad = $('#fileAutocad_'+indexMatras).prop('files')[0]
                form.append("fileAutocad[]",fileAutocad)
            }
            let rawMaterial = []

            // get jumlahBahanBaku
            let jumlahBahanBaku = $('#tabelBahanBaku_'+indexMatras+' tbody tr').length
            for (let indexMaterial = 0; indexMaterial < jumlahBahanBaku; indexMaterial++) {
                let indexBahan = indexMaterial+1
                let idMaterial = $('#namaBahan_'+indexMatras+'_'+indexBahan).val()
                let jumlahMaterial = $('#qtyBahan_'+indexMatras+'_'+indexBahan).val()
                let material = {
                    idMaterial:idMaterial,
                    jumlahMaterial:jumlahMaterial
                }
                rawMaterial.push(material)
            }
            
            // Build Data 
            let dataMatras = {
                indexMatras:indexMatras,
                produkMatras:produkMatras,
                tipeMatras:tipeMatras,
                rawMaterial:rawMaterial,
                fotoGambarTeknik:fotoGambarTeknik64,
                extensionFoto:extension
            }
            // Convert object to String
            dataMatras = JSON.stringify(dataMatras)
            // Convert JSON String to base64
            dataMatras = btoa(dataMatras)
            form.append("matras[]",dataMatras)
        }

        // hit server
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/Workshop/GambarTeknik",
            data:form,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                // Set Hidden Input
                $('#idGambarTeknik').val(data.data.id)
                $('#action').val('ubah')

                // Set button
                $("#btn_baru").prop('disabled',false)
                $("#btn_ubah").prop('disabled',false)
                $("#btn_posting").prop('disabled',false)
                $("#btn_simpan").prop('disabled',true)
                $("#btn_batal").prop('disabled',true)
                $("#btn_cetak").prop('disabled',true)

                // Disable Input
                $('.product').prop('disabled',true)
                $('.tipeMatras').prop('disabled',true)
                $('.fileAutocad').prop('disabled',true)
                $('.namaBahan').prop('disabled',true)
                $('.qtyBahan').prop('disabled',true)
                $('.btn_add_row').prop('disabled',true)
                $('.btn_remove').prop('disabled',true)
                $(".preloader").fadeOut(); 
                return;
            },
            error: function(xhr){
                $(".preloader").fadeOut(); 
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
                return;
            }
        })
    }

    async function UpdateGambarTeknik() {
        $(".preloader").show();  
        let idGambarTeknik = $('#idGambarTeknik').val()
        let jenisMatras = $('#jenisMatras').val()
        let jumlahMatras = $('#jumlahMatras').val()

        // Build all data to become Form Data
        let formData = new FormData()
        formData.append("idGambarTeknik",idGambarTeknik)
        formData.append("jenisMatras",jenisMatras)
        formData.append("jumlahMatras",jumlahMatras)
        
        for (let index = 0; index < jumlahMatras; index++) {
            let indexMatras = index+1
            let produkMatras = $('#product_'+indexMatras).val()
            let tipeMatras = $('#tipeMatras_'+indexMatras).val()
            // Get GambarTeknik
            if ($('#fotoGambarTeknik_'+indexMatras).get(0).files.length === 0) { 
                $('#fotoGambarTeknik64_'+indexMatras).val("null");
                var extension = null;
            } else {
                var extension = $('#fotoGambarTeknik_'+indexMatras).val().slice(($('#fotoGambarTeknik_'+indexMatras).val().lastIndexOf(".") - 1 >>> 0) + 2);
                let fotoGambarTeknik = $('#fotoGambarTeknik_'+indexMatras).prop('files')[0]
                var filereader = new FileReader();
                filereader.readAsDataURL(fotoGambarTeknik);
                filereader.onload = function (evt) {
                    var base64 = evt.target.result;
                    $('#fotoGambarTeknik64_'+indexMatras).val(base64);
                }
                await new Promise(r => setTimeout(r, 2000));
            }
            let fotoGambarTeknik64 = $('#fotoGambarTeknik64_'+indexMatras).val()
            
            let rawMaterial = []

            // get jumlahBahanBaku
            let jumlahBahanBaku = $('#tabelBahanBaku_'+indexMatras+' tbody tr').length
            for (let indexMaterial = 0; indexMaterial < jumlahBahanBaku; indexMaterial++) {
                let indexBahan = indexMaterial+1
                let idMaterial = $('#namaBahan_'+indexMatras+'_'+indexBahan).val()
                let jumlahMaterial = $('#qtyBahan_'+indexMatras+'_'+indexBahan).val()
                let material = {
                    idMaterial:idMaterial,
                    jumlahMaterial:jumlahMaterial
                }
                rawMaterial.push(material)
            }
            
            // Build Data 
            let dataMatras = {
                indexMatras:indexMatras,
                produkMatras:produkMatras,
                tipeMatras:tipeMatras,
                rawMaterial:rawMaterial,
                fotoGambarTeknik:fotoGambarTeknik64 == 'null' ? null : fotoGambarTeknik64,
                extensionFoto:extension
            }
            // Convert object to String
            dataMatras = JSON.stringify(dataMatras)
            // Convert JSON String to base64
            dataMatras = btoa(dataMatras)
            formData.append("matras[]",dataMatras)
        }
        // for (let [key, value] of formData.entries()) {
        //     console.log(`${key}: ${value}`);
        // }
        // return

        // hit server
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/Workshop/GambarTeknik/update",
            data:formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                // Set button
                $("#btn_baru").prop('disabled',false)
                $("#btn_ubah").prop('disabled',false)
                $("#btn_posting").prop('disabled',false)
                $("#btn_simpan").prop('disabled',true)
                $("#btn_batal").prop('disabled',true)
                $("#btn_cetak").prop('disabled',true)

                // Disable Input
                $('.product').prop('disabled',true)
                $('.tipeMatras').prop('disabled',true)
                $('.fileAutocad').prop('disabled',true)
                $('.namaBahan').prop('disabled',true)
                $('.qtyBahan').prop('disabled',true)
                $('.btn_add_row').prop('disabled',true)
                $('.btn_remove').prop('disabled',true)
                $(".preloader").fadeOut();
                return;
            },
            error: function(xhr){
                $(".preloader").fadeOut();
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
                return;
            }
        })
    }
</script>