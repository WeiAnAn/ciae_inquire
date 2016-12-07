@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<a href="{{url('/manage')}}" style="color: black">
			<h1 class="page-header">帳號管理</h1>
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
									<td id="username" onclick="sort(id)">使用者名稱</td>
									<td id="user.college" onclick="sort(id)">單位</td>
									<td id="user.dept" onclick="sort(id)">系所部門</td>
									<td id="chtName" onclick="sort(id)">中文名稱</td>
									<td id="engName" onclick="sort(id)">英文名稱</td>
									<td id="contactPeople" onclick="sort(id)">聯絡人</td>
									<td id="phone" onclick="sort(id)">電話</td>
									<td id="eamil" onclick="sort(id)">電子信箱</td>
									<td id="permission" onclick="sort(id)">權限</td>
									<td>管理</td>
								</tr>
								</thead>
								<tbody>
								@foreach ($user as $data)
								<tr id="{{$data->id}}">
									<td class="text-nowrap">{{$data->username}}</td>
									<td class="text-nowrap">{{$data->chtCollege}}</td>
									<td >{{$data->chtDept}}</td>
									<td>{{$data->chtName}}</td>
									<td>{{$data->engName}}</td>
									<td>{{$data->contactPeople}}</td>
									<td>{{$data->phone}}</td>
									<td>{{$data->email}}</td>
									<td>
									@if($data->permission == 0)
										最高權限
									@elseif($data->permission == 1)
										所有權限
									@elseif($data->permission == 2)
										院級權限
									@elseif($data->permission == 3)
										系級權限
									@elseif($data->permission == 4)
										無權限
									@endif
									</td>
									<td class="text-nowrap">
										<a href="{{url('manage',$data->id)}}"
											class="glyphicon glyphicon-pencil	
											btn btn-success btn-xs"></a>
										<form action="{{url('manage',$data->id)}}"
											method="post" style="display: inline;">
											{{ method_field('DELETE') }}
                        					{{ csrf_field() }}
											<button class="glyphicon glyphicon-trash
												btn btn-danger btn-xs" 
												onclick="clickDel(event)"></button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						{{ $user->links()}}
					</div>
					
					<!--insert page-->

					@if(count($errors)>0)
						<div class="tab-pane fade in col-md-12 active " id="insert" 
							style="margin-top: 10px">
					@else
						<div class="tab-pane fade in col-md-12 " id="insert" 
							style="margin-top: 10px">
					@endif
						<form action="{{url('/manage')}}" method="post">
                        	{{ csrf_field() }}
							@include("../layouts/select")

							@if($errors->has('username'))
                                <p class="text-danger">{{$errors->first('username')}}</p>
                            @endif
							<div class="form-group">
								<label for="username">使用者名稱</label>
								<input type="text" name="username" class="form-control" value="{{old('username')}}">
							</div>

							@if($errors->has('password'))
                                <p class="text-danger">{{$errors->first('password')}}</p>
                            @endif
							<div class="form-group">
								<label for="password">使用者密碼</label>
								<input type="text" name="password" class="form-control">
							</div>

							<div class="form-group">
                                <label for="password" >確認密碼</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

							@if($errors->has('chtName'))
                                <p class="text-danger">{{$errors->first('chtName')}}</p>
                            @endif
							<div class="form-group">
								<label for="chtName">中文名稱</label>
								<input type="text" name="chtName" class="form-control" value="{{old('chtName')}}">
							</div>

							@if($errors->has('engName'))
                                <p class="text-danger">{{$errors->first('engName')}}</p>
                            @endif
							<div class="form-group">
								<label for="engName">英文名稱</label>
								<input type="text" name="engName" class="form-control" value="{{old('engName')}}">
							</div>

							@if($errors->has('contactPeople'))
                                <p class="text-danger">{{$errors->first('contactPeople')}}</p>
                            @endif
							<div class="form-group">
								<label for="contactPeople">聯絡人</label>
								<input type="text" name="contactPeople" class="form-control" value="{{old('contactPeople')}}">
							</div>

							@if($errors->has('phone'))
                                <p class="text-danger">{{$errors->first('phone')}}</p>
                            @endif
							<div class="form-group">
								<label for="phone">電話</label>
								<input type="text" class="form-control" name="phone"
									value="{{old('phone')}}"></input>
							</div>

							@if($errors->has('email'))
                                <p class="text-danger">{{$errors->first('email')}}</p>
                            @endif
							<div class="form-group">
								<label for="email">電子信箱</label>
								<input type="text" class="form-control" name="email"
									value="{{old('email')}}"></input>
							</div>
							@if($errors->has('permission'))
								<p class="text-danger">{{$errors->first('permission')}}</p>
							@endif
							<div class="form-group">
								<label for="permission">權限</label>
								<select name="permission" id="permission_option" 
									class="form-control">
									<option value="1">所有權限</option>
									<option value="2">院級權限</option>
									<option value="3">系級權限</option>
									<option value="4">無權限</option>
								</select>
							</div>
							@if($errors->has('permission'))
								<script>
									document.getElementById('permission_option').value 
										= {{old('permission')}};
								</script>
							@endif


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
						<form action="{{url('/manage/search')}}" method="get">
							@include("../layouts/select_search")
							<div class="form-group">
								<label for="username">使用者名稱</label>
								<input type="text" name="username" class="form-control">
							</div>

							<div class="form-group">
								<label for="chtName">中文名稱</label>
								<input type="text" name="chtName" class="form-control">
							</div>

							<div class="form-group">
								<label for="engName">英文名稱</label>
								<input type="text" name="engName" class="form-control">
							</div>

							<div class="form-group">
								<label for="contactPeople">聯絡人</label>
								<input type="text" name="contactPeople" class="form-control">
							</div>

							<div class="form-group">
								<label for="phone">電話</label>
								<input type="text" class="form-control" name="phone"></input>
							</div>

							<div class="form-group">
								<label for="email">電子信箱</label>
								<input type="text" class="form-control" name="email"></input>
							</div>
							<div class="form-group">
								<label for="permission">權限</label>
								<select name="permission" id="permission_option" 
									class="form-control">
									<option value=""></option>
									<option value="0">最高權限</option>
									<option value="1">所有權限</option>
									<option value="2">院級權限</option>
									<option value="3">系級權限</option>
									<option value="4">無權限</option>
								</select>
							</div>
							<button class="btn btn-success">搜尋</button>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection