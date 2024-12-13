@if(Session::has('message'))

<div style="" class="session alert alert-{{ Session::get('class', 'info') }}">
    {{ Session::get('message') }}

    @if(Session::has('data'))
        <ul>
            @foreach (Session::get('data') as $e)
                <li>{{ ($e) }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endif


<div style="display: none;" class="alert alert-danger error"></div>
<div style="display: none;" class="alert alert-success success"></div>


