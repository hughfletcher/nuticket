<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    <ul class="nav navbar-nav">
        @foreach ($items as $item)
        <li class="{{ isset($item->attributes['class']) ? 'active' : '' }}{{ $item->hasChildren() ? ' dropdown' : '' }}">
            <a href="{{ $item->url() }}"@if($item->hasChildren()) class="dropdown-toggle" data-toggle="dropdown"@endif >{{ trans($item->title) }}
                @if ($item->hasChildren())
                <span class="caret"></span>
                @endif
            </a>
            @if ($item->hasChildren())
            <ul class="dropdown-menu" role="menu">
            @foreach ($item->children() as $child)
                <li>
                    <a href="{{ $child->url() }}">{{ trans($child->title) }}</a>
                </li>
            @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
</div>