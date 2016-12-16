@extends('../layouts/master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">外籍學者蒞校交換資料修改</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="{{url('foreign_prof_exchange',$id)}}" method="post">
				{{ method_field('PATCH') }}
						{{ csrf_field() }}
						@include('../layouts/select_edit')

						@if($errors->has('name'))
                            <p class="text-danger">{{$errors->first('name')}}</p>
                        @endif
						<div class="form-group">
							<label for="">外籍學者姓名</label>
							<input type="text" class="form-control" name="name" value="{{$name}}">
						</div>
						<div class="form-group">
							<label for="profLevel">外籍學者身分</label>
							<select name="profLevel" id="profLevel_option" class="form-control">
								<option value="1">教授</option>
								<option value="2">副教授</option>
								<option value="3">助理教授</option>
								<option value="4">博士候選人</option>
								<option value="5">研究生</option>
							</select>
						</div>

						@if($errors->has('nation'))
                            <p class="text-danger">{{$errors->first('nation')}}</p>
                        @endif
						<div class="form-group">
							<label for="nation">國籍</label>
							<input type="text" name="nation" class="form-control" value="{{$nation}}">
						</div>
							
						@if($errors->has('startDate')||$errors->has('endDate'))
                            <p class="text-danger col-md-6">{{ $errors->first('startDate')}}</p>                      
                            <p class="text-danger col-md-6">{{ $errors->first('endDate')}}</p>
                        @endif
						<div class="form-group col-md-6" style="padding-left:0 ;	padding-right: 0">
								<label for="startDate">開始時間</label>
								<input type="date" name="startDate" class="form-control" value="{{$startDate}}">
						</div>
						<div class="form-group col-md-6" style="padding-left:0 ;	padding-right: 0">
								<label for="endDate">結束時間</label>
								<input type="date" name="endDate" class="form-control" value="{{$endDate}}">
						</div>

						@if($errors->has('comments'))
                            <p class="text-danger">{{$errors->first('comments')}}</p>
                        @endif
						<div class="form-group">
							<label for="comments">備註</label>
							<textarea name="comments" id="comments" cols="30" rows="3" class="form-control">{{$comments}}</textarea>
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