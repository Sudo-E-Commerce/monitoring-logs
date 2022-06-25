<?php

namespace Sudo\MonitoringLog\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class SudoMonitoringLogServiceProvider extends ServiceProvider
{
    /**
     * Register config file here
     * alias => path
     */
    private $configFile = [
    ];

    /**
     * Register commands file here
     * alias => path
     */
    protected $commands = [

    ];

    /**
     * Register middleare file here
     * name => middleware
     */
    protected $middleare = [

    ];

	/**
     * Register bindings in the container.
     */
    public function register()
    {
        // Đăng ký config cho từng Module
        $this->mergeConfig();
        // boot commands
        $this->commands($this->commands);
    }

	public function boot()
	{
        Schema::defaultStringLength(191);

		$this->registerModule();

        $this->publish();

        $this->registerMiddleware();
	}

	private function registerModule() {
		$modulePath = __DIR__.'/../../';
        $moduleName = 'SudoMonitoringLog';
	    // boot all helpers
        if (File::exists($modulePath . "helpers")) {
            // get all file in Helpers Folder
            $helper_dir = File::allFiles($modulePath . "helpers");
            // foreach to require file
            foreach ($helper_dir as $key => $value) {
                $file = $value->getPathName();
                require_once $file;
            }
        }
	}

    /*
    * publish dự án ra ngoài
    * publish config File
    * publish assets File
    */
    public function publish()
    {
        if ($this->app->runningInConsole()) {
            // __DIR__.'/../../resources/assets' => public_path('assets'),
            $config = [
                __DIR__.'/../../config/SudoMonitoringLog.php' => config_path('SudoMonitoringLog.php'),
            ];

            // Chạy riêng
            $this->publishes($config, 'sudo/monitoring_logs');
        }
    }


    /*
    * Đăng ký config cho từng Module
    * $this->configFile
    */
    public function mergeConfig() {
        foreach ($this->configFile as $alias => $path) {
            // $config = $this->app['config']->get($alias, []);
            // $this->app['config']->set($alias, $this->mergeArrayConfigs(require __DIR__ . "/../../config/" . $path, $config));
            $this->mergeConfigFrom(__DIR__ . "/../../config/" . $path, $alias);
        }
    }

    /**
     * Merge config để lấy ra mảng chung
     * Ưu tiên lấy config ở app
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    // protected function mergeArrayConfigs(array $original, array $merging)
    // {
    //     $array = array_merge($original, $merging);
    //     foreach ($original as $key => $value) {
    //         if (! is_array($value)) { continue; }
    //         if (! \Arr::exists($merging, $key)) { continue; }
    //         if (is_numeric($key)) { continue; }
    //         $array[$key] = $this->mergeArrayConfigs($merging[$key], $value);
    //     }
    //     return $array;
    // }


    /**
     * Đăng ký Middleare
     */
    public function registerMiddleware()
    {
        foreach ($this->middleare as $key => $value) {
            $this->app['router']->pushMiddlewareToGroup($key, $value);
        }
    }

}
