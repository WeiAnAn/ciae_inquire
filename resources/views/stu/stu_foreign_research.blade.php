@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="stu_foreign_research" style="color: black">
			<h1 class="page-header">本校學生其他出國研修情形</h1>
		</a>
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
									<td id="stu_foreign_research.college" class="text-nowrap" 
										onclick="sort(id)">所屬單位
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="stu_foreign_research.dept" class="text-nowrap" 
										onclick="sort(id)">系所部門
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="name" class="text-nowrap" 
										onclick="sort(id)">姓名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="stuLevel" class="text-nowrap" 
										onclick="sort(id)">身分
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="nation" class="text-nowrap" 
										onclick="sort(id)">前往國家
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="startDate" class="text-nowrap" 
										onclick="sort(id)">開始時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="endDate" class="text-nowrap" 
										onclick="sort(id)">結束時間
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="comments" class="text-nowrap" 
										onclick="sort(id)">備註
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td class="text-nowrap">管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach($foreignreseach as $data)
								<tr>
									<td class="text-nowrap">{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td class="text-nowrap">{{$data->name}}</td>
									<td class="text-nowrap">@if ($data->stuLevel==1)
									博士班
									@elseif ($data->stuLevel==2)
									碩士班
									@else
									學士班
									@endif
									</td>
									<td>{{$data->nation}}</td>
									<td class="text-nowrap">{{$data->startDate}}</td>
									<td class="text-nowrap">{{$data->endDate}}</td>
									<td>{{$data->comments}}</td>
									<td class="text-nowrap">
										@can('permission',$data)
										<a href="{{url('stu_foreign_research',$data->id)}}"
									class="glyphicon glyphicon-pencil btn btn-success btn-xs"></a>
										<form action="{{url('stu_foreign_research',$data->id)}}"
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
					{{ $foreignreseach->links() }}	
					</div>
					
					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('stu_foreign_research')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('name'))
                                <p class="text-danger">{{$errors->first('name')}}</p>
                            @endif
							<div class="form-group">
								<label for="">姓名</label>
								<input type="text" class="form-control" name="name" value="{{old('name')}}">
							</div>
							<div class="form-group">
								<label for="stuLevel">身分</label>
								<select name="stuLevel" id="stuLevel_option" class="form-control">
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>

							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="nation">前往國家</label>
								<input type="text" name="nation" class="form-control" value="{{old('nation')}}">
							</div>

							@if($errors->has('startDate')||$errors->has('endDate'))
                                <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>                      
                                <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                            @endif
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control" value="{{old('startDate')}}">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
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
						<form action="{{url('stu_foreign_research/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="">姓名</label>
								<input type="text" class="form-control" name="name" />
							</div>
							<div class="form-group">
								<label for="stuLevel">身分</label>
								<select name="stuLevel" class="form-control">
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
							<div class="form-group" style="margin-bottom: 0px">
								<label >日期</label>
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">從</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
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
						<form action="{{url('stu_foreign_research/upload')}}" method="post" enctype="multipart/form-data">		
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
							<input type="file" name="file" class="" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px"
								onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('stu_foreign_research/example')}}">範例檔案</a>
							<a class="btn btn-success" href="{{url('example')}}">系所對照表</a>								
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@if(count($errors)>0)
	<script>
		document.getElementById('stuLevel_option').value ={{old('stuLevel')}};
	</script>
@endif
@endsection