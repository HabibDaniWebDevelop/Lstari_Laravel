<!-- Basic Layout -->
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Basic Layout</h5>
                <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body" id="form1">
                {{-- <form> --}}
                <div class="mb-3">
                    <label class="form-label" for="basic-default-fullname">Full Name</label>
                    <input type="text" class="form-control" id="basic-default-fullname" placeholder="John Doe"
                        data-index="1" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-default-company">Company</label>
                    <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc."
                        data-index="2" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-default-email">Email</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="basic-default-email" class="form-control" placeholder="john.doe"
                            data-index="3" aria-label="john.doe" aria-describedby="basic-default-email2" />
                        <span class="input-group-text" id="basic-default-email2">@example.com</span>
                    </div>
                    <div class="form-text">You can use letters, numbers & periods</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-default-phone">Phone No</label>
                    <input type="text" id="basic-default-phone" class="form-control phone-mask" data-index="4"
                        placeholder="658 799 8941" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-default-message">Message</label>
                    <textarea id="basic-default-message" class="form-control" data-index="5" placeholder="Hi, Do you have a moment to talk Joe?"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
                {{-- </form> --}}
            </div>
        </div>
    </div>
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Basic with Icons</h5>
                <small class="text-muted float-end">Merged input group</small>
            </div>
            <div class="card-body">
                {{-- <form> --}}
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="basic-icon-default-fullname"
                            placeholder="John Doe" data-index="6"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-company">Company</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-company2" class="input-group-text"><i
                                class="bx bx-buildings"></i></span>
                        <input type="text" id="basic-icon-default-company" class="form-control"
                            placeholder="ACME Inc." data-index="7" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-email">Email</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="text" id="basic-icon-default-email" class="form-control" placeholder="john.doe"
                            data-index="8" />
                        <span id="basic-icon-default-email2" class="input-group-text">@example.com</span>
                    </div>
                    <div class="form-text">You can use letters, numbers & periods</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-phone2" class="input-group-text"><i
                                class="bx bx-phone"></i></span>
                        <input type="text" id="basic-icon-default-phone" class="form-control phone-mask"
                            placeholder="658 799 8941" data-index="9" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-message">Message</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-message2" class="input-group-text"><i
                                class="bx bx-comment"></i></span>
                        <textarea id="basic-icon-default-message" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?"
                            data-index="10"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary">Send</button>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>



<script>
    $(document).on('keypress', 'input,textarea', function(e) {

        // pindah enter cara 0
        if (event.keyCode == 13) {
            event.preventDefault();
            var inputs = $('.form-control:visible:not(:disabled)');
            inputs.eq(inputs.index(this) + 1).focus();
        }

        // pindah enter cara 1
        // if (e.which == 13) {
        //     e.preventDefault();
        //     var posisi = parseFloat($(this).attr('data-index')) + 1;
        //     $('[data-index="' + posisi + '"]').focus();

        //     if(posisi == '11'){
        //         $('[data-index="1"]').focus();
        //     }
        // }
        // pindah enter cara 2
        // if (e.which == 13) {
        //     e.preventDefault();
        //     $(this).parent().next('div').find('input').focus();
        //     $(this).parent().parent().next('div').find('input').focus();
        //     $(this).parent().next('div').find('textarea').focus();
        //     $(this).parent().parent().next('div').find('textarea').focus();
        // }
    });
</script>
