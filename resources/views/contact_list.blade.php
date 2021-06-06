@extends('admin.layouts.app')
@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-finance">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h3 class="mb-2">Contact </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Contacts</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="contact" class="table table-striped table-bordered first">
                                        <thead class="bg-light">
                                            <tr class="border-0">
                                                <th class="border-0">#</th>
                                                <th class="border-0">Name</th>
                                                <th class="border-0">Email</th>
                                                <th class="border-0">Mobile</th>
                                                <th class="border-0">Subject</th>
                                                <th class="border-0">Message</th>
                                                <th class="border-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contact as $index => $data)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $data->name }}</td>
                                                    <td>{{ $data->email }}</td>
                                                    <td>{{ $data->mobile }}</td>
                                                    <td>{{ $data->subject }}</td>
                                                    <td>{{ $data->message }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-dander btn-sm deletecontact" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete contact" data-id="{{ $data->id }}">
                                                            <span class="fas fa-trash-alt"></span>
                                                        </button>
                                                        {{-- <button type="button" class="btn btn-danger btn-sm deletecontact" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete contact" data-id="{{ $data->id }}">
                                                            <span class="fas fa-trash-alt"></span>
                                                        </button> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $(document).on("click", ".deletecontact", function(e)
            {
                e.preventDefault();
                Id = $(this).attr("data-id");
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax(
                        {
                            url:"{{url('contact-delete')}}",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id":Id
                            },
                            success: function(response)
                            {
                                if(response){
                                    if(response.success == "success"){
                                        swal("Trashed", "Your data has been moved to trash", "success");
                                        location.reload();
                                    }else{
                                        swal("Error", "Something went wrong try again later", "error");
                                    }
                                }else{
                                    swal("Error", "Something went wrong try again later", "error");
                                }
                            }
                        });
                    } else {
                        swal("Cancelled", "Your data is safe", "error");
                    }
                });
            });
        });
    </script>
@endsection
