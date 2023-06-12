<script>
    var port, textEncoder, writableStreamClosed, writer, historyIndex = -1, type_timbangan = "AND";
    const lineHistory = [];


    async function connectSerial() {
        try {
            const ports = await navigator.serial.getPorts();

            // Prompt user to select any serial port.
            port = await navigator.serial.requestPort();

            // if (!port) {
            //     port = ports[0];
            // }

            await port.open({
                baudRate: 9600
            });

            textEncoder = new TextEncoderStream();
            writableStreamClosed = textEncoder.readable.pipeTo(port.writable);
            writer = textEncoder.writable.getWriter();

            var conscale = document.getElementById("conscale");
            conscale.classList.add("disabled");
            conscale.innerHTML = 'Connected';

            console.log('connectSerial');

            await listenToPort();

        } catch (e) {
            alert(" Harap Close Halaman Lain yang Terkoneksi ke Timbangan \n Pesan " + e);
        }
    }

    async function sendSerialLine() {
        // dataToSend = "S";
        if(type_timbangan == "AND"){
            dataToSend = "S";
        }else{
            dataToSend = "O9";
        }
        lineHistory.unshift(dataToSend);
        historyIndex = -1; // No history entry selected
        dataToSend = dataToSend + "\n";

        console.log('sendSerialLine');
        await writer.write(dataToSend);

    }
    async function listenToPort() {
        console.log('listenToPort');
        const textDecoder = new TextDecoderStream();
        const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
        const reader = textDecoder.readable.getReader();

        // Listen to data coming from the serial device.
        while (true) {
            const {
                value,
                done
            } = await reader.read();
            if (done) {
                // Allow the serial port to be closed later.
                console.log('[readLoop] DONE', done);
                reader.releaseLock();
                break;
            }
            console.log(value);
            // value is a string.
            appendToTerminal(value);
        }
    }

    async function appendToTerminal(newStuff) {
        // //cek timbangan
        // if ((newStuff == "E01" || newStuff == "E" || newStuff == "01") && type_timbangan == "AND"){
        //     type_timbangan = "SHINKO";
        // }
        
        // // mettler
        // newStuff = newStuff.replace("S S       ", "");
        // newStuff = newStuff.replace("S       ", "");
        // newStuff = newStuff.replace(" g", "");
        // newStuff = newStuff.replace(" ", "");

        // // and 
        // newStuff = newStuff.replace("ST,+0000", "");
        // newStuff = newStuff.replace("ST,+000", "");
        // newStuff = newStuff.replace("ST,+00", "");
        // newStuff = newStuff.replace("ST,+0", "");
        // newStuff = newStuff.replace("T,+0000", "");
        // newStuff = newStuff.replace("T,+000", "");
        // newStuff = newStuff.replace("T,+00", "");
        // newStuff = newStuff.replace("T,+0", "");

        // // shinko
        // newStuff = newStuff.replace("+00000", "");
        // newStuff = newStuff.replace("+0000", "");
        // newStuff = newStuff.replace("+000", "");
        // newStuff = newStuff.replace("+00", "");
        // newStuff = newStuff.replace("+0", "");
        // newStuff = newStuff.replace("+", "");
        // newStuff = newStuff.replace("GHS", "");

        // // vibra
        // newStuff = newStuff.replace("0000", "");
        // newStuff = newStuff.replace("000", "");
        // newStuff = newStuff.replace("00", "");
        // if (newStuff.charAt(0) === '.') { // periksa apakah karakter pertama adalah titik
        //     newStuff = newStuff.replace(/^\./, '0.'); // ganti karakter pertama dari titik menjadi 0.
        // }
        // if (newStuff.endsWith('.')) { // periksa apakah karakter terakhir adalah titik
        //     newStuff += '00'; // tambahkan string "00" di belakangnya
        // }

        // let result = newStuff.includes("G S");
        // if (result) {
        //     newStuff = newStuff.replace("G S", "");
        //     var id = $('#selscale').val();
        //     $('#' + id).val(newStuff).change();
        //     $('#selscale').val('reset');
        // }

        // TEST OK
        // newStuff = newStuff.replace(/[^.\d]/g, ''); //almas
        newStuff = newStuff.replace(/[^\d.-]+/g, ''); //arik
        newStuff = Number(newStuff).toFixed(2);
        let weight = parseFloat(newStuff);
        // console.log(typeof newStuff);
        if (weight > 0){
            $('#weight_realtime').val(weight)
        }
        var id = $('#selscale').val();
        $('#' + id).val(newStuff).change();
        $('#selscale').val('reset');
    }

    // function kliktimbang(id) {
    //     // if (event.keyCode === 13) {
    //     sendSerialLine();
    //     $('#selscale').val(id);
    //     // }

    //     // console.log(id);
    // }

    async function kliktimbang(id_of_input_element){
        // get weight realtime 
        await sendSerialLine()
        let weight = await $('#weight_realtime').val()
        // set weight of input with this id
        $('#'+id_of_input_element).val(weight)
    }

    $(document).ready(function(){
        let timbangan_realtime = $('#weight_realtime')
        let input_timbangan_realtime = '<input type="hidden" id="weight_realtime">'
        if (timbangan_realtime.length == 0) {
            $('body').append(input_timbangan_realtime)
        } else {
            $('#weight_realtime').remove()
            $('body').append(input_timbangan_realtime)
        }
    })

</script>


