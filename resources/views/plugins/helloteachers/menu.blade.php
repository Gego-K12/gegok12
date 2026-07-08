<li class="py-3 px-3 hover:bg-purple-900 {{ Request::segment('2') == 'helloteachers' ? 'active' : '' }}">
    <a href="{{ url('/teacher/helloteachers') }}" class="flex items-center">
        <svg class="w-5 h-5 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.5-6.3 4.5 2.3-7.2-6-4.6h7.6z"/></svg>
        <span class="mx-3 whitespace-no-wrap">Motivation</span>
    </a>
</li>
