@extends('layouts.app')

@section('title', $article->title . ' - Tech Insights')

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
    <label class="flex flex-col min-w-40 !h-10 max-w-64">
      <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
      </div>
    </label>
    <div class="flex gap-2">
      <a href="{{ route('home') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#2d372a] text-white text-sm font-bold leading-normal tracking-[0.015em]">
        <span class="truncate">Dashboard</span>
      </a>
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
    </div>
    <div
      class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
      style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username[0] . ".") }}&background=2d372a&color=fff")'
    ></div>
  </div>
</header>
<div class="px-40 flex flex-1 justify-center py-5">
  <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
    <div class="flex flex-wrap justify-between gap-3 p-4">
      <div class="flex min-w-72 flex-col gap-3">
        <p class="text-white tracking-light text-[32px] font-bold leading-tight">{{ $article->title }}</p>
        <p class="text-[#a5b6a0] text-sm font-normal leading-normal">{{ $article->category }}</p>
      </div>
    </div>
    <div class="flex items-center gap-4 bg-[#131712] px-4 min-h-[72px] py-2">
      <div
        class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-14 w-fit"
        style='background-image: url("{{ $article->author_image ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCipXpga9SNDEogDGipY_9BH0xK1D7qozMM5thKpRLWrGqZUMbE7duAuIHYtUJyCsUqIyCZwmy2f8AQp_GW5_vv9N3YjsURNSN9pOfQE06hvDibOZ5hUt7wiE1F_4NVlGY06aozkONVQ3HGRmNOM8JgTMgmMqmmcZFwQggoEZBZOAzTYG2ICVnthV7p5m61YeeHpaiZa7qtSLp7E9ZbEr0-89W0M3g9NFuVo_js9BdtudbyWQZfIoNlFHI0WxZmKLHDs-1fhCKQBaw' }}");'
      ></div>
      <div class="flex flex-col justify-center">
        <p class="text-white text-base font-medium leading-normal line-clamp-1">{{ $article->author_name }}</p>
        <p class="text-[#a5b6a0] text-sm font-normal leading-normal line-clamp-2">{{ $article->created_at->format('M d, Y') }}</p>
      </div>
    </div>
    @if($article->image_url)
    <div class="@container">
      <div class="@[480px]:px-4 @[480px]:py-3">
        <div
          class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl"
          style='background-image: url("{{ $article->image_url }}");'
        ></div>
      </div>
    </div>
    @endif
    <div class="text-white text-base font-normal leading-normal pb-3 pt-1 px-4">
      {!! nl2br(e($article->content)) !!}
    </div>
    <div class="flex flex-wrap gap-4 px-4 py-2 py-2 justify-between">
      <div class="flex items-center justify-center gap-2 px-3 py-2">
        <div class="text-[#a5b6a0]" data-icon="Heart" data-size="24px" data-weight="regular">
          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
            <path d="M178,32c-20.65,0-38.73,8.88-50,23.89C116.73,40.88,98.65,32,78,32A62.07,62.07,0,0,0,16,94c0,70,103.79,126.66,108.21,129a8,8,0,0,0,7.58,0C136.21,220.66,240,164,240,94A62.07,62.07,0,0,0,178,32ZM128,206.8C109.74,196.16,32,147.69,32,94A46.06,46.06,0,0,1,78,48c19.45,0,35.78,10.36,42.6,27a8,8,0,0,0,14.8,0c6.82-16.67,23.15-27,42.6-27a46.06,46.06,0,0,1,46,46C224,147.61,146.24,196.15,128,206.8Z"></path>
          </svg>
        </div>
      </div>
      <div class="flex items-center justify-center gap-2 px-3 py-2">
        <div class="text-[#a5b6a0]" data-icon="ChatCircle" data-size="24px" data-weight="regular">
          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
            <path d="M128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a87.87,87.87,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216,52.47,178.6a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z"></path>
          </svg>
        </div>
      </div>
      <div class="flex items-center justify-center gap-2 px-3 py-2">
        <div class="text-[#a5b6a0]" data-icon="ArrowsClockwise" data-size="24px" data-weight="regular">
          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
            <path d="M197.67,186.37a8,8,0,0,1,0,11.29C196.58,198.73,170.82,224,128,224c-37.39,0-64.53-22.4-80-39.85V208a8,8,0,0,1-16,0V160a8,8,0,0,1,8-8H88a8,8,0,0,1,0,16H55.44C67.76,183.35,93,208,128,208c36.51,0,58.48-21.42,58.48-21.42A8,8,0,0,1,197.67,186.37ZM216,40a8,8,0,0,0-8,8V72.15C192.53,54.4,165.39,32,128,32,85.18,32,59.42,57.27,58.33,58.34a8,8,0,0,0,11.3,11.32S91.49,48,128,48c35,0,60.24,24.65,72.56,40H168a8,8,0,0,0,0,16h48a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z"></path>
          </svg>
        </div>
      </div>
      <div class="flex items-center justify-center gap-2 px-3 py-2">
        <div class="text-[#a5b6a0]" data-icon="Export" data-size="24px" data-weight="regular">
          <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
            <path d="M237.66,106.35l-80-80A8,8,0,0,0,144,32V72.4c-25.18,2.59-48.5,15.54-67.22,37.37a102.33,102.33,0,0,0-24.64,54.18,8,8,0,0,0,13.65,7.07C76.73,159.14,103.39,148,128,148a8,8,0,0,0,8-8V104a8,8,0,0,0,8-8h8l74.35,74.35a8,8,0,0,0,11.31-11.31Z"></path>
          </svg>
        </div>
      </div>
    </div>
    <h3 class="text-white text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">Comments</h3>
    <div class="px-4 pb-6">
      @foreach($article->comments as $comment)
        <div class="flex items-start gap-3 mb-4">
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 shrink-0" style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($comment->user->username) }}&background=2d372a&color=fff")'></div>
          <div>
            <div class="text-white font-semibold text-sm">{{ $comment->user->username }}</div>
            <div class="text-[#a5b6a0] text-xs mb-1">{{ $comment->created_at->diffForHumans() }}</div>
            <div class="text-white text-base">{!! $comment->content !!}</div>
            <!-- VULN: IDOR - delete link for any comment -->
            <a href="{{ url('/comments/delete/' . $comment->id) }}" class="text-red-400 text-xs ml-2">Delete (no check)</a>
          </div>
        </div>
      @endforeach
      @auth
      <form action="{{ route('comments.store', $article) }}" method="POST" class="flex items-center gap-3 mt-4">
        <!-- CSRF token intentionally removed for research -->
        <input name="content" required maxlength="1000" placeholder="Add a comment..." class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border border-[#42513e] bg-[#1f251d] focus:border-[#42513e] h-12 placeholder:text-[#a5b6a0] p-[15px] text-base font-normal leading-normal" />
        <button type="submit" class="bg-[#53d22c] text-[#131712] font-bold rounded-xl px-4 py-2">Post</button>
      </form>
      @else
      <div class="text-[#a5b6a0] text-sm mt-2">Login to post a comment.</div>
      @endauth
    </div>
  </div>
</div>
@endsection
