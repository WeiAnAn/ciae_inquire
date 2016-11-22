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
								@foreach($foreignLanguageClass as $data)
								<tr>
									<td>{{$data->college}}</td>
									<td>{{$data->dept}}</td>
									<td>{{$data->year}}</td>
									<td>{{$data->semester}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>{{$data->teacher}}</td>
									<td>{{$data->language}}</td>
									<td>{{$data->totalCount}}</td>
									<td>{{$data->nationalCount}}</td>
									<td>123</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						{{$foreignLanguageClass->links()}}
					</div>

					<div class="tab-pane fade in col-md-12" id="insert" style="margin-top: 10px">
						<form action="{{url('/foreign_language_class')}}" method="post">
                        	{{ csrf_field() }}
							@include('../layouts/select')
							<div class="form-group">
								<label for="">學年</label>
								<input type="number" name="year" class="form-control" value="105">
							</div>
							<div class="form-group">
								<label for="semester">學期</label>
								<select name="semester" id="semester" class="form-control">
									<option value="1">上學期</option>
									<option value="2">下學期</option>
								</select>
							</div>
							<div class="form-group">
								<label for="">課程中文名稱</label>
								<input type="text"  name="chtName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="engName">課程英文名稱</label>
								<input type="text" name="teacher" class="form-control">
							</div>
							<div class="form-group">
								<label for="teacher">授課教師</label>
								<input type="text" name="teacher" class="form-control">
							</div>
							<div class="form-group">
								<label for="totalCount">總人數</label>
								<input type="number" name="totalCount" class="form-control" value="0">
							</div>
							<div class="form-group">
								<label for="nationalCount">外籍生人數</label>
								<input type="number" name="nationalCount" class="form-control" value="0">
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
						<form action="{{url('foreign_language_class/search')}}" method="get">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="">學年</label>
								<input type="number" name="year" class="form-control">
							</div>
							<div class="form-group">
								<label for="semester">學期</label>
								<select name="semester" id="semester" class="form-control">
									<option ></option>
									<option value="1">上學期</option>
									<option value="2">下學期</option>
								</select>
							</div>
							<div class="form-group">
								<label for="">課程中文名稱</label>
								<input type="text"  name="chtName" class="form-control"></input>
							</div>
							<div class="form-group">
								<label for="engName">課程英文名稱</label>
								<input type="text" name="teacher" class="form-control">
							</div>
							<div class="form-group">
								<label for="teacher">授課教師</label>
								<input type="text" name="teacher" class="form-control">
							</div>
							<div class="form-group">
								<label for="totalCount">總人數</label>
								<input type="number" name="totalCount" class="form-control">
							</div>
							<div class="form-group">
								<label for="nationalCount">外籍生人數</label>
								<input type="number" name="nationalCount" class="form-control">
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

					<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
						<form action="{{url('foreign_language_class/upload')}}">
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