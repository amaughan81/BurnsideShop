<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Burnside Merit Shop @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" id="main-nav">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/browse">All Products</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(isStudent() || isManager())
                    <li id="merit-balance-status">
                        <a hre="#" class="text-warning">
                        Merit Points:
                        <span>@if(count(auth()->user()->merits))
                        {{ auth()->user()->merits->balance() }} BM
                            @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isAdmin() || isManager())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-briefcase"></span>
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/categories">Categories</a></li>
                            <li><a href="/products">Products</a></li>
                            <li role="separator" class="divider"></li>
                            @if(isAdmin())
                            <li><a href="/coupons">Coupons</a></li>
                            @endif
                            <li><a href="/orders">Orders</a></li>
                            @if(isAdmin())
                            <li role="separator" class="divider"></li>
                            <li><a href="/users">Users</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span>
                            Your Account
                        </a>
                        <ul class="dropdown-menu">
                            @if(!isLoggedIn())
                            <li><a href="/login">Login</a></li>
                            @else
                            <li><a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @endif
                            @if(isStudent() || isManager())
                            <li role="separator" class="divider"></li>
                            <li><a href="/my-account">Your Account</a></li>
                            <li><a href="/orders">My Orders</a></li>
                            @endif
                        </ul>
                    </li>
                    <li class="">
                        <a href="/cart">
                            <span class="glyphicon glyphicon-shopping-cart"></span>
                            Basket ({{getBasketCount()}})
                        </a>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <footer>
        <div class="container">
            &copy; <a href="https://www.burnsidecollege.org.uk" target="_blank">Burnside Business &amp; Enterprise College</a>
        </div>
    </footer>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    @yield('scripts')
    @include('layouts._scripts')
    @include('layouts._flash')

</body>
</html>