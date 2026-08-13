<?php

namespace app\components;

/**
 * Helper class for menu formatting and manipulation.
 * Provides reusable callbacks for MenuHelper operations.
 */
class MenuHelper
{
    /**
     * Get the menu formatting callback for MenuHelper::getAssignedMenu()
     * Converts raw menu data into formatted array structure.
     *
     * @return callable Menu formatter callback
     */
    public static function getMenuFormatter(): callable
    {
        return static function ($menu) {
            $data = eval($menu['data']);

            $menuItem = [
                'label'   => $menu['name'],
                'url'     => empty($menu['route']) ? '#' : [$menu['route']],
                'options' => empty($data) ? [] : $data,
            ];

            if (!empty($menu['children'])) {
                $menuItem['items'] = $menu['children'];
            }

            return $menuItem;
        };
    }


    /**
     * Map menu items to their corresponding parents or URLs.
     * Converts a nested menu structure into a flattened associative array where
     * parent labels are keys and their child items are recursively mapped.
     *
     * @param array $menuItems Array of menu items, where each menu item may have 'url', 'label', and 'items' keys.
     * @return array Associative array where keys are parent labels or URLs, and values are either child arrays or labels.
     */
    public static function mapMenuItemsWithParents($menuItems): array
    {
        $result = [];

        foreach ($menuItems as $item) {
            // Checking if the 'url' key exists and it's not empty
            $url = is_array($item['url']) && !empty($item['url'][0]) ? $item['url'][0] : $item['url'];

            // If the item is a parent (url contains `#`), recursively add its children
            if (str_starts_with($url, '#') && !empty($item['items'])) {
                // Parent menu item with nested children
                $result[$item['label']] = self::mapMenuItemsWithParents($item['items']);
            } else {
                // Flipping the key and value
                $result[$url] = $item['label'];
            }
        }

        return $result;
    }
}
