@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Categories</h2>
            </div>
            <div class="pull-right">
                @can('product-create')
                <a class="btn btn-success" href="{{ route('categories.create') }}"> Create New Category</a>
                @endcan
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        <?php $i = 0;?>
        @foreach ($categories as $category)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $category->name }}</td>
	        <td>
                <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                    @can('product-edit')
                    <a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}">Edit</a>
                    @endcan


                    @csrf
                    @method('DELETE')
                    @can('product-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan
                </form>
	        </td>
	    </tr>
	    @endforeach
    </table>


@endsection