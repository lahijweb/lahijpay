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
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Page bg image-->
    <style>body { background-image: url('assets/media/bg10.jpeg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/bg10-dark.jpeg'); }</style>
    <!--end::Page bg image-->
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Body-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
            <!--begin::Card-->
            <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10">
                        <!--begin::Icon-->
                        <div class="text-center mb-10">
                            <img alt="Logo" class="mh-125px" src="assets/media/card.png" />
                        </div>
                        <!--end::Icon-->
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">
                                @if(!$errors->any() && @session('message')['status'] == 'success')
                                    پرداخت موفق
                                @else
                                    خطا
                                @endif
                            </h1>
                            <!--end::Title-->
                            <!--begin::Sub-title-->
                            <div class="fw-semibold fs-4 mb-5">
                                @if(!$errors->any() && @session('message')['status'] == 'success')
                                    {{ session('message')['message'] }}
                                @else
                                    {{ $errors->first() }}
                                @endif
                            </div>
                            <!--end::Sub-title-->
                        </div>
                        <!--end::Heading-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Card-->
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
