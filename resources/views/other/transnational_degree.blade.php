@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{URL('transnational_degree')}}" style="color:black">
			<h1 class="page-header">跨國學位</h1>
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
	                <li><a href="#search" data-toggle="tab">進階搜尋</a>
	                </li>
	                <li><a href="#upload" data-toggle="tab">批次上傳</a>
	                </li>
				@elseif(count($errors->upload)>0)
	                <li><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li><a href="#insert" data-toggle="tab">新增</a>
	                </li>
	                <li><a href="#search" data-toggle="tab">進階搜尋</a>
	                </li>
	                <li class="active"><a href="#upload" data-toggle="tab">批次上傳</a>
	                </li>
	            @else
	           		<li class="active"><a href="#show" data-toggle="tab">檢視</a>
	                </li>
	                <li><a href="#insert" data-toggle="tab">新增</a>
	                </li>
	                <li><a href="#search" data-toggle="tab">進階搜尋</a>
	                </li>
	                <li><a href="#upload" data-toggle="tab">批次上傳</a>
	                </li>
	            @endif
            </ul>
            
            	<div class="tab-content">
					@if(count($errors)>0||count($errors->upload)>0)
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
									<td id="transnational_degree.college" class="text-nowrap"
										onclick="sort(id)">所屬單位
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="transnational_degree.dept" class="text-nowrap"
										onclick="sort(id)">系所部門
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="nation" class="text-nowrap"
										onclick="sort(id)">國家
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="chtName" class="text-nowrap"
										onclick="sort(id)">中文校名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="engName" class="text-nowrap"
										onclick="sort(id)">英文校名
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="bachelor" class="text-nowrap"
										onclick="sort(id)">學士
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="master" class="text-nowrap"
										onclick="sort(id)">碩士
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="PHD" class="text-nowrap"
										onclick="sort(id)">博士
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="" class="text-nowrap"
										onclick="sort(id)">修業年限
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="classMode" class="text-nowrap"
										onclick="sort(id)">授課方式
										<i class="fa fa-sort" aria-hidden="true"></i>
									</td>
									<td id="degreeMode" class="text-nowrap"
										onclick="sort(id)">學位授予方式
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
								@foreach($transnational as $data)
								<tr>
									<td>{{$data->chtCollege}}</td>
									<td>{{$data->chtDept}}</td>
									<td>{{$data->nation}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>@if($data->bachelor==1)	
									o
									@else
									x
									@endif</td>
									<td>@if($data->master==1)
									o
									@else
									x
									@endif</td>
									<td>@if($data->PHD==1)
									o
									@else
									x
									@endif
									<td></td>
									<td>{{$data->classMode}}</td>
									<td>{{$data->degreeMode}}</td>
									<td>{{$data->comments}}</td>
									<td class="text-nowrap">
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
							<div class="form-group">
								<label for="profLevel">授予身分</label>
							</div>
							
							<div class="form-group">
								<label for="bachelor">學士</label>
								<select name="bachelor" id="bachelor_option" class="form-control">
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
							</div>
							<div class="form-group">
								<label for="master">碩士</label>
								<select name="master" id="master_option" class="form-control">
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
							</div>
							<div class="form-group">
								<label for="PHD">博士</label>
								<select name="PHD" id="PHD_option" class="form-control">
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
							</div>


							@if($errors->has('classMode'))
                                <p class="text-danger">{{$errors->first('classMode')}}</p>
                            @endif
							<div class="form-group">
								<label for="classMode">授課方式</label>
								<textarea name="classMode" id="classMode" cols="30" rows="3" class="form-control">{{old('classMode')}}</textarea>
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
								<select name="bachelor" id="bachelor" class="form-control">
									<option value="">所有授予情形</option>
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
							</div>
							<div class="form-group">
								<label for="master">碩士</label>
								<select name="master" id="master" class="form-control">
									<option value="">所有授予情形</option>
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
							</div>
							<div class="form-group">
								<label for="PHD">博士</label>
								<select name="PHD" id="PHD" class="form-control">
									<option value="">所有授予情形</option>
									<option value="0">無授予</option>
									<option value="1">有授予</option>
								</select>
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

					@if(count($errors->upload)>0)
						<div class="tab-pane fade in col-md-12 active" id="upload" style="margin-top: 10px;">
					@else
						<div class="tab-pane fade in col-md-12" id="upload" style="margin-top: 10px;">
					@endif
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
@if(count($errors)>0)
	<script>
		document.getElementById('bachelor_option').value ={{old('bachelor')}};
		document.getElementById('master_option').value ={{old('master')}};
		document.getElementById('PHD_option').value ={{old('PHD')}};
	</script>
@endif
@endsection