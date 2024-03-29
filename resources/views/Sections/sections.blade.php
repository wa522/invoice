@extends('layouts.master')
@section('title')
	الاقسام
@stop
@section('css')
    <link href='{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}' rel='stylesheet'>
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
						<h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الاقسام</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
	<!-- row -->
	<div class="row">

		<!-- Errors -->
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<!-- Errors Closed -->
		<!-- Add -->
		@if(session()->has('Add'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>{{ session()->get('Add') }}</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<!-- Add Closed -->
		<!-- Edite -->
		@if(session()->has('Edite'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>{{ session()->get('Edite') }}</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<!-- Edite Closed -->
		<!-- Delete -->
		@if(session()->has('Delete'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>{{ session()->get('Delete') }}</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif
		<!-- Delete Closed -->

		<!-- div -->
		<div class="col-xl-12">
			<div class="card">
				<div class="card-header pb-0">
					<div class="d-flex justify-content-between">
						<!-- Button -->
						<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo2">اضافة قسم</a>
						<!-- Button Closed -->
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50'style="text-align: center">
							<thead>
								<tr>
									<th class="border-bottom-0">#</th>
									<th class="border-bottom-0">اسم القسم</th>
									<th class="border-bottom-0">الوصف</th>
									<th class="border-bottom-0">العمليات</th>
								</tr>
							</thead>
							<tbody>
							<?php $i=0 ?>
							@foreach( $select as $data )
								<?php $i++ ?>
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $data->section_name }}</td>
									<td>{{ $data->description }}</td>
									<td>
										<!-- Button Edite -->
										<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
										data-id="{{ $data->id }}" data-section_name="{{ $data->section_name }}"
										data-description="{{ $data->description }}" data-toggle="modal" href="#exampleModal2"
										title="تعديل"><i class="las la-pen"></i></a>
										<!-- Button Delete -->
										<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-id="{{ $data->id }}"
										data-section_name="{{$data->section_name}}" data-description="{{ $data->description }}"
										data-toggle="modal" href="#modaldemo9" title="حذف"><i class="las la-trash"></i></a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- div Closed -->

		{{-- Add Section --}}
		<div class="modal" id="modaldemo2">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">اضافة قسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<form action="{{ route('sections.store') }}" method="post"  autocomplete="off">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="exampleInputEmail1">اسم القسم</label>
								<input type="text" class="form-control" id="section_name" name="section_name">
							</div>
							<div class="form-group">
								<label for="exampleFormControlTextarea1">ملاحظات</label>
								<textarea class="form-control" id="description" name="description" rows="3"></textarea>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn ripple btn-primary">تاكيد</button>
								<button type="button" class="btn ripple btn-secondary" data-dismiss="modal">اغلاق</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		{{-- Add Section Closed --}}

		{{--Edite Section --}}
		<div class="modal" id="exampleModal2">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">تعديل القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<form action="sections/update" method="post"  autocomplete="off">
							{{method_field('patch')}}
							{{ csrf_field() }}
							<div class="form-group">
								<input type="hidden" name="id" id="id">
								<label for="exampleInputEmail1">اسم القسم</label>
								<input type="text" class="form-control" id="section_name" name="section_name">
							</div>
							<div class="form-group">
								<label for="exampleFormControlTextarea1">ملاحظات</label>
								<textarea class="form-control" id="description" name="description" rows="3"></textarea>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn ripple btn-primary">تاكيد</button>
								<button type="button" class="btn ripple btn-secondary" data-dismiss="modal">اغلاق</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		{{--Edite Section Closed --}}

		{{-- Delete Section --}}
		<div class="modal" id="modaldemo9">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<form action="sections/destroy" method="post">
						{{method_field('delete')}}
						{{csrf_field()}}
						<div class="modal-body">
							<p>هل انت متاكد من عملية الحذف ؟</p><br>
							<input type="hidden" name="id" id="id" value="">
							<input class="form-control" name="section_name" id="section_name" type="text" readonly>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
							<button type="submit" class="btn btn-danger">تاكيد</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		{{-- Delete Section Closed --}}

    </div>
    <!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

	{{-- Edite --}}
	<script>
		$('#exampleModal2').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var section_name = button.data('section_name')
			var description = button.data('description')
			var modal = $(this)
			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #section_name').val(section_name);
			modal.find('.modal-body #description').val(description);
		})
	</script>

	{{-- Delete --}}
	<script>
		$('#modaldemo9').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var section_name = button.data('section_name')
			var modal = $(this)
			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #section_name').val(section_name);
		})
	</script>
@endsection
