@extends('layout.app')
@section('content')
    <?php
    $userRole = Auth::user()->role;
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Brand</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
            <div class="row">
                <div class="col-2 ">
                    <button class="btn btn-sm py-2 btn-secondary" onclick="history.back()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Back</button>

                </div>
                <div class="col-8"></div>
                <div class="col-2 text-right">
                    @if ($userRole == 1 || $userRole == 0)
                        <a href="{{ url('brand/info') }}" class="btn btn-sm py-2 btn-primary">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            New Brand</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Description</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Description</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $brand)
                            <tr id="tr_{{$brand->id}}">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $brand['brand']; ?></td>
                                <td>
                                    @if ($brand->status == 1)
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('brand/status/' . $brand->id) }} @endif"
                                            class="btn btn-sm btn-success">Active</a>
                                    @else
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('brand/status/' . $brand->id) }} @endif"
                                            class="btn btn-sm btn-secondary">Inactive</a>
                                    @endif
                                </td>
                                <td><?php echo $brand['description']; ?></td>
                                @if ($userRole == 1 || $userRole == 0)
                                    <td>
                                        <a href="{{ url('brand/edit/' . $brand->id) }}" class="btn btn-sm btn-info"><i
                                                class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                        <button data-id="{{ url('brand/delete/' . $brand->id) }}"
                                            class="btn btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                                aria-hidden="true"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('common.script_alert')
@endsection
