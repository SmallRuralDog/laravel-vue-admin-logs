<?php


namespace SmallRuralDog\LaravelVueAdminLogs\Components;


use SmallRuralDog\Admin\Components\Component;

class LogViewerShow extends Component
{
    protected $componentName = "SmallRuralDogLogViewerShow";

    protected $date;

    protected $routers;

    /**
     * LogViewerShow constructor.
     * @param $date
     */
    public function __construct($date)
    {
        $this->date = $date;

        $this->routers = [
            'get_chart_data' => route('log_view_get_chart_data'),
            'get_list_logs' => route('log_view_get_list_logs'),
            'download' => route('log_view_download', ['date' => '##date##']),
            'get' => route('log_view_get', ['date' => $this->date, 'level' => '##level##']),
            'delete' => route('log_view_delete'),
        ];
    }


    public static function make($date)
    {
        return new LogViewerShow($date);
    }

}
