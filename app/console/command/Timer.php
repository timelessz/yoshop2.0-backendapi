<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\console\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Event;
use Workerman\Worker;

/**
 * 定时器 (Workerman)
 * 用于执行系统里的定时任务, 例如自动确认收货
 * 使用方法: 打开命令行 - 执行命令: php think timer start
 * Class Timer
 * @package app\common\command
 */
class Timer extends Command
{
    // 定时器句柄/ID
    protected $timer;

    // 时间间隔 (单位: 秒, 默认5秒)
    protected $interval = 1;

    protected function configure()
    {
        // 指令配置
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->addOption('i', null, Option::VALUE_OPTIONAL, '多长时间执行一次')
            ->setDescription('start/stop/restart 定时任务');
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;

        if ($input->hasOption('i'))
            $this->interval = floatval($input->getOption('i'));

        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }

    /**
     * 创建定时器
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        // 创建定时器任务
        $worker = new Worker();
        $worker->onWorkerStart = [$this, 'start'];
        $worker->runAll();
    }

    /**
     * 定时器执行的内容
     * @return false|int
     */
    public function start()
    {
        // 每隔n秒执行一次
        return $this->timer = \Workerman\Lib\Timer::add($this->interval, function () use (&$task) {
            try {
                // 这里执行系统预设的定时任务事件
                Event::trigger('StoreTask');
            } catch (\Throwable $e) {
                echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
                $this->stop();
            }
        });
    }

    /**
     * 停止/删除定时器
     * @return bool
     */
    public function stop()
    {
        return \Workerman\Lib\Timer::del($this->timer);
    }

}