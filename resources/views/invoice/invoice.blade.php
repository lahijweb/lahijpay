@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container content-space-1 content-space-t-md-1">
            <div class="mx-auto" style="max-width: 40rem;">
                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif
                <div class="card card-lg card-bordered">
                    <div class="card-header border text-center">
                        <h1 class="card-title fs-4">پرداخت فاکتور</h1>
                        <p class="card-text">شماره فاکتور: {{ $invoice->invoice_no }}</p>
                    </div>
                    <div class="card-body">
                        <form method="post" class="form w-100" action="{{ route('invoice.store', $invoice->uuid) }}">
                            @csrf
                            <div class="text-center">
                                <p>مبلغ فاکتور</p>
                                <div class="text-primary fw-bold">
                                    <span class="display-5">{{ number_format($invoice->total) }}</span>
                                    <span class="fs-6">ریال</span>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a class="link" href="#">مشاهده جزئیات فاکتور</a>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label">درگاه</label>
                                </div>
                                @foreach($drivers as $driver)
                                <div class="col-sm-30 mb-2">
                                    <label class="form-control" for="{{$driver->driver}}">
                                      <span class="form-check">
                                        <input type="radio" class="form-check-input" name="driver" value="{{ $driver->driver }}" id="{{ $driver->driver }}" @checked(old('driver', $driver->is_default))>
                                        <span class="form-check-label">{{ $driver->name }}</span>
                                      </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">پرداخت</button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('layout.copyright')
            </div>
        </div>
    </main>
@endsection
