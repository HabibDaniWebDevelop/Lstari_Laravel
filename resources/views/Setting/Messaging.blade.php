@extends('layouts.backend-Theme-3.app')

<?php $title = 'Masanging'; ?>

@section('css')
    <style>
        .direct-chat-messages {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            height: 50vh;
            overflow: auto;
            padding: 10px;
            scroll-behavior: smooth;
        }

        .direct-chat-msg,
        .direct-chat-text {
            display: block;
        }

        .direct-chat-msg {
            margin-bottom: 10px;
        }

        .direct-chat-msg::after {
            display: block;
            clear: both;
            content: "";
        }

        .direct-chat-messages,
        .direct-chat-contacts {
            transition: -webkit-transform .5s ease-in-out;
            transition: transform .5s ease-in-out;
            transition: transform .5s ease-in-out, -webkit-transform .5s ease-in-out;
        }

        .direct-chat-text {
            border-radius: 0.3rem;
            background-color: #d2d6de;
            border: 1px solid #d2d6de;
            color: #444;
            margin: 5px 0 0 50px;
            padding: 5px 10px;
            position: relative;
            width: auto;
            max-width: 350px;
        }

        .direct-chat-text::after,
        .direct-chat-text::before {
            border: solid transparent;
            border-right-color: #d2d6de;
            content: " ";
            height: 0;
            pointer-events: none;
            position: absolute;
            right: 100%;
            top: 15px;
        }

        .direct-chat-text::after {
            border-width: 5px;
            margin-top: -5px;
        }

        .direct-chat-text::before {
            border-width: 6px;
            margin-top: -6px;
        }

        .right .direct-chat-text {
            margin-left: 0;
            margin-right: 10px;
            text-align: right;
            color: white;
            background-color: #ac3939;
            /* width: 100px; */
            float: right;
        }

        .right .direct-chat-text::after,
        .right .direct-chat-text::before {
            border-left-color: #ac3939;
            border-right-color: transparent;
            left: 100%;
            right: auto;
        }

        .direct-chat-img {
            border-radius: 50%;
            float: left;
            height: 40px;
            width: 40px;
        }

        .direct-chat-name {
            font-weight: bold;
        }

        .right .direct-chat-img {
            float: right;
        }

        .right .direct-chat-infos {
            text-align: right;
        }
    </style>
@endsection


@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
    <div class="row mb-4">
        <div class="col-md-12">

            <div class="row" style="height: calc(100vh - 550px);">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-2 order-0 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Chat</h5>
                                {{-- <small class="text-muted">42.82k</small> --}}
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Mark all as Read</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">

                            <div class="table-responsive">
                                <table class="table table-borderless" id="sidebare1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <a href="messaging" class="app-brand-link gap-2">
                                                        <div class="avatar me-2">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-warning">SB</span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-0 text-truncate">Sarah Bullock</h6><small
                                                                class="text-truncate text-muted">I would love to. I
                                                                w</small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire2</h6><small
                                                            class="text-truncate text-muted">PHP Developer2</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire3</h6><small
                                                            class="text-truncate text-muted">PHP Developer3</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire4</h6><small
                                                            class="text-truncate text-muted">PHP Developer4</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire4</h6><small
                                                            class="text-truncate text-muted">PHP Developer4</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire4</h6><small
                                                            class="text-truncate text-muted">PHP Developer4</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AM</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-truncate">Adrian McGuire4</h6><small
                                                            class="text-truncate text-muted">PHP Developer4</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!--/ Order Statistics -->

                <!-- Transactions -->
                <div class="col-md-6 col-lg-8 col-xl-8 order-1 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Sarah Bullock</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 7 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">All Massage</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">


                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages" id="massage">
                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp">23 Jan 2:00 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user2.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Is this template
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp ">23 Jan 2:05 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        You better believe it!
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp">23 Jan 5:37 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user2.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Working with Lestari Laravel
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp ">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp">23 Jan 5:37 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user2.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Working with Lestari on a great new app! Wanna join?
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp ">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp">23 Jan 5:37 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user2.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Wanna join?
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp order-0">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to. I would love to.
                                        I would love to. I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->
                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp order-0">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to. I would love to.
                                        I would love to. I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->
                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos ">
                                        <span class="direct-chat-timestamp order-0">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="assets/images/user.jpg" alt="message user image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to. I would love to.
                                        I would love to. I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                            </div>
                            <!--/.direct-chat-messages-->


                            <!-- /.direct-chat-pane -->


                        </div>

                        <div class="card-footer">
                            <form action="#" method="post">

                                <div class="input-group">
                                    <input type="file" class="form-control" style="max-width: 80px;"
                                        id="inputGroupFile03" />
                                    <input type="text" class="form-control" autofocus
                                        placeholder="Type Message ..." />
                                    <button class="btn btn-primary" type="button" id="button-addon2">Send</button>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                <!--/ Transactions -->
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            document.getElementById('massage').scrollTop = document.getElementById('massage').scrollHeight
        });



        $(function() {

            $('#sidebare1').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
            });

        });
    </script>
@endsection
