@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">姊妹校締約情形</h1>
	</div>
</div>
<div class="row">	
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
			<ul class="nav nav-tabs">
                @if(count($errors)>0)
	                <li><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li class="active"><a href="#insert" data-toggle="tab">新增</a>
	                </li>
				@else
	                <li class="active"><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li><a href="#insert" data-toggle="tab">新增</a>
	                </li>
	            @endif    
                <li><a href="#search" data-toggle="tab">進階搜尋</a>
                </li>
                <li><a href="#upload" data-toggle="tab">批次上傳</a>
                </li>
            </ul>
            	
				<div class="tab-content">
					@if(count($errors)>0)
						<div class="tab-pane fade in table-responsive" id="show" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in active table-responsive" id="show" 
							style="margin-top: 10px">
					@endif
						@if(session('success'))
				        <div class="alert alert-success alert-dismissible" role="alert">
				            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				            <strong> {{ session('success') }}</strong>
				        </div>
			        	@endif
						<table width="100%" class="table table-striped table-bordered table-hover">
							<thead>	
								<tr>
									<td id="partner_school.college" onclick="sort(id)">簽約機構所屬一級單位</td>
									<td id="partner_school.dept" onclick="sort(id)">簽約系所</td>
									<td id="nation" onclick="sort(id)">姊妹校所屬國家</td>
									<td id="chtName" onclick="sort(id)">中文校名</td>
									<td id="engName" onclick="sort(id)">英文校名</td>
									<td id="startDate" onclick="sort(id)">簽約時間</td>
									<td id="endDate" onclick="sort(id)">到期時間</td>
									<td id="comments" onclick="sort(id)">備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach ($partner as $data)
								<tr>
									<td>{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td>{{$data->nation}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>{{$data->startDate}}</td>
									<td>{{$data->endDate}}</td>
									<td>{{$data->comments}}</td>
									<td>
										@can('permission',$data)
										<a href="{{url('partner_school',$data->id)}}"
											class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form 
											action="{{url('partner_school',$data->id)}}"
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
					
					{{ $partner->links() }}
					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('partner_school')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="nation">姊妹校所屬國家</label>
								<input type="text" name="nation" class="form-control" value="{{old('nation')}}">
							</div>

							@if($errors->has('chtName'))
                                <p class="text-danger">{{$errors->first('chtName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">中文校名</label>
								<input type="text" class="form-control" name="chtName" value="{{old('chtName')}}">
							</div>

							@if($errors->has('engName'))
                                <p class="text-danger">{{$errors->first('engName')}}</p>
                            @endif
							<div class="form-group">
								<label for="">英文校名</label>
								<input type="text" class="form-control" name="engName" value="{{old('engName')}}">
							</div>
							
							@if($errors->has('startDate')||$errors->has('endDate'))
                                <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>                      
                                <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                            @endif
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">簽約時間</label>
								<input type="date" name="startDate" class="form-control" value="{{old('startDate')}}">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">到期時間</label>
								<input type="date" name="endDate" class="form-control" value="{{old('endDate')}}">
							</div>

							@if($errors->has('comments'))
                                <p class="text-danger">{{$errors->first('comments')}}</p>
                            @endif
							<div class="form-group">
								<label for="comments">備註</label>
								<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{old('comments')}}</textarea>
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
						<form action="{{url('partner_school/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="nation">姊妹校所屬國家</label>
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
							
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">簽約時間</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">到期時間</label>
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
						<form action="{{url('partner_school/upload')}}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
                        	<div id="file_error"></div>
                        	@if(count($errors->upload)>0)
                        		@if($errors->upload->has('format'))
                        			<p class="text-danger">
										{{$errors->upload->first('format')}}
                        			</p>
                        		@elseif($errors->upload->has('permission'))
                        			<p class="text-danger">
                        				{{$errors->upload->first('permission')}}
                        			</p>
                        		@elseif($errors->upload->has('number'))
                        			<p class="text-danger">
                        				{{$errors->upload->first('number')}}
                        			</p>
                        		@else
                        			<p class="text-danger">
                        				欄位內容格式錯誤或必填欄位未填
                        			</p>
                        		@endif
                        	@endif                        	
							<input type="file" name="file" class="" style="margin: 2px" 
								onclick="checkFile(event)">
							<button class="btn btn-primary" style="margin: 2px" onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('partner_school/example')}}">範例檔案</a>
							<a class="btn btn-success" href="{{url('example')}}">系所對照表</a>								
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection