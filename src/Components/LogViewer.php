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
            'get_chart_data' => route('log_view_get_chart_data'),
            'get_list_logs' => route('log_view_get_list_logs'),
            'download' => route('log_view_download', ['date' => '##date##']),
            'get' => route('log_view_get', ['date' => '##date##', 'level' => '##level##']),
            'delete' => route('log_view_delete'),
        ];
    }


    public static function make()
    {
        return new LogViewer();
    }

}
