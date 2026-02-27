<!DOCTYPE html>
@php
$menuFixed = ($configData['layout'] === 'vertical') ? ($menuFixed ?? '') : (($configData['layout'] === 'front') ? '' : $configData['headerType']);
$navbarType = ($configData['layout'] === 'vertical') ? ($configData['navbarType'] ?? '') : (($configData['layout'] === 'front') ? 'layout-navbar-fixed' : '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = (isset($container) ? (($container === 'container-xxl') ? "layout-compact" : "layout-wide") : "");
@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}" class="{{ $configData['style'] }}-style {{($contentLayout ?? '')}} {{ ($navbarType ?? '') }} {{ ($menuFixed ?? '') }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['themeOpt'] . '-' . $configData['styleOpt'] }}" data-style="{{$configData['styleOptVal']}}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title') | OYA</title>
  <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.png') }}" />


  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles' . $isFront)

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes' . $isFront)
</head>

<body>
  <style>
    #page-loader-overlay {
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.74);
      backdrop-filter: blur(2px);
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      transition: opacity 0.18s ease, visibility 0.18s ease;
      z-index: 2000;
    }

    #page-loader-overlay.is-active {
      opacity: 1;
      visibility: visible;
      pointer-events: all;
    }

    #page-loader-overlay .loader-spinner {
      width: 2.5rem;
      height: 2.5rem;
      border: 0.22rem solid rgba(67, 89, 113, 0.2);
      border-top-color: #091c2d;
      border-radius: 50%;
      animation: page-loader-spin 0.75s linear infinite;
    }

    @keyframes page-loader-spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>

  <div id="page-loader-overlay" class="is-active" aria-hidden="true">
    <div class="loader-spinner" role="status" aria-label="Loading"></div>
  </div>

  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->



  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scripts' . $isFront)
  <script>
    (function () {
      var overlay = document.getElementById('page-loader-overlay');
      if (!overlay) return;

      function showLoader() {
        overlay.classList.add('is-active');
      }

      function hideLoader() {
        overlay.classList.remove('is-active');
      }

      window.pageLoader = {
        show: showLoader,
        hide: hideLoader
      };

      document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link) return;
        if (event.defaultPrevented) return;
        if (link.target && link.target.toLowerCase() === '_blank') return;
        if (link.hasAttribute('download')) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        if (link.dataset.noLoader === 'true') return;

        var href = (link.getAttribute('href') || '').trim().toLowerCase();
        if (!href || href.startsWith('#')) return;
        if (href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;

        showLoader();
      });

      document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || form.getAttribute('target') === '_blank') return;
        if (event.defaultPrevented) return;

        var action = (form.getAttribute('action') || '').trim().toLowerCase();
        if (action.startsWith('javascript:')) return;

        showLoader();
      });

      function hideWhenReady() {
        if (document.readyState === 'complete') {
          hideLoader();
          return;
        }

        window.addEventListener('load', hideLoader, { once: true });
      }

      window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
          hideLoader();
          return;
        }

        hideWhenReady();
      });

      hideWhenReady();
    })();
  </script>

</body>

</html>
