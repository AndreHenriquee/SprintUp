<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
    @foreach ($teamsAndSquads as $team)
    <li>
        <h6 class="dropdown-header">{{$team['nome']}}</h6>
    </li>
    @foreach ($team['squads'] as $squadId => $squadName)
    <li><a class="dropdown-item {{$sessionParams['squad_id'] == $squadId ? 'active' : ''}}" href="#" wire:click="changeSquad({{(int) $squadId}})">{{$squadName}}</a></li>
    @endforeach
    @endforeach
</ul>