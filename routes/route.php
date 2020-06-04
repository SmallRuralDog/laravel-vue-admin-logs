<?php



$router = app('router');

$router->group([
    'prefix' => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
], function ($router) {

});

//
$router->group([
    'prefix' => config('admin.route.api_prefix'),
    'middleware' => config('admin.route.middleware'),
], function ($router) {

    $router->resource('log_view', 'LogViewerController');

    $router->get('log_view_api/get_chart_data', 'LogViewerController@getChartData')->name("log_view_get_chart_data");
    $router->get('log_view_api/get_list_logs', 'LogViewerController@getListLogs')->name("log_view_get_list_logs");
    $router->get('log_view_api/download/{date}', 'LogViewerController@download')->name("log_view_download");
    $router->get('log_view_api/get/{date}/{level}', 'LogViewerController@showByLevel')->name("log_view_get");

    $router->delete('log_view_api/delete', 'LogViewerController@delete')->name("log_view_delete");
});
