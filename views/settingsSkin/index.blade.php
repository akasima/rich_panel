<form method="post" action="{{route('manage.rich_panel.store')}}">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <div class="xe-form-group xe-form-inline">
        <label>php artisan &nbsp;</label>
        <input class="xe-form-control" name="commandLine" value="" placeholder="Enter artisan execute command with options" style="min-width: 400px;"/>
    </div>

    <button type="submit" class="xe-btn xe-btn-primary" />Send Queue</button>
</form>
