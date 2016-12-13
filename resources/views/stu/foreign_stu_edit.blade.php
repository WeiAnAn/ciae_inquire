@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">修讀正式學位之外國學生資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('foreign_stu',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")
					<div class="form-group">
								<label for="stuID">學號</label>
								<input type="text" name="stuID" class="form-control" value="{{old('stuID')}}">
							</div>

							
							<div class="form-group">
								<label for="">中文姓名</label>
								<input type="text" class="form-control" name="chtName" value="{{old('chtName')}}">
							</div>

		
							<div class="form-group">
								<label for="">英文姓名</label>
								<input type="text" class="form-control" name="engName" value="{{old('engName')}}">
							</div>

							<div class="form-group">
								<label for="stuLevel">身分</label>
								<select name="stuLevel" id="stuLevel_option" class="form-control">
									<option value="1">博士班</option>
									<option value="2">碩士班</option>
									<option value="3">學士班</option>
								</select>
							</div>


							<div class="form-group">
								<label for="nation">中文國籍</label>
								<input type="text" name="nation" class="form-control" value="{{old('nation')}}">
							</div>


							<div class="form-group">
								<label for="engNation">英文國籍</label>
								<input type="text" name="engNation" class="form-control" value="{{old('nation')}}">
							</div>


							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control" value="{{old('startDate')}}">
							</div>
							<div class="form-group col-md-6" style="padding-left:0 ;padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="date" name="endDate" class="form-control" value="{{old('endDate')}}">
							</div>

							<div class="form-group">
								<label for="comments">備註</label>
								<input type="text" name="comments" class="form-control" 
									value="{{$comments}}">
							</div>

							<button class="btn btn-success">修改</button>
						</form>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById('stuLevel').value = {{$stuLevel}};
</script>
@endsection