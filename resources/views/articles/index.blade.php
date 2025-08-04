@extends('layouts.app')

@section('title', 'Tech Insights - Home')

@section('content')
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#2d372a] px-10 py-3">
  <div class="flex items-center gap-4 text-white">
    <div class="size-4">
      <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor"></path>
      </svg>
    </div>
    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Tech Insights</h2>
  </div>
  <div class="flex flex-1 justify-end gap-8">
    <div class="flex items-center gap-9">
    </div>
    @auth
      @if(auth()->user()->is_admin)
        <a href="{{ route('admin.index') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#53d22c] text-[#131712] text-sm font-bold leading-normal tracking-[0.015em]">
          <span class="truncate">Admin</span>
        </a>
      @endif
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
          <span class="truncate">Logout</span>
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
        <span class="truncate">Login</span>
      </a>
    @endauth
    <div
      class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
      style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDcl3j_ZbxF4gAJNzwC0UpQXJiKUJ7oA_RPdFFqEH9gs6EdY-ccnUAxFi8Okk-EIeD9VZ-V9TGsit34xf5ZVOfgwcoxzD7volKqNscHJW0oUQFayWo9NWND2fGMd5KtOa4WmpG8xX5hOaZVCWOP7DumWIHWwlqrVK4RmlZdK9B12Y2jcqlUGEmX-iZzngJUMO7RhZWE1HqVdjXVdXJ9i7Va8RWch2i3r2GqH2xcq5YNlvizfM8veBimGmgL0FFEBJtNvlBwKFeXyZc");'
    ></div>
  </div>
</header>
<div class="px-40 flex flex-1 justify-center py-5">
  <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
    <div class="flex flex-wrap justify-between gap-3 p-4">
      <p class="text-white tracking-light text-[32px] font-bold leading-tight min-w-72">Latest Articles</p>
    </div>
    
    @forelse($articles as $article)
    <div class="p-4">
      <div class="flex items-stretch justify-between gap-4 rounded-xl">
        <div class="flex flex-[2_2_0px] flex-col gap-4">
          <div class="flex flex-col gap-1">
            <p class="text-[#a5b6a0] text-sm font-normal leading-normal">{{ $article->category }}</p>
            <p class="text-white text-base font-bold leading-tight">{{ $article->title }}</p>
            <p class="text-[#a5b6a0] text-sm font-normal leading-normal">
              {{ Str::limit($article->content, 150) }}
            </p>
          </div>
          <a href="{{ route('articles.show', $article) }}"
            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-8 px-4 flex-row-reverse bg-[#2d372a] text-white text-sm font-medium leading-normal w-fit"
          >
            <span class="truncate">Read More</span>
          </a>
        </div>
        @if($article->image_url)
        <div
          class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex-1"
          style='background-image: url("{{ $article->image_url }}");'
        ></div>
        @endif
      </div>
    </div>
    @empty
    <div class="p-4">
      <p class="text-[#a5b6a0] text-center">No articles found. Please check back later!</p>
    </div>
    @endforelse
  </div>
</div>
@endsection
