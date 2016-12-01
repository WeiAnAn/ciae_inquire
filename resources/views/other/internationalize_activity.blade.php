@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">國際化活動</h1>
	</div>
</div>
<div class="row">	
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
			<ul class="nav nav-tabs">
                <li class="active"><a href="#show" data-toggle="tab">檢視</a>
                </li>
                <li><a href="#insert" data-toggle="tab">新增</a>
                </li>
                <li><a href="#search" data-toggle="tab">進階搜尋</a>
                </li>
                <li><a href="#upload" data-toggle="tab">批次上傳</a>
                </li>
            </ul>
				<div class="tab-content">
					<div class="tab-pane fade in active " id="show" style="margin-top: 10px">
						<table width="100%" class="table table-striped table-bordered table-hover">
							<thead>	
								<tr>
									<td>單位</td>
									<td>系所部門</td>
									<td>活動性質</td>
									<td>地點</td>
									<td>主辦</td>
									<td>外賓</td>
									<td>開始時間</td>
									<td>結束時間</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach ($internationalactivity as $data)
								<tr>
									<td>{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td>{{$data->activityName}}</td>
									<td>{{$data->place}}</td>
									<td>{{$data->host}}</td>
									<td>{{$data->guest}}</td>
									<td>{{$data->startDate}}</td>
									<td>{{$data->endDate}}</td>
									<td>
										@can('permission',$data)
										<a href="{{url('internationalize_activity',$data->id)}}"
											class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form 
											action="{{url('internationalize_activity',$data->id)}}"
											method="post" style="display: inline;">
											{{ method_field('DELETE') }}
                        					{{ csrf_field() }}
											<button class="glyphicon glyphicon-trash
												btn btn-danger btn-xs" 
												onclick="clickDel(event)"></button>
										</form>
										@endcan
									</td>

								</tr>
								@endforeach
							</tbody>
						</table>
					
					{{ $internationalactivity->links() }}	
					</div>


					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('internationalize_activity')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')
							<div class="form-group">
								<label for="activityName">活動性質</label>
								<input type="text" class="form-control" name="activityName" />
							</div>
							<div class="form-group">
								<label for="place">地點</label>
								<input type="text" class="form-control" name="place" />
							</div>
							<div class="form-group">
								<label for="host">主辦</label>
								<textarea name="host" id="host" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="guest">外賓</label>
								<textarea name="guest" id="guest" cols="30" rows="3" class="form-control"></textarea>
							</div>
							
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="date" name="endDate" class="form-control">
							</div>
							<button class="btn btn-success">新增</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="search" style="margin-top: 10px;">
						<div class="alert alert-success alert-dismissible" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				            <strong>不加入搜尋條件之選項留空即可</strong>
				        </div>
						<form action="{{url('internationalize_activity/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="activityName">活動性質</label>
								<input type="text" class="form-control" name="activityName" />
							</div>
							<div class="form-group">
								<label for="place">地點</label>
								<input type="text" class="form-control" name="place" />
							</div>
							<div class="form-group">
								<label for="host">主辦</label>
								<textarea name="host" id="host" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="guest">外賓</label>
								<textarea name="guest" id="guest" cols="30" rows="3" class="form-control"></textarea>
							</div>
							
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="date" name="endDate" class="form-control">
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('internationalize_activity/upload')}}">
							<input type="file" class="" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px">上傳</button>								
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection