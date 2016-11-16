@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">全外語授課之課程</h1>
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
									<td>學年</td>
									<td>學期</td>
									<td>中文名稱</td>
									<td>英文名稱</td>
									<td>教師</td>
									<td>授課語言</td>
									<td>修課總人數</td>
									<td>國際生人數</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
									<td>圖形識別</td>
									<td>Pattern Recognition</td>
									<td>林維暘</td>
									<td>英語</td>
									<td>123</td>
									<td>123</td>
									<td>123</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('graduate_threshold')}}" method="post">
							<div class="form-group">
								<label for="">語言測驗名稱</label>
								<textarea type="text" class="form-control"></textarea>
							</div>
							<button class="btn btn-success">新增</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="search" style="margin-top: 10px;">
						<form action="{{url('graduate_threshold/search')}}">
							<div class="form-group">
								<label for="">搜尋</label>
								<input type="text" class="form-control">
							</div>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('graduate_threshold/search')}}">
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