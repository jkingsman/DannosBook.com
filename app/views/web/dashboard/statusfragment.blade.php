<ul class="list-group">
  <li class="list-group-item">
    {{ $autostatus = Cache::get('automated', false) ? '<span class="label label-success pull-right">Running</span>' : '<span class="label label-default pull-right">Not Running</span>' }}
    Current automation status
  </li>
  <li class="list-group-item">
    {{ Cache::get('status', 'Unknown') }}
  </li>
  <li class="list-group-item">
    <span class="label label-primary pull-right">{{ Cache::get('cachecount', 0) }}</span>
    Raw cache objects awaiting move
  </li>
    <li class="list-group-item">
    <span class="label label-primary pull-right">{{ Cache::get('loadedcount', 0) }}</span>
    Loaded into database
  </li>
  <li class="list-group-item">
    <div class="progress">
	<div class="progress-bar progress-bar-success progress-bar-striped active" style="width: {{ round(((Cache::get('loadedcount', 0) / (Cache::get('cachecount', 0) + Cache::get('loadedcount', 0) + .001 /*div by 0 avoidance */))*100)) }}%%">
	    {{ round(((Cache::get('loadedcount', 0) / (Cache::get('cachecount', 0) + Cache::get('loadedcount', 0) + .001 /*div by 0 avoidance */))*100)) }}% Complete
	</div>
    </div>
  </li>
</ul>
    