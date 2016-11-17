@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">英檢畢業門檻</h1>
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
									<td>語言測驗名稱</td>
									<td>等級或分數</td>
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
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('graduate_threshold')}}" method="post">
                        	{{ csrf_field() }}
							@include("../layouts/select")
							<div class="form-group">
								<label for="testName">語言測驗名稱</label>
								<input type="text" name="testName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="testGrade">等級或分數</label>
								<input type="text" name="testGrade" class="form-control">
							</div>
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea type="text" class="form-control" name="comments"></textarea>
							</div>

							<button class="btn btn-success">新增</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="search" style="margin-top: 10px;">
						<form action="{{url('graduate_threshold/search')}}">
							{{ csrf_field() }}
							@include("../layouts/select_search")
							<div class="form-group">
								<label for="testName">語言測驗名稱</label>
								<input type="text" name="testName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="testGrade">等級或分數</label>
								<input type="text" name="testGrade" class="form-control">
							</div>
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea type="text" class="form-control" name="comments"></textarea>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('graduate_threshold/upload')}}">
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