<ul class="sidebar-menu">
    {{-- <li class="header">HEADER</li> --}}
@foreach ($items as $item)
    <li class="{{ isset($item->attributes['class']) ? 'active' : '' }}{{ $item->hasChildren() ? ' treeview' : '' }}">
        <a href="{{ $item->url() }}">
            <i class="fa fa-{{ menu_icon($item->title) }}"></i> <span>{{ trans($item->title) }}</span>
            @if ($item->hasChildren())
            <i class="fa fa-angle-left pull-right"></i>
            @endif
        </a>
        @if ($item->hasChildren())
        <ul class="treeview-menu">
        @foreach ($item->children() as $child)
            <li>
                <a href="{{ $child->url() }}"><i class="fa fa-angle-double-right"></i> {{ trans($child->title) }}</a>
            </li>
        @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
