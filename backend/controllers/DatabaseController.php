<?php

namespace backend\controllers;

use common\backup\Backup;
use Yii;

/**
 * Class DatabaseController
 *
 * @package \backend\controllers
 */
class DatabaseController extends BaseController
{
    protected $dbpath;

    public function init()
    {
        parent::init();
        $this->dbpath = realpath(Yii::getAlias('@base') . Yii::$app->params['web']['DATA_BACKUP_PATH']);
    }

    /**
     * @param string $type
     * @return string
     * 数据库备份/还原列表
     */
    public function actionIndex($type = 'export')
    {
        $list = [];
        switch ($type) {
            case 'import':
                // 列出备份文件列表
                $path = $this->dbpath;
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path, $flag);

                $list = array();
                foreach ($glob AS $name => $file) {
                    if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}-{$name[4]}-{$name[5]}";
                        $part = $name[6];

                        if (isset($list["{$date} {$time}"])) {
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time'] = strtotime("{$date}{$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                break;
            case 'export':
                $list = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
                $list = array_map('array_change_key_case', $list);
                break;
            default:
                $this->error('参数错误！');
                break;
        }

        return $this->render($type, ['list' => $list]);
    }

    /**
     * @param null $tables
     * 优化表
     */
    public function actionOptimize($tables = null)
    {
        $tables = $tables ? $tables : Yii::$app->request->post('tables');
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`")->execute();
                if ($list) {
                    $this->success('数据表优化完成！');
                } else {
                    $this->error('数据表优化出错请重试！');
                }
            } else {
                $list = Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`")->execute();
                if ($list) {
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error('请指定要优化的表！');
        }
    }

    /**
     * @param null $tables
     * 修复表
     */
    public function actionRepair($tables = null)
    {
        $tables = $tables ? $tables : Yii::$app->request->post('tables');
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->execute();
                if ($list) {
                    $this->success('数据表修复完成！');
                } else {
                    $this->error('数据表修复出错请重试！');
                }
            } else {
                $list = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->execute();
                if ($list) {
                    $this->success("数据表'{$tables}'修复完成！");
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error('请指定要修复的表！');
        }
    }

    /**
     * @param int $time
     * 删除备份文件
     */
    public function actionDel($time = 0)
    {
        if ($time) {
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = $this->dbpath . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if (count(glob($path))) {
                $this->success('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 备份数据库
     */
    public function actionExport()
    {
        $time = time();
        $tables = Yii::$app->request->param('tables');
        $id = Yii::$app->request->param('id', 0);
        $start = Yii::$app->request->param('start', 0);

        if (Yii::$app->request->isPost && !empty($tables) && is_array($tables)) {
            // 读取备份设置
            $config = array(
                'path' => $this->dbpath . DIRECTORY_SEPARATOR,
                'part' => 20971520,
                'compress'  => 1,
                'level'     => 9,
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
                $this->error('检测到有一个备份的任务正在执行，请稍后再试！');
            } else {
                file_put_contents($lock, $time);
            }

            // 检查备份目录是否可写
            is_writable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            Yii::$app->session->set('backup_config', $config);

            // 生成备份文件信息
            $file = array(
                'name'  => date('Ymd-His', $time),
                'part'  => 1,
            );
            Yii::$app->session->set('backup_file', $file);

            // 缓存要备份的表
            Yii::$app->session->set('backup_tables', $tables);

            // 创建备份文件
            $Database = new Backup($file, $config);
            if (false !== $Database->create()) {
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                $this->error('初始化失败，备份文件创建失败！');
            }
        } else if (Yii::$app->request->isGet && is_numeric($id) && is_numeric($start)) {
            $tables = Yii::$app->session->get('backup_tables');
            $Database = new Backup(
                Yii::$app->session->get('backup_file'),
                Yii::$app->session->get('backup_config')
            );
            $start = $Database->backup($tables[$id], $start);
            if (false === $start) {
                $this->error('备份出错！');
            } elseif (0 === $start) { // 下一表
                if (isset($tables[ ++$id ])) {
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！', '', array('tab' => $tab));
                } else {
                    // 备份完成，清空缓存
                    unlink(Yii::$app->session->get('backup_config')['path'] . 'backup.lock');
                    Yii::$app->session->remove('backup_tables');
                    Yii::$app->session->remove('backup_file');
                    Yii::$app->session->remove('backup_config');
                    $this->success('备份完成！');
                }
            } else {
                $tab    = array('id' => $id, 'start' => $start[0]);
                $rate   = floor(100 * ($start[0] / $start[1]));
                $this->success("正在备份...{$rate}%", '', array('tab' => $tab));
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 还原数据库
     */
    public function actionImport()
    {
        $time = Yii::$app->request->get('time', 0);
        $part = Yii::$app->request->get('part', null);
        $start= Yii::$app->request->get('start', null);
        if (is_numeric($time) && is_null($part) && is_null($start)) {
            // 获取备份文件信息
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = $this->dbpath . DIRECTORY_SEPARATOR . $name;
            $files= glob($path);
            $list = array();
            foreach ($files as $name) {
                $basename   = basename($name);
                $match      = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz         = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            // 检测文件正确性
            $last = end($list);
            if (count($list) === $last[0]) {
                Yii::$app->session->set('backup_list', $list); // 缓存备份列表
                $this->success('初始化完成！', '', array('part' => 1, 'start' => 0));
            } else {
                $this->error('备份文件可能已经损坏，请检查!');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            $list = Yii::$app->session->get('backup_list');
            $db = new Backup($list[$part], array(
                'path'  => $this->dbpath . DIRECTORY_SEPARATOR,
                'compress'  => $list[$part][2]
            ));
            $start = $db->import($start);

            if (false === $start) {
                $this->error('还原数据出错！');
            } else if (0 === $start) {
                if (isset($list[ ++$part ])) {
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", '', $data);
                } else {
                    Yii::$app->session->remove('backup_list');
                    $this->success('还原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                } else {
                    $data['gz'] = 1;
                    $this->success("正在还原...#{$part}", '', $data);
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }
}