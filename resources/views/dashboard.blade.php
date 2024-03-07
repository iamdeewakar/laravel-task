@extends('layouts.app')

@section('content')
<div class="dashboard-container">
  <h1>Dashboard</h1>
  <p>Welcome to the dashboard, {{ Auth::user()->name ?? '' }}!</p>
 
  @if(Auth::check())
    @php
        $user = Auth::user();
        
        $User_amount = App\Models\amount::where('user_id', $user->id)->first()?? 0 ;
        $amount = $User_amount->amount??0;
        @endphp
    @endif
 
  <div class="balance-and-logout">
    <p style="font-size: large;">Your current balance is: {{ $amount ?? 0}}</p>
    <a href="{{ route('logout') }}" class="logout-btn">Logout</a>
  </div>
</div>

@endsection

<style>
  .dashboard-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .balance-and-logout {
    display: flex;
    justify-content: space-between;
    width: 100%; /* Adjust width as needed */
  }

  .logout-btn {
    text-decoration: none;
    color: #000;
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
</style>
