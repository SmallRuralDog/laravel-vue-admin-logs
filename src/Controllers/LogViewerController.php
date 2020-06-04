<?php

namespace SmallRuralDog\LaravelVueAdminLogs\Controllers;

use Arcanedev\LogViewer\Contracts\LogViewer as LogViewerContract;
use Arcanedev\LogViewer\Exceptions\LogNotFoundException;
use Arcanedev\LogViewer\Tables\StatsTable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Layout\Content;
use SmallRuralDog\LaravelVueAdminLogs\Components\LogViewer;
use SmallRuralDog\LaravelVueAdminLogs\Components\LogViewerShow;


class LogViewerController extends AdminController
{
    /**
     * @var LogViewerContract
     */
    private $logViewer;
    /** @var int */
    protected $perPage = 20;

    /**
     * @param LogViewerContract $logViewer
     */
    public function __construct(LogViewerContract $logViewer)
    {
        $this->logViewer = $logViewer;
    }

    public function index(Content $content)
    {
        return $content->body(LogViewer::make());
    }

    public function show($log_view,Content $content){
        return $content->body(LogViewerShow::make($log_view));
    }

    public function getChartData()
    {
        $stats = $this->logViewer->statsTable();
        $chartData = $this->prepareChartData($stats);
        $percents = $this->calcPercentages($stats->footer(), $stats->header());

        return response()->json([
            'chartData' => $chartData,
            'percents' => $percents
        ], 200);
    }

    public function getListLogs(Request $request)
    {
        $stats = $this->logViewer->statsTable();
        $headers = $stats->header();
        $rows = $this->paginate($stats->rows(), $request);

        return response()->json([
            'headers' => $headers,
            'rows' => $rows
        ]);
    }

    public function showByLevel($date, $level)
    {
        $log = $this->getLogOrFail($date);
        $levels = $this->logViewer->levelsNames();
        $entries = $this->logViewer->entries($date, $level)->paginate($this->perPage);
        $menus = $log->menu();

        return response()->json([
            'log' => $log,
            'info' => [
                'path' => $log->getPath(),
                'size' => $log->size(),
                'entries' => $entries->total(),
                'created_at' => $log->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $log->updatedAt()->format('Y-m-d H:i:s')
            ],
            'levels' => $levels,
            'level' => $level,
            'entries' => $entries,
            'menus' => $menus
        ], 200);
    }

    public function download($date)
    {
        return $this->logViewer->download($date);
    }

    public function delete(Request $request)
    {
        $date = $request->get('date');
        return response()->json([
            'result' => $this->logViewer->delete($date) ? 'success' : 'error'
        ]);
    }

    protected function prepareChartData(StatsTable $stats)
    {
        $totals = $stats->totals()->all();

        return [
            'labels' => Arr::pluck($totals, 'label'),
            'datasets' => [
                [
                    'data' => Arr::pluck($totals, 'value'),
                    'backgroundColor' => Arr::pluck($totals, 'color'),
                    'hoverBackgroundColor' => Arr::pluck($totals, 'highlight'),
                ],
            ],
        ];
    }

    protected function calcPercentages(array $total, array $names)
    {
        $percents = [];
        $all = Arr::get($total, 'all');

        foreach ($total as $level => $count) {
            $percents[$level] = [
                'name' => $names[$level],
                'count' => $count,
                'percent' => $all ? round(($count / $all) * 100, 2) : 0,
                'backgroundColor' => config('log-viewer.colors.levels.' . $level)
            ];
        }

        return $percents;
    }

    protected function paginate(array $data, Request $request)
    {
        $data = new Collection($data);
        $page = $request->get('page', 1);
        $path = $request->url();

        return new LengthAwarePaginator(
            $data->forPage($page, $this->perPage),
            $data->count(),
            $this->perPage,
            $page,
            compact('path')
        );
    }

    protected function getLogOrFail($date)
    {
        $log = null;

        try {
            $log = $this->logViewer->get($date);
        } catch (LogNotFoundException $e) {
            abort(404, $e->getMessage());
        }

        return $log;
    }

}
