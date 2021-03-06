@extends('layouts.base')

@section('title','Quests')

@section('content')
    @include('tccx.nav')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('tccx.shared.status')
                @include('tccx.shared.errors')
                <div id="quest-list" class="card m-2">
                    <div class="card-header">
                        <div class="row ml-1">
                            <h5><i class="fas fa-list-alt"></i> Quest List</h5>
                            @if(Auth::user()->can('manage_quest'))
                                <a class="btn btn-primary btn-sm d-print-none ml-3" href="/quest/create" role="button">
                                    <i class="fas fa-plus"></i> Create
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="quest-filter" method="get" action="{{route('tccx.quest.quests')}}">
                            <div class="form-row">
                                <div class="col-6 col-md-2 py-1">
                                    <input type="text" name="name" class="form-control" placeholder="Name or code"
                                           value="{{request('name')}}">
                                </div>
                                <div class="col-6 col-md-1 py-1">
                                    <input type="number" min="0" name="group" class="form-control" placeholder="Group"
                                           value="{{request('group')}}">
                                </div>
                                <div class="col-6 col-md-2 py-1">
                                    <select id="input-type" name="type" class="custom-select">
                                        <option value="">All types</option>
                                        @if(isset($types))
                                            @foreach($types as $type)
                                                <option @if($type->id == request('type')) selected
                                                        @endif value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-6 col-md-2 py-1">
                                    <select id="input-time" name="time" class="custom-select">
                                        <option value="">All times</option>
                                        @if(isset($times))
                                            @foreach($times as $timeName => $value)
                                                <option @if($value == request('time',-1)) selected
                                                        @endif value="{{$value}}">{{$timeName}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-6 col-md-2 py-1">
                                    <select id="input-zone" name="zone" class="custom-select">
                                        <option value="">All zones</option>
                                        @if(isset($zones))
                                            @foreach($zones as $zone)
                                                <option @if($zone->id == request('zone')) selected
                                                        @endif value="{{$zone->id}}">{{$zone->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-6 col-md-1 py-1">
                                    <input type="number" min="0" max="4" name="level" class="form-control"
                                           placeholder="Level"
                                           value="{{request('level')}}">
                                </div>
                                <div class="col-6 col-md-1 py-1">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                        <table class="mt-3 table table-responsive table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Time</th>
                                <th scope="col">Group</th>
                                <th scope="col">Location</th>
                                <th scope="col">Level</th>
                                <!--<th scope="col">Instruction</th>-->
                                <th scope="col">Reward</th>
                                <th class="text-nowrap" scope="col">Status</th>
                                @if(Auth::user()->can('manage_quest'))
                                    <th scope="col">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <?php /**@var App\TCCX\Quest\Quest $quest */?>
                            @inject('qc','App\TCCX\Quest\QuestCode')
                            @inject('pd','Parsedown')
                            @foreach($quests as $quest)
                                <tr>
                                    <th scope="row">{{$quest->id}}</th>
                                    <td><a href="/quest/view/{{$qc->generate($quest)}}"
                                           target="_blank">{{$quest->name}} ({{$qc->generate($quest)}})</a></td>
                                    <td>{{optional($quest->quest_type)->code}}</td>
                                    <td>{{[0 => 'N/A',1 => 'M',2 => 'A'][$quest->getOriginal('time')] ?? ''}}</td>
                                    <td>{{$quest->group}}</td>
                                    <td>@if(isset($quest->quest_location))
                                            {{$quest->quest_location->name}}
                                        @else <span
                                                    class="text-muted font-italic">Unspecified</span>
                                        @endif
                                        @if(isset($quest->quest_zone))
                                            ({{$quest->quest_zone->name}})
                                        @endif
                                    </td>
                                    <td>{{ucfirst($quest->difficulty)}}</td>
                                <!--<td>{{str_limit(strip_tags($pd->parse($quest->how_to)),50)}}</td>-->
                                    <td>{{$quest->reward}}</td>
                                    <td>
                                        {{-- If quest has been assigned --}}
                                        @if(!empty($quest->assignedTo()))
                                            @if($quest->isCompleted())
                                                <span class="text-success">Completed on {{$quest->assignedTo()->pivot->completed_at}}
                                                    by {{$quest->assignedTo()->name}}</span>
                                            @else
                                                <span class="text-muted">Assigned to {{$quest->assignedTo()->name ?? 'None'}}
                                                    on {{$quest->assignedTo()->pivot->assigned_at}}</span>
                                            @endif
                                        @endif
                                    </td>
                                    @if(Auth::user()->can('manage_quest'))
                                        <td class="text-nowrap">
                                            <a href="/quest/edit?id={{$quest->id}}&last_page={{request('page',1)}}"
                                               class="btn btn-sm btn-primary" role="button" aria-disabled="true"><i
                                                        class="fas fa-edit"></i> Edit</a>
                                            <a href="" data-toggle="modal" data-target="#quest-delete-modal"
                                               data-quest="{{$quest->id}}"
                                               data-quest-code="{{$qc->generate($quest)}}"
                                               class="btn btn-sm btn-danger" role="button"
                                               aria-disabled="true"><i
                                                        class="fas fa-trash"></i> Delete</a>
                                            @if($quest->assignedTo())
                                                @if(!$quest->isCompleted())
                                                    <a href="" data-toggle="modal" data-target="#quest-finish-modal"
                                                       data-quest="{{$quest->id}}"
                                                       data-quest-code="{{$qc->generate($quest)}}"
                                                       class="btn btn-sm btn-success"
                                                       role="button"
                                                    ><i class="fas fa-paper-plane"></i> Finish</a>
                                                @endif
                                            @else
                                                <a href="" data-toggle="modal" data-target="#quest-assign-modal"
                                                   data-quest="{{$quest->id}}"
                                                   data-quest-code="{{$qc->generate($quest)}}"
                                                   data-quest-group="{{$quest->group}}"
                                                   class="btn btn-sm btn-info quest-assign" role="button"
                                                ><i class="fas fa-file"></i> Assign</a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row d-print-none">
                            <div class="col-md-6 offset-md-3 py-3">
                                {{ $quests->appends($_GET)->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                        <!-- delete modal -->
                        <div class="modal fade" id="quest-delete-modal" tabindex="-1" role="dialog"
                             aria-labelledby="quest-delete-message" aria-hidden="true">
                            <div id="dialog-quest-delete" class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quest-delete-message">Delete Quest (<span
                                                    id="input-quest-code"></span>)?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger">This will permanently delete this quest and can't be
                                            undone.</p>
                                        <form id="form-delete-quest" method="post" action="/quest/delete">
                                            {{csrf_field()}}
                                            <input type="hidden" id="input-delete-quest" name="quest-id" value="">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                                onclick="$('form#form-delete-quest').submit()">Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- assign modal -->
                        <div class="modal fade" id="quest-assign-modal" tabindex="-1" role="dialog"
                             aria-labelledby="quest-finish-message" aria-hidden="true">
                            <div id="dialog-quest-assign" class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quest-finish-message">Assign Quest
                                            (<span id="input-quest-code"></span>)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form-assign-quest" method="post" action="/quest/assign">
                                            {{csrf_field()}}
                                            <input type="hidden" id="input-assign-quest" name="quest-id" value="">
                                            <select id="select-team" name="selected-team" class="form-control">
                                                @foreach($teams as $team)
                                                    <option data-group="{{$team->tracking->assigned_group ?? ''}}"
                                                            value="{{$team->id}}">
                                                        {{$team->name}}@if(optional($team->tracking)->assigned_group)
                                                            (Group no.{{$team->tracking->assigned_group}})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label id="quest-assign-info" for="select-team" class="text-info">Please
                                                select a team</label>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                                        </button>
                                        <button type="button" class="btn btn-info"
                                                onclick="$('form#form-assign-quest').submit()">Assign
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Finish -->
                        <div class="modal fade" id="quest-finish-modal" tabindex="-1" role="dialog"
                             aria-labelledby="quest-finish-message" aria-hidden="true">
                            <div id="dialog-quest-finish" class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quest-finish-message">Finish Quest (<span
                                                    id="input-quest-code"></span>)</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-info">Are you sure?</p>
                                        <form id="form-finish-quest" method="post" action="/quest/finish">
                                            {{csrf_field()}}
                                            <input type="hidden" id="input-finish-quest" name="quest-id" value="">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                                        </button>
                                        <button type="button" class="btn btn-success"
                                                onclick="$('form#form-finish-quest').submit()">Finish
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection