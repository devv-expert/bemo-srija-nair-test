<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        .modal-mask {
            position: fixed;
            z-index: 9998;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: table;
            transition: opacity 0.3s ease;
            }

            .modal-wrapper {
            display: table-cell;
            vertical-align: middle;
            }

            .modal-container {
            width: 600px;
            margin: 0px auto;
            padding: 50px 30px;
            background-color: #fff;
            border-radius: 2px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
            transition: all 0.3s ease;
            font-family: Helvetica, Arial, sans-serif;
            }

            .modal-header h3 {
            margin-top: 0;
            }

            .modal-body {
            margin: 20px 0;
            }

            .modal-default-button {
            float: right;
            }

            /*
            * The following styles are auto-applied to elements with
            * transition="modal" when their visibility is toggled
            * by Vue.js.
            *
            * You can easily play with the modal transition by editing
            * these styles.
            */

            .modal-enter {
            opacity: 0;
            }

            .modal-leave-active {
            opacity: 0;
            }

            .modal-enter .modal-container,
            .modal-leave-active .modal-container {
            -webkit-transform: scale(1.1);
            transform: scale(1.1);
            }
    </style>
    <script type="text/x-template" id="modal-template">
    <transition name="modal">
          <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container">
  
                <div class="modal-header">
                  <slot name="header">
                    default header
                  </slot>
                </div>
  
                <div class="modal-body">
                  <slot name="body">
                    default body
                  </slot>
                </div>
  
                <div class="modal-footer">
                  <slot name="footer">
                    <button class="modal-default-button text-sm bg-white hover:bg-gray-100 text-black-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow" @click="$emit('close')">
                      Close
                    </button>
                  </slot>
                </div>
              </div>
            </div>
          </div>
        </transition>
    </script>
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
    <div id="app">
        <nav class="bg-grey-900 text-black shadow mb-8 py-6">
            <div class="container mx-auto px-6 md:px-0">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                          <div >
                           
                            <a target="__blank" class="no-underline hover:underline text-sm font-normal text-teal-800 uppercase pr-6" href="{{url('/db-dump')}}"><button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Export Database</button></a>
                          
                        </div>
                        </a>
                    </div>
                    <!-- <div class="flex-1 text-right">
                        @guest
                            <a class="no-underline hover:underline text-black text-sm p-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a class="no-underline hover:underline text-black text-sm p-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            <span class="text-black-300 text-sm pr-4">{{ Auth::user()->name }}</span>

                            <a href="{{ route('logout') }}"
                               class="no-underline hover:underline text-black-300 text-sm p-3"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                            </form>
                        @endguest
                    </div> -->
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
</body>
</html>
