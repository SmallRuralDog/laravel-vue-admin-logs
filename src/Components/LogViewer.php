<?php

namespace SmallRuralDog\LaravelVueAdminLogs\Components;

use SmallRuralDog\Admin\Components\Component;

class LogViewer extends Component
{
    protected $componentName = "SmallRuralDogLogViewer";

    protected $routers;

    public function __construct()
    {
        $this->routers = [
            'get_chart_data' => route('log_view_get_chart_data',[],false),
            'get_list_logs' => route('log_view_get_list_logs',[],false),
            'download' => route('log_view_download', ['date' => '##date##'],[],false),
            'get' => route('log_view_get', ['date' => '##date##', 'level' => '##level##'],false),
            'delete' => route('log_view_delete',[],false),
        ];
    }


    public static function make()
    {
        return new LogViewer();
    }

}
