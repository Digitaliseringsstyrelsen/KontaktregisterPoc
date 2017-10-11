@extends('layout.adminlte')

@section('page_title', 'Search CPR')

@section('body')
    <form action="#" id="search-cpr">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search_query">
                <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
                <input class="mdl-textfield__input" type="text" id="search_query">
                <label class="mdl-textfield__label" for="sample-expandable">Expandable Input</label>
            </div>
        </div>
    </form>
    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
        <thead>
        <tr>
            <th>CPR/CVR</th>
            <th>Type</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="content-response">
            @if(! app()->environment('production'))
                @foreach (\Api\Models\Contact::inRandomOrder()->limit(20)->get() as $contact)
                    <tr>
                        <td>{{ $contact->identifier }}</td>
                        <td>{{ $contact->type }}</td>
                        <td><a href="/contacts/{{ $contact->identifier }}">Vis</a></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#search-cpr').on('submit', function(event){
            event.preventDefault();
            var val = document.getElementById('search_query').value;
            $.ajax({
                url: 'api/search',
                data: {
                    query: val
                }
            }).done(function(response) {
                var htmlBuilder = '';
                response.data.length > 0 ? response.data.map(function (contact){
                    htmlBuilder += '<tr>' +
                        '<td>' + contact.identifier  + '</td>' +
                        '<td>' + contact.type  + '</td>' +
                        '<td> <a href="/contacts/' + contact.identifier + '">Vis</a> </td>' +
                        '</tr>';
                }) : '<tr><td></td><td>Ingen resultater<td></tr>';
                $("#content-response").html(htmlBuilder);
            });
        });
    });
</script>
@endpush
