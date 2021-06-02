@component('mail::message')
# {{ __('Hello')}}, @if($action_to->name!='No Name'){{ $action_to->name }}@endif

{{ __('Your')}}  <b> {{ $action_title->name }} {{('is')}} <b> {{ $read_status->name }}</b> {{ __('by')}} {{ $action_performer->name }}

@component('mail::button', ['url' => route('projects.show',[$project->workspaceData->slug,$project->id])])
{{ __('Open Project')}}
@endcomponent

{{ __('Thanks')}},<br>
{{ config('app.name') }}
@endcomponent
