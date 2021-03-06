


<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<caption>Made by MOHAMAD RIYAN</caption>
		<thead class="thead-dark">
			<tr>
				<th scope="col">No</th>
				<th scope="col">Sampul</th>
				<th scope="col">Nama</th>
				<th scope="col">Jumlah Pemilih</th>
				<th scope="col">Hasil</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data->choice as $item)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td><img src="{{asset($item->sampul)}}" alt="" width="120px"></td>
				<td class="h5">{{$item->name}}</td>
				<td class="h5"><li class="fa fa-users"></li> {{$item->vote->count()}}</td>
				@if($data->vote->count() == 0)
				<td class="h5" width="130px"> 0%</td>
				@else
				<td class="h5" width="130px">{{round($item->vote->count() / $jml * 100,2)}}%</td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
