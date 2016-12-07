<?php

namespace common\helpers;

/**
 * Class ArrayHelper
 *
 * @package \common\helpers
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     * 把返回的数组转换成Tree
     */
    public static function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list AS $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list AS $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[ $parentId ])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * @param $tree
     * @param string $child
     * @param string $order
     * @param array $list
     * @return array
     * 将list_t_tree的树还原成列表
     */
    public static function tree_to_list($tree, $child = '_child', $order = 'id', &$list = [])
    {
        if (is_array($tree)) {
            $reffer = [];
            foreach ($tree AS $key => $value) {
                $reffer = $value;
                if (isset($reffer[ $child ])) {
                    unset($reffer[$child]);
                    static::tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = static::list_sort_by($list, $order, $sortby = 'asc');
        }
        return $list;
    }

    /**
     * @param $list 查询结果
     * @param $field 排序的字段名
     * @param string $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
     * @return array|bool
     * 对查询结果集进行排序
     */
    public static function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = [];
            foreach ($list AS $i => $data)
            {
                $refer[$i] =& $data[$field];
            }
            switch ($sortby) {
                case 'asc':
                    // 正向排序
                    asort($refer);
                    break;
                case 'desc':
                    // 逆向排序
                    arsort($refer);
                    break;
                case 'nat':
                    // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }
            return $resultSet;
        }
        return false;
    }

    /**
     * @param $tree 树形结构的数组
     * @param string $title 将格式化的字段
     * @param int $level 当前循环层次， 从0开始
     * @return array
     * 递归方式将tree结构转化为表单中的select可使用的格式
     */
    public static function format_tree($tree, $title = 'title', $level = 0)
    {
        static $list =array();
        /* 按层级格式的字符串 */
        $tmp_str=str_repeat("　",$level)."└";
        $level == 0 && $tmp_str = '';

        foreach ($tree as $key => $value) {
            $value[$title] = $tmp_str.$value[$title];
            $arr = $value;
            if (isset($arr['_child'])) unset($arr['_child']);
            $list[] = $arr;
            if (array_key_exists('_child', $value)) {
                static::format_tree($value['_child'], $title, $level+1);
            }
        }
        return $list;
    }

    /**
     * @param $list array 由findAll或->all()生成的数据
     * @param $key string dropDownList的data数据的key
     * @param $value string dropDownList的data数据的value
     * @param string $pk 主键字段名
     * @param string $pid 父id字段名
     * @param int $root 根ID
     * @return array
     * 获取dropDownList的data数据，主要是二级栏目及以上数据，一级栏目可以用ArrayHelper::map()生成
     * 示例：ArrayHelper::listDataLevel(\backend\models\Menu::find()->asArray()->all(), 'id', 'title', 'id', 'pid')
     */
    public static function listDataLevel($list, $key, $value, $pk = 'id', $pid = 'pid', $root = 0)
    {
        if (!is_array($list)) {
            return [];
        }
        $_tmp = $list;
        /* 判断$list是否由findAll生成的数据 */
        if (array_shift($_tmp) instanceof \yii\base\Model) {
            $list = array_map(function($record) {return $record->attributes;},$list);
        }
        unset($_tmp);
        $tree = static::list_to_tree($list,$pk,$pid,'_child',$root);
        return static::map( static::format_tree($tree, $value), $key, $value);
    }
}