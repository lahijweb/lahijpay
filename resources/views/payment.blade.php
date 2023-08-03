<!DOCTYPE html>
<html direction="rtl" dir="rtl" style="direction: rtl">
<!--begin::Head-->
<head>
    <title>LahijPay</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-Variable-font-face.css" rel="stylesheet" type="text/css" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page bg image-->
    <style>body { background-image: url('assets/media/bg10.jpeg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/bg10-dark.jpeg'); }</style>
    <!--end::Page bg image-->
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Body-->

        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
            <!--begin::Wrapper-->
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    @if ($errors->any())
                    <!--begin::Alert-->
                    <div class="alert alert-danger d-flex align-items-center p-5">
                        <!--begin::Icon-->
                        <i class="ki-duotone ki-message-text fs-2hx text-active-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <!--end::Icon-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column">
                            <!--begin::Title-->
                            <h4 class="mb-1 text-danger">خطا</h4>
                            <!--end::Title-->
                            <!--begin::Content-->
                            @foreach ($errors->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Alert-->
                    @endif
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-center flex-column flex-column-fluid">
                        <!--begin::Form-->
                        <form method="post" class="form w-100" action="{{route('payment.store')}}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">پرداخت آنلاین</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6">پرداخت آنلاین وجه لاهیج پی</div>
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Heading-->
                            <div class="row mb-6">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class=" fs-5 mb-2">نام</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control bg-transparent" placeholder="نام" name="first_name" value="{{old('first_name')}}">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--end::Label-->
                                    <label class="fs-5 mb-2">نام خانوادگی</label>
                                    <!--end::Label-->
                                    <!--end::Input-->
                                    <input type="text" class="form-control bg-transparent" placeholder="نام خانوادگی" name="last_name" value="{{old('last_name')}}">
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--begin::Input group=-->
                            <div class="fv-row mb-6">
                                <!--begin::Email-->
                                <label class="fs-5 mb-2">ایمیل</label>
                                <input type="email" placeholder="Email" name="email" class="form-control bg-transparent" value="{{old('email')}}" />
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-6">
                                <label class="fs-5 mb-2">موبایل</label>
                                <input type="text" placeholder="موبایل" name="mobile" class="form-control bg-transparent" value="{{old('mobile')}}" />
                            </div>
                            <!--end::Input group=-->
                            <!--end::Input group=-->
                            <div class="fv-row mb-6">
                                <label class="required fs-5 mb-2">مبلغ</label>
                                <div class="input-group mb-5">
                                    <input type="text" name="amount" class="form-control" placeholder="مبلغ پرداختی به ریال" value="{{old('amount')}}" required />
                                    <span class="input-group-text">ریال</span>
                                </div>
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="required fs-6 mb-2">درگاه</label>
                                <!--End::Label-->
                                <!--begin::Row-->
                                <div class="row row-cols-2 row-cols-md-3 g-5">
                                    @foreach($drivers as $driver)
                                        <!--begin::Col-->
                                        <div class="col">
                                            <!--begin::Option-->
                                            <input type="radio" class="btn-check" name="driver" value="{{$driver->driver}}" id="{{$driver->driver}}" @if($driver->is_default) checked="checked" @endif>
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex flex-column flex-center gap-5 h-100" for="{{$driver->driver}}">
                                                <!--begin::Label-->
                                                <div class="fs-5 fw-bold">{{$driver->name}}</div>
                                                <!--end::Label-->
                                            </label>
                                        </div>
                                        <!--end::Col-->
                                    @endforeach
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">پرداخت</span>
                                    <!--end::Indicator label-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
