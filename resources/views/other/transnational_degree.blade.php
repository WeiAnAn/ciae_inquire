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
									<td id="transnational_degree.college" onclick="sort(id)">單位</td>
									<td id="transnational_degree.dept" onclick="sort(id)">系所部門</td>
									<td id="nation" onclick="sort(id)">國家</td>
									<td id="chtName" onclick="sort(id)">中文校名</td>
									<td id="engName" onclick="sort(id)">英文校名</td>
									<td id="bachelor" onclick="sort(id)">學士</td>
									<td id="master" onclick="sort(id)">碩士</td>
									<td id="PHD" onclick="sort(id)">博士</td>
									<td id="classMode" onclick="sort(id)">授課方式</td>
									<td id="degreeMode" onclick="sort(id)">學位授予方式</td>
									<td id="comments" onclick="sort(id)">備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach($transnational as $data)
								<tr>
									<td>{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td>{{$data->nation}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>@if($data->bachelor==1)	
									有
									@else
									無
									@endif</td>
									<td>@if($data->master==1)
									有
									@else
									無
									@endif</td>
									<td>@if($data->PHD==1)
									有
									@else
									無
									@endif
									<td>{{$data->classMode}}</td>
									<td>{{$data->degreeMode}}</td>
									<td>{{$data->comments}}</td>
									<td>
										@can('permission',$data)
										<a href="{{url('transnational_degree',$data->id)}}"
											class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form 
											action="{{url('transnational_degree',$data->id)}}"
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
					
					{{ $transnational->links() }}	
					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('transnational_degree')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')

							@if($errors->has('nation'))
                                <p class="text-danger">{{$errors->first('nation')}}</p>
                            @endif
							<div class="form-group">
								<label for="nation">國家</label>
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
							<!--還沒完成-->
							<div class="form-group">
								<label for="profLevel">授予身分</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="bachelor" class="checkbox-inline" value="{{old('bachelor')}}" >學士
								<input type="checkbox" name="master" class="checkbox-inline" value="{{old('master')}}">碩士
								<input type="checkbox" name="PHD" class="checkbox-inline" value="{{old('PHD')}}">博士
							</div>

							@if($errors->has('teachMode'))
                                <p class="text-danger">{{$errors->first('teachMode')}}</p>
                            @endif
							<div class="form-group">
								<label for="teachMode">授課方式</label>
								<textarea name="teachMode" id="teachMode" cols="30" rows="3" class="form-control">{{old('teachMode')}}</textarea>
							</div>

							@if($errors->has('degreeMode'))
                                <p class="text-danger">{{$errors->first('degreeMode')}}</p>
                            @endif
							<div class="form-group">
								<label for="degreeMode">學位授予方式</label>
								<textarea name="degreeMode" id="degreeMode" cols="30" rows="3" class="form-control">{{old('degreeMode')}}</textarea>
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
								<label for="master">碩士</label>
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
						<form action="{{url('transnational_degree/upload')}}" method="post" enctype="multipart/form-data">
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
							<button class="btn btn-primary" style="margin: 2px" onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('transnational_degree/example')}}">範例檔案</a>
							<a class="btn btn-success" href="{{url('example')}}">系所對照表</a>										
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection