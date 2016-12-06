@extends('../layouts/master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">本校教師赴國外出席國際會議資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('prof_exchange',$id)}}" method="post">
				{{ method_field('PATCH') }}
					{{ csrf_field() }}
					@include("../layouts/select_edit")
					<div class="form-group">
						<label for="">姓名</label>
						<input type="text" name="name" class="form-control" 
							value="{{$name}}">
					</div>
					<div class="form-group">
						<label for="profLevel">身分</label>
						<select name="profLevel" id="profLevel" class="form-control" >
							<option value="1">教授</option>
							<option value="2">副教授</option>
							<option value="3">助理教授</option>
							<option value="4">博士後</option>
							<option value="5">研究生</option>
						</select>

					</div>
					<div class="form-group">
						<label for="">前往國家</label>
						<input type="text"  name="nation" class="form-control" 
							value="{{$nation}}"></input>
					</div>
					
					<div class="form-group">
						<label for="startDate">開始時間</label>
						<input type="date" name="startDate" class="form-control"
							value="{{$startDate}}">
					</div>
					<div class="form-group">
						<label for="endDate">結束時間</label>
						<input type="date" name="endDate" class="form-control"
							value="{{$endDate}}">
					</div>
					<div class="form-group">
						<label for="comments">備註</label>
						<input type="text" name="comments" class="form-control" 
							value="{{$comments}}">
					</div>

					<button class="btn btn-success">修改</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById('profLevel').value = {{$profLevel}};
</script>
@endsection