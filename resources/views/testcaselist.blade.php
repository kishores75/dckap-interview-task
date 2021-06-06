@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top:60px">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Module
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#moduleModal" style="float: inline-end"> Add </button>
                    </div>
                    <div class="card-body">
                        <div class="well" id="treeview_div">
                            <ul>
                                @foreach($modules as $index => $data)
                                    <li id="{{ $data->id }}">{{ $data->name }}
                                        <ul>
                                            @foreach($submodules as $index => $submodData)
                                                @if($submodData->parent_id == $data->id)
                                                    <li id="{{ $submodData->id }}">{{ $submodData->name }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testcaseModal" style="float: inline-end"> Add Test Case </button><br><br>
                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Summary</th>
                            <th>Description</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- Module Modal -->
        <div class="modal fade" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="moduleForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="moduleModalLabel">Module/Sub Module</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                {{-- Parent default value is 0 and name is Root.  Which means it's helps to create module. --}}
                                <label for="module">Select Module</label>
                                <select class="form-control" id="module" name="module">
                                    <option value="" disabled selected="selected">-- select --</option>
                                    <option value="0">Root</option>
                                    @foreach($modules as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="module-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Please enter name">
                                <span class="text-danger" id="name-error"></span>
                            </div>
                        </div>
                        <div id="moduleModalFormStatus"></div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="moduleButton">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Test Case Modal -->
        <div class="modal fade bd-example-modal-lg" id="testcaseModal" tabindex="-1" role="dialog" aria-labelledby="testcaseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="testCaseForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="testcaseModalLabel">Test Case</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="module_id">Select Module</label>
                                <select class="form-control" id="module_id" name="module_id">
                                    <option value="" disabled selected="selected">-- select --</option>
                                    @foreach($modules as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="id-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="summary">Summary</label>
                                <textarea class="form-control" id="summary" name="summary" rows="3"></textarea>
                                <span class="text-danger" id="summary-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description">
                            </div>
                            <div class="form-group">
                                <label for="file">File</label>
                                <input type="file" class="form-control-file" id="file" name="file">
                            </div>
                        </div>
                        <div id="testcaseModalFormStatus"></div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="testCaseButton">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="testcaseDeleteStatus"></div>
    </div>
@endsection
@section('script')
    <script>
		$(document).ready(function(){
			$('#moduleForm').on('submit',function(event){
				event.preventDefault();
                $("#moduleModalFormStatus").html('');
				$('#moduleButton').html('Please wait...');
				$('#module-error').text('');
				$('#name-error').text('');
				$.ajax({
					url: "{{url('module-add')}}",
					type:"POST",
					data: $('#moduleForm').serialize(),
					success:function(response){
						if (response.success) {
						    $("#moduleButton").html('Save');
                            $("#moduleModalFormStatus").html('<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>Success! </strong>' + response.success + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
							window.setTimeout(function(){window.location.href = "{{url('/')}}";},2000);
							// $("#moduleForm")[0].reset();
							// window.setTimeout(function(){ $("#moduleModalFormStatus").html(''); $('#moduleModal').modal('hide') },2000);
							// window.setTimeout(function(){window.location.href = "{{url('employee-list')}}";},2000);
						}else{
						    $("#moduleButton").html('Save');
                            $("#moduleModalFormStatus").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> <strong>Error! </strong>' + response.failure + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
						}
					},
					error: function(response) {
						$("#moduleButton").html('Save');
						$('#module-error').text(response.responseJSON.errors.module);
						$('#name-error').text(response.responseJSON.errors.name);
					}
				});
			});
			$('#testCaseForm').on('submit',function(event){
				event.preventDefault();
                $("#testcaseModalFormStatus").html('');
				$('#testCaseButton').html('Please wait...');
				$('#id-error').text('');
				$('#summary-error').text('');
				$.ajax({
					url: "{{url('testcase-add')}}",
					type:"POST",
					data: new FormData(this),contentType: false,cache: false,processData:false,
					success:function(response){
						if (response.success) {
						    $("#testCaseButton").html('Save');
                            $("#testcaseModalFormStatus").html('<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>Success! </strong>' + response.success + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
							window.setTimeout(function(){window.location.href = "{{url('/')}}";},2000);
							// $("#testCaseForm")[0].reset();
							// window.setTimeout(function(){ $("#testcaseModalFormStatus").html(''); $('#testCaseModal').modal('hide') },2000);
							// window.setTimeout(function(){window.location.href = "{{url('employee-list')}}";},2000);
						}else{
						    $("#testCaseButton").html('Save');
                            $("#testcaseModalFormStatus").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> <strong>Error! </strong>' + response.failure + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
						}
					},
					error: function(response) {
						$("#testCaseButton").html('Save');
						$('#id-error').text(response.responseJSON.errors.module_id);
						$('#summary-error').text(response.responseJSON.errors.summary);
					}
				});
			});
            moduleStatus();
            $("#treeview_div").on(
                "select_node.jstree", function(evt, data){
                    var id = data.node.id;
                    moduleStatus(id);
                }
            );
            function moduleStatus(id=null){
                var val = id;
                var table = $('#datatable').dataTable( {
                    "Paginate": true, "processing": true, "pageLength": 10, "serverSide": true,
                    "rowCallback": function (nRow, aData, iDisplayIndex) { var oSettings = this.fnSettings (); $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1); return nRow; },
                    "bDestroy": true,
                    "columns": [
                        { data: 'id' },
                        { data: 'summary' },
                        { data: 'description' },
                        { data: 'file' },
					    { data: "action_delete" }
                    ],
                    "ajax": { "url": "{{ url('testcase-getTestcases') }}", "data": function ( d ) { d.custom = val; } }
                });
            }
			$(document).on("click", ".deletetestcase", function(e)
			{
                e.preventDefault();
				$('#testcaseDeleteStatus').html('');
                Id = $(this).attr("data-id");
                if (confirm("Are you sure?")) {
                    $.ajax(
                    {
                        url:"{{url('testcase-delete')}}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id":Id
                        },
                        success: function(response)
                        {
                            if(response){
                                if(response.success == "success"){
                                    $("#testcaseDeleteStatus").html('<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>Success! </strong>' + response.success + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
							        window.setTimeout(function(){window.location.href = "{{url('/')}}";},2000);
                                }else{
                                    $("#testcaseDeleteStatus").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> <strong>Error! </strong>' + response.failure + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
                                }
                            }else{
                                $("#testcaseDeleteStatus").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"> <strong>Error! </strong>' + response.failure + '.<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>');
                            }
                        }
                    });
                }
                return false;
            });
		});
    </script>
@endsection