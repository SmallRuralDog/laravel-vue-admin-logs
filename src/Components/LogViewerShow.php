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
            'get_chart_data' => route('log_view_get_chart_data',[],false),
            'get_list_logs' => route('log_view_get_list_logs',[],false),
            'download' => route('log_view_download', ['date' => '##date##'],false),
            'get' => route('log_view_get', ['date' => $this->date, 'level' => '##level##'],false),
            'delete' => route('log_view_delete',[],false),
        ];
    }


    public static function make($date)
    {
        return new LogViewerShow($date);
    }

}
