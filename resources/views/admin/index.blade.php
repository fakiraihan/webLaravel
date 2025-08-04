@extends('layouts.app')

@section('title', 'Admin Dashboard - Admin')

@section('content')
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#2d372a] px-10 py-3">
  <div class="flex items-center gap-4 text-white">
    <div class="size-4">
      <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor"></path>
      </svg>
    </div>
    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Admin</h2>
  </div>
  <div class="flex flex-1 justify-end gap-8">
    <div class="flex items-center gap-9">
      <a class="text-white text-sm font-medium leading-normal" href="{{ route('admin.index') }}">Manage Articles</a>
      <a class="text-white text-sm font-medium leading-normal" href="{{ route('home') }}">View Website</a>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.create') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#53d22c] text-[#131712] text-sm font-bold leading-normal tracking-[0.015em]">
        <span class="truncate">Add New Article</span>
      </a>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
          <span class="truncate">Logout</span>
        </button>
      </form>
    </div>
  </div>
</header>
<div class="px-40 flex flex-1 justify-center py-5">
  <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
    <div class="flex flex-wrap justify-between gap-3 p-4">
      <p class="text-white tracking-light text-[32px] font-bold leading-tight min-w-72">All Articles</p>
    </div>

    @if(session('success'))
      <div class="mx-4 mb-4 p-4 bg-green-600/20 border border-green-600/50 rounded-xl">
        <p class="text-green-300 text-sm">{{ session('success') }}</p>
      </div>
    @endif

    <div class="px-4 py-3 @container">
      <div class="flex overflow-hidden rounded-xl border border-[#42513e] bg-[#131712]">
        <table class="flex-1">
          <thead>
            <tr class="bg-[#1f251d]">
              <th class="px-4 py-3 text-left text-white w-[400px] text-sm font-medium leading-normal">Title</th>
              <th class="px-4 py-3 text-left text-white w-[400px] text-sm font-medium leading-normal">Author</th>
              <th class="px-4 py-3 text-left text-white w-[400px] text-sm font-medium leading-normal">Date</th>
              <th class="px-4 py-3 text-left text-white w-60 text-sm font-medium leading-normal">Status</th>
              <th class="px-4 py-3 text-left text-white w-60 text-[#a5b6a0] text-sm font-medium leading-normal">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($articles as $article)
            <tr class="border-t border-t-[#42513e]">
              <td class="h-[72px] px-4 py-2 w-[400px] text-white text-sm font-normal leading-normal">
                {{ $article->title }}
              </td>
              <td class="h-[72px] px-4 py-2 w-[400px] text-[#a5b6a0] text-sm font-normal leading-normal">
                {{ $article->author_name }}
              </td>
              <td class="h-[72px] px-4 py-2 w-[400px] text-[#a5b6a0] text-sm font-normal leading-normal">
                {{ $article->created_at->format('M d, Y') }}
              </td>
              <td class="h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                <span class="px-2 py-1 rounded-full text-xs {{ $article->status === 'published' ? 'bg-green-600/20 text-green-300' : 'bg-yellow-600/20 text-yellow-300' }}">
                  {{ ucfirst($article->status) }}
                </span>
              </td>
              <td class="h-[72px] px-4 py-2 w-60 text-[#a5b6a0] text-sm font-bold leading-normal tracking-[0.015em]">
                <div class="flex gap-2">
                  <a href="{{ route('admin.edit', $article) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                  <form method="POST" action="{{ route('admin.destroy', $article) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr class="border-t border-t-[#42513e]">
              <td colspan="5" class="h-[72px] px-4 py-2 text-center text-[#a5b6a0] text-sm font-normal leading-normal">
                No articles found. <a href="{{ route('admin.create') }}" class="text-[#53d22c] underline">Create your first article</a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
