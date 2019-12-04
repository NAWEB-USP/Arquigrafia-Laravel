<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 		@include("includes.head")

    @yield('head')

</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRLDGW3"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <!-- End Google Tag Manager (noscript) -->

  <div id="container">

 	@include("includes.header")

  @yield('content')

  @include("includes.footer")

	</div>

  @yield('scripts')

</body>
</html>
