@extends('layouts.app')

@section('title', 'Edit Article - Blog Admin')

@section('content')
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#2d372a] px-10 py-3">
  <div class="flex items-center gap-4 text-white">
    <div class="size-4">
      <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor"></path>
      </svg>
    </div>
    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">Blog Admin</h2>
  </div>
  <div class="flex flex-1 justify-end gap-8">
    <div class="flex items-center gap-9">
      <a class="text-white text-sm font-medium leading-normal" href="{{ route('admin.index') }}">Manage Articles</a>
      <a class="text-white text-sm font-medium leading-normal" href="{{ route('home') }}">View Website</a>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.index') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
        <span class="truncate">Back to Articles</span>
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
      <p class="text-white tracking-light text-[32px] font-bold leading-tight min-w-72">Edit Article</p>
    </div>

    @if ($errors->any())
      <div class="mx-4 mb-4 p-4 bg-red-600/20 border border-red-600/50 rounded-xl">
        <ul class="text-red-300 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.update', $article) }}" class="p-4">
      @csrf
      @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-white text-sm font-medium mb-2">Title</label>
          <input
            name="title"
            type="text"
            placeholder="Enter article title"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('title', $article->title) }}"
            required
          />
        </div>
        <div>
          <label class="block text-white text-sm font-medium mb-2">Category</label>
          <input
            name="category"
            type="text"
            placeholder="e.g., Technology, Programming"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('category', $article->category) }}"
            required
          />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-white text-sm font-medium mb-2">Author Name</label>
          <input
            name="author_name"
            type="text"
            placeholder="Author name"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('author_name', $article->author_name) }}"
            required
          />
        </div>
        <div>
          <label class="block text-white text-sm font-medium mb-2">Status</label>
          <select
            name="status"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            required
          >
            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-white text-sm font-medium mb-2">Image URL (optional)</label>
          <input
            name="image_url"
            type="url"
            placeholder="https://example.com/image.jpg"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('image_url', $article->image_url) }}"
          />
        </div>
        <div>
          <label class="block text-white text-sm font-medium mb-2">Author Image URL (optional)</label>
          <input
            name="author_image"
            type="url"
            placeholder="https://example.com/author.jpg"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-14 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
            value="{{ old('author_image', $article->author_image) }}"
          />
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-white text-sm font-medium mb-2">Content</label>
        <textarea
          name="content"
          rows="10"
          placeholder="Write your article content here..."
          class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal"
          required
        >{{ old('content', $article->content) }}</textarea>
      </div>

      <div class="flex gap-4">
        <button
          type="submit"
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#53d22c] text-[#131712] text-sm font-bold leading-normal tracking-[0.015em]"
        >
          <span class="truncate">Update Article</span>
        </button>
        <a href="{{ route('admin.index') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
          <span class="truncate">Cancel</span>
        </a>
        <form method="POST" action="{{ route('admin.destroy', $article) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-red-600 text-white text-sm font-bold leading-normal tracking-[0.015em]">
            <span class="truncate">Delete Article</span>
          </button>
        </form>
      </div>
    </form>
  </div>
</div>
@endsection
