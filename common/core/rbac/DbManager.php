<?php

namespace common\core\rbac;

use Yii;
use yii\rbac\Item;
/**
 * Class DbManager
 *
 * @package \common\core\rbac
 */
class DbManager extends \yii\rbac\DbManager
{
    /**
     * @param $name  string  rule名称
     * 当Rule不存在时添加
     * 同时将auth_item添加或更新
     */
    public function saveRule($name)
    {
        if ($rule = $this->getRule($name)) {
            // TODO:更新
        } else {
            $rule = new Rule();
            $rule->name = $name;
            $this->add($rule);
        }

        if ($item = $this->getItem($name)) {
            // TODO: 更新
        } else {
            $item = new Item();
            $item->name = $name;
            $item->type = 2;
            $item->ruleName = $name;
            $this->add($item);
        }
    }

    /**
     * @param $parent   角色name
     * @param $child    权限name
     * 保存角色的权限分配
     */
    public function saveChild($parent, $child)
    {
        $parent = $this->getRole($parent);
        $child  = $this->getItem($child);
        if (!$this->hasChild($parent, $child)) {
            $this->addChild($parent, $child);
        }
    }

    /**
     * @param string $name
     * @param \yii\rbac\Rule $rule
     * @return bool
     * 更新auth_item
     */
    protected function updateRule($name, $rule)
    {
        if ($rule->name !== $name && !$this->supportsCascadeUpdate()) {
            $this->db->createCommand()->update($this->itemTable, [
                'rule_name' => $rule->name,
                'name'      => $rule->name,
            ], [
                'rule_name' => $name
            ])->execute();
        }
        $rule->updatedAt = time();

        $this->db->createCommand()->update($this->ruleTable, [
            'name'  => $rule->name,
            'data'  => serialize($rule),
            'updated_at' => $rule->updatedAt,
        ], ['name' => $name])->execute();

        $this->invalidateCache();

        return true;
    }
}