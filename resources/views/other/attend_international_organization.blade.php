@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">參與國際組織</h1>
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
						<div class="tab-pane fade in active table-responsive " id="show" 
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
									<td id="attend_international_organization.college" onclick="sort(id)">所屬一級單位</td>
									<td id="attend_international_organization.dept" onclick="sort(id)">所屬系所部門</td>
									<td id="name" onclick="sort(id)">參加人</td>
									<td id="orgnization" onclick="sort(id)">組織名稱</td>
									<td id="startDate" onclick="sort(id)">開始時間</td>
									<td id="endDate" onclick="sort(id)">結束時間</td>
									<td id="comments" onclick="sort(id)">備註</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
									@foreach ($attendiorganization as $data)
										<tr>
											<td>{{$data->chtCollege}}</td>
											<td>{{$data->chtDept}}</td>
											<td>{{$data->name}}</td>										
											<td>{{$data->organization}}</td>
											<td>{{$data->startDate}}</td>
											<td>{{$data->endDate}}</td>
											<td>{{$data->comments}}</td>
											<td class="text-nowrap">
												@can('permission',$data)
												<a href="{{url('attend_international_organization',$data->id)}}"
													class="glyphicon glyphicon-pencil	
													btn btn-success btn-xs"></a>
												<form action="{{url('attend_international_organization',$data->id)}}"
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

					</div>

					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('attend_international_organization')}}" method="post">
							{{ csrf_field() }}
							@include('../layouts/select')
							
							@if($errors->has('name'))
                                <p class="text-danger">{{$errors->first('name')}}</p>
                            @endif
							<div class="form-group">
								<label for="">參加人</label>
								<input type="text" class="form-control" name="name" value="{{old('name')}}" />
							</div>
							
							@if($errors->has('orgName'))
                                <p class="text-danger">{{$errors->first('orgName')}}</p>
                            @endif
							<div class="form-group">
								<label for="orgnization">組織名稱</label>
								<textarea name="orgnization" id="orgnization" cols="30" rows="3" class="form-control">{{old('name')}}</textarea>
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
						<form action="{{url('attend_international_organization/search')}}">
							@include('../layouts/select_search')
							<div class="form-group">
								<label for="">參加人</label>
								<input type="text" class="form-control" name="name" />
							</div>
							<div class="form-group">
								<label for="orgnization">組織名稱</label>
								<textarea name="orgnization" id="orgnization" cols="30" rows="3" class="form-control"></textarea>
							</div>

							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
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
						<form action="{{url('attend_international_organization/upload')}}" method="post" enctype="multipart/form-data">			
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
							<input type="file" name= "file"class="" style="margin: 2px">
							<button class="btn btn-primary" style="margin: 2px" 
								onclick="checkFile(event)">上傳</button>
							<a class="btn btn-success" href="{{url('attend_international_organization/example')}}">範例檔案</a>
							<a class="btn btn-success" href="{{url('example')}}">系所對照表</a>										
						</form>
					</div>



            
            </div>
        </div>
	</div>
</div>
@endsection