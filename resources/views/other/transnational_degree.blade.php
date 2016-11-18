@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">跨國學位</h1>
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
									<td>國家</td>
									<td>中文校名</td>
									<td>英文校名</td>
									<td>學士</td>
									<td>碩士</td>
									<td>博士</td>
									<td>授課方式</td>
									<td>學位授予方式</td>
									<td>備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>圖形識別</td>
									<td>Pattern Recognition</td>
									<td>林維暘</td>
									<td>英語</td>
									<td>123</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('transnational_degree')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')
							<div class="form-group">
								<label for="nation">國家</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="">中文校名</label>
								<input type="text" class="form-control" name="chtName" />
							</div>
							<div class="form-group">
								<label for="">英文校名</label>
								<input type="text" class="form-control" name="engName" />
							</div>
							<div class="form-group">
								<label for="profLevel">授予身分</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="bachelor" class="checkbox-inline">學士
								<input type="checkbox" name="master" class="checkbox-inline">碩士
								<input type="checkbox" name="PHD" class="checkbox-inline">博士
							</div>
							<div class="form-group">
								<label for="teachMode">授課方式</label>
								<textarea name="teachMode" id="teachMode" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="degreeMode">學位授予方式</label>
								<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control"></textarea>
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
						<form action="{{url('transnational_degree/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="nation">國家</label>
								<input type="text" name="nation" class="form-control">
							</div>
							<div class="form-group">
								<label for="">中文校名</label>
								<input type="text" class="form-control" name="chtName" />
							</div>
							<div class="form-group">
								<label for="">英文校名</label>
								<input type="text" class="form-control" name="engName" />
							</div>
							<div class="form-group">
								<label for="profLevel">授予身分</label>
							</div>
							<div class="form-group">
								<label for="bachelor">學士</label>
								<input type="radio" name="bachelor" value="1" class="radio-inline">有授予
								<input type="radio" name="bachelor" value="0" class="radio-inline">無授予
							</div>
							<div class="form-group">
								<label for="bachelor">碩士</label>
								<input type="radio" name="master" value="1" class="radio-inline">有授予
								<input type="radio" name="master" value="0" class="radio-inline">無授予
							</div>
							<div class="form-group">
								<label for="PHD">博士</label>
								<input type="radio" name="PHD" value="1" class="radio-inline">有授予
								<input type="radio" name="PHD" value="0" class="radio-inline">無授予
							</div>
							<div class="form-group">
								<label for="teachMode">授課方式</label>
								<textarea name="teachMode" id="teachMode" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="degreeMode">學位授予方式</label>
								<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control"></textarea>
							</div>
							
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control"></textarea>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('transnational_degree/upload')}}">
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