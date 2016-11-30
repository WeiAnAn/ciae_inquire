@extends('../layouts/master')

@section('content')

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">赴國外出席國際會議</h1>
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
									<td>姓名</td>
									<td>身分</td>
									<td>前往國家</td>
									<td>會議名稱</td>
									<td>開始時間</td>
									<td>結束時間</td>
									<td>備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach ($conf as $data)
								<tr>
									<td>{{$data->college}}</td>
									<td>{{$data->dept}}</td>
									<td>{{$data->name}}</td>
									<td>@if ($data->stuLevel==1)
									博士生
									@elseif ($data->stuLevel==2)
									碩士生
									@else
									學士生
									@endif</td>
									<td>{{$data->nation}}</td>
									<td>{{$data->confName}}</td>
									<td>{{$data->startDate}}</td>
									<td>{{$data->endDate}}</td>
									<td>{{$data->comments}}</td>
									<td>{{$data->dept}}</td>								
								</tr>
								@endforeach
							</tbody>
						</table>
					{{ $conf->links() }}	
					</div>
					
					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('stu_attend_conf')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')
							<div class="form-group">
								<label for="">姓名</label>
								<input type="text" class="form-control" name="name" />
							</div>
							<div class="form-group">
								<label for="sutLevel">身分</label>
								<select name="sutLevel" id="sutLevel" class="form-control">
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>
							<div class="form-group">
								<label for="nation">前往國家</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="confName">會議名稱</label>
								<textarea name="confName" id="confName" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group col-md-6" style="padding-left:0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="date" name="endDate" class="form-control">
							</div>
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control"></textarea>
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
						<form action="{{url('stu_attend_conf/search')}}">
							{{ csrf_field() }}
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="">姓名</label>
								<input type="text" class="form-control" name="name" />
							</div>
							<div class="form-group">
								<label for="sutLevel">身分</label>
								<select name="sutLevel" id="sutLevel" class="form-control">
									<option value=""></option>
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>
							<div class="form-group">
								<label for="nation">前往國家</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="confName">會議名稱</label>
								<textarea name="confName" id="confName" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group" style="margin-bottom: 0px">
								<label >日期</label>
							</div>
							<div class="form-group col-md-6" style="padding-left:0">
								<label for="startDate">從</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-right: 0">
								<label for="endDate">到</label>
								<input type="date" name="endDate" class="form-control">
							</div>
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('stu_attend_conf/upload')}}">
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