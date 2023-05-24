<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>{{ config('app.name', 'Mayden-Tech') }}</title>
    @stack('scripts')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary py-3 px-4">
        <a class="navbar-brand text-light" href="#">Mayden-Tech</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            @if(Auth::check())
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#spendingLimitModal">Set Spending Limit</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">Share Shopping List</button>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            @endif
        </div>
    </nav>

    <section class="section">
        <div class="container">
            @yield('content')
        </div>
    </section>

    @if(Auth::check())
        <!-- Spending Limit Modal -->
        <div class="modal fade" id="spendingLimitModal" tabindex="-1" aria-labelledby="spendingLimitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="spendingLimitModalLabel">Set Spending Limit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="spendingLimitForm" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="spendingLimitInput" class="form-label">Spending Limit</label>
                                <input type="number" class="form-control" id="spendingLimitInput" name="spending_limit" value="{{ Auth::user()->spending_limit }}" placeholder="Enter your spending limit">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="spendingLimitForm" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>    
        <!-- Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="emailForm" action="{{ route('shopping_list.send_email') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="recipientEmailInput" class="form-label">Recipient Email</label>
                                <input type="email" class="form-control" id="recipientEmailInput" name="recipient_email" placeholder="Enter recipient email" required>
                            </div>
                            <div class="mb-3">
                                <label for="messageTextarea" class="form-label">Message</label>
                                <textarea class="form-control" id="messageTextarea" name="message" rows="3" placeholder="Enter message"></textarea>
                            </div>
                            <input type="hidden" name="shopping_list_id" value="{{ $shoppingList->id }}">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="emailForm" class="btn btn-primary">Send Email</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
