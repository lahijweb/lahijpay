@include('layout.head')
<body class="bg-soft-primary-light">
@yield('content')
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/theme.min.js') }}"></script>
</body>
</html>
