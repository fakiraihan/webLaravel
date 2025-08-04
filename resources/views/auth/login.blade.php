@extends('layouts.app')

@section('title', 'Login - Bloggr')

@section('content')
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#2d372a] px-10 py-3">
  <div class="flex items-center gap-4 text-white">
    <div class="size-4">
      <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6H42L36 24L42 42H6L12 24L6 6Z" fill="currentColor"></path></svg>
    </div>
    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Bloggr</h2>
  </div>
  <div class="flex flex-1 justify-end gap-8">
    <div class="flex items-center gap-9">
    </div>
    <button
      class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 bg-[#2d372a] text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5"
    >
      <div class="text-white" data-icon="Bell" data-size="20px" data-weight="regular">
        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
          <path
            d="M221.8,175.94C216.25,166.38,208,139.33,208,104a80,80,0,1,0-160,0c0,35.34-8.26,62.38-13.81,71.94A16,16,0,0,0,48,200H88.81a40,40,0,0,0,78.38,0H208a16,16,0,0,0,13.8-24.06ZM128,216a24,24,0,0,1-22.62-16h45.24A24,24,0,0,1,128,216ZM48,184c7.7-13.24,16-43.92,16-80a64,64,0,1,1,128,0c0,36.05,8.28,66.73,16,80Z"
          ></path>
        </svg>
      </div>
    </button>
    <div
      class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
      style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC7EzU2jSxkWek5jI8cHJaRaiOG57hHW8uA3OPG_EWmkUigZdqb_25tAUAqDC9lsdO1ejxF5jFr63QOjweUYzwmxxupNAPJjmJKyM8G7_xSpT3Qu84M1gXj-pba7iJoNM2HPg9k4Q1odgO-emvhe_2qCQcZ4P5OX6bfaJaxkWKRc9bEiV7meViRTRbgGhO3j-enkeE8JTZDe3UVE0rsbbxUXWzUFu9_vhFUzuNtvRzKX_-Y5C35_wPUfsNifpYUNKSKNA5BgvmSRGM");'
    ></div>
  </div>
</header>
<div class="flex flex-1 items-center justify-center px-40 py-5">
  <div class="layout-content-container flex flex-col w-[512px] max-w-[512px] py-5 max-w-[960px] flex-1">
    <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Welcome back</h2>
    
    @if ($errors->any())
      <div class="mx-4 mb-4 p-4 bg-red-600/20 border border-red-600/50 rounded-xl">
        <ul class="text-red-300 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
        <label class="flex flex-col min-w-40 flex-1">
          <p class="text-white text-base font-medium leading-normal pb-2">Username</p>
          <input
            name="username"
            type="text"
            placeholder="Username"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('username') }}"
            required
          />
        </label>
      </div>
      <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
        <label class="flex flex-col min-w-40 flex-1">
          <p class="text-white text-base font-medium leading-normal pb-2">Password</p>
          <input
            name="password"
            type="password"
            placeholder="Password"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            required
          />
        </label>
      </div>
      <div class="flex px-4 py-3">
        <button
          type="submit"
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 flex-1 bg-[#53d22c] text-[#131712] text-sm font-bold leading-normal tracking-[0.015em]"
        >
          <span class="truncate">Login</span>
        </button>
      </div>
    </form>
    <p class="text-[#a5b6a0] text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center">
      <a href="{{ route('register') }}" class="underline">No account? Register here</a>
    </p>
    <p class="text-[#a5b6a0] text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center underline">Forgot Password?</p>
  </div>
</div>
@endsection
