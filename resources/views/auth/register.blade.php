@extends('layouts.app')

@section('title', 'Register - Bloggr')

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
      <a class="text-white text-sm font-medium leading-normal" href="{{ route('home') }}">Home</a>
      <a class="text-white text-sm font-medium leading-normal" href="#">Articles</a>
      <a class="text-white text-sm font-medium leading-normal" href="#">About</a>
    </div>
    <a href="{{ route('login') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
      <span class="truncate">Sign In</span>
    </a>
  </div>
</header>
<div class="px-40 flex flex-1 justify-center py-5">
  <div class="layout-content-container flex flex-col w-[512px] max-w-[512px] py-5 max-w-[960px] flex-1">
    <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Create an account</h2>
    
    @if ($errors->any())
      <div class="mx-4 mb-4 p-4 bg-red-600/20 border border-red-600/50 rounded-xl">
        <ul class="text-red-300 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
        <label class="flex flex-col min-w-40 flex-1">
          <p class="text-white text-base font-medium leading-normal pb-2">Username</p>
          <input
            name="username"
            placeholder="Enter your username"
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
            placeholder="Enter your password"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            required
          />
        </label>
      </div>
      <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
        <label class="flex flex-col min-w-40 flex-1">
          <p class="text-white text-base font-medium leading-normal pb-2">Confirm Password</p>
          <input
            name="password_confirmation"
            type="password"
            placeholder="Confirm your password"
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
          <span class="truncate">Register</span>
        </button>
      </div>
    </form>
    <p class="text-[#a5b6a0] text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center">
      <a href="{{ route('login') }}" class="underline">Already have an account? Sign in</a>
    </p>
  </div>
</div>
@endsection
