<?php

declare(strict_types=1);

/** @var yii\web\View $this */


use yii\helpers\Html;
use yii\widgets\Menu;

$currentRoute = $this->context instanceof \yii\base\Controller
    ? $this->context->getRoute()
    : (Yii::$app->controller?->getRoute() ?? '');
$isMenuItemActive = static function (array $item) use ($currentRoute): bool {
    if (!isset($item['url']) || !is_array($item['url']) || empty($item['url'][0])) {
        return false;
    }

    $menuRoute = $item['url'][0];

    // Handle routes starting with /
    if (strncmp($menuRoute, '/', 1) === 0) {
        $menuRoute = substr($menuRoute, 1);
    }

    // Parse routes into parts
    $menuRouteParts = array_filter(explode('/', $menuRoute));
    $currentRouteParts = array_filter(explode('/', $currentRoute));

    // Determine comparison level based on the number of route parts
    // 3+ parts: module/controller/action → compare module and controller (parts 0-1)
    // 2 parts: controller/action → compare controller only (parts 0)
    if (count($menuRouteParts) >= 3) {
        // Module-based route: /module/controller/action
        $menuPart0 = $menuRouteParts[0];
        $menuPart1 = $menuRouteParts[1];
        $currentPart0 = $currentRouteParts[0] ?? '';
        $currentPart1 = $currentRouteParts[1] ?? '';
        return $menuPart0 === $currentPart0 && $menuPart1 === $currentPart1;
    } else {
        // App-level route: /controller/action
        // Compare only controller (first part)
        $menuController = $menuRouteParts[0] ?? '';
        $currentController = $currentRouteParts[0] ?? '';
        return !empty($menuController) && $menuController === $currentController;
    }
};
$formatItems = static function (array $menuItems) use (&$formatItems, $isMenuItemActive): array {
    $formattedItems = [];

    foreach ($menuItems as $item) {
        $metaOptions = is_array($item['options'] ?? null) ? $item['options'] : [];
        $hasChildren = !empty($item['items']) && is_array($item['items']);

        $icon = (string) ($metaOptions['icon'] ?? 'play');
        $icon = strtolower(trim($icon));
        $icon = preg_replace('/[^a-z0-9-]/', '', $icon) ?: 'play';

        $formattedItem = $item;
        $label = sprintf(
            '<i class="bi bi-%s me-2"></i><span class="dockify-sidebar-text">%s</span>',
            $icon,
            Html::encode((string) ($item['label'] ?? ''))
        );
        if ($hasChildren) {
            $label .= '<i class="bi bi-chevron-right dockify-menu-chevron ms-auto" aria-hidden="true"></i>';
        }
        $formattedItem['label'] = $label;

        $itemClasses = ['dockify-sidebar-item'];
        if ($hasChildren) {
            $itemClasses[] = 'has-children';
        }

        $isActive = $isMenuItemActive($item);
        if ($isActive) {
            $itemClasses[] = 'is-active';
            if ($hasChildren) {
                $itemClasses[] = 'is-open';
            }
        }

        $formattedItem['options'] = ['class' => implode(' ', $itemClasses)];
        $formattedItem['active'] = $isActive;

        if ($hasChildren) {
            $formattedItem['items'] = $formatItems($item['items']);
            $formattedItem['url'] = $formattedItem['url'] ?? '#';

            // Check if any child is active, if so, add is-open class to parent
            $hasActiveChild = array_reduce($formattedItem['items'], static function ($carry, $child) {
                return $carry || (!empty($child['active']));
            }, false);

            if ($hasActiveChild && !$isActive) {
                // Add is-open to parent's classes if the child is active but the parent is not
                $currentClasses = explode(' ', $formattedItem['options']['class']);
                if (!in_array('is-open', $currentClasses)) {
                    $currentClasses[] = 'is-open';
                    $formattedItem['options']['class'] = implode(' ', $currentClasses);
                }
            }
        }

        $formattedItems[] = $formattedItem;
    }

    return $formattedItems;
};

$items = $formatItems($this->params['menuSidebarItems']);

$this->registerJs(
    <<<'JS'
(() => {
    const body = document.body;
    const sidebar = document.getElementById('dockify-sidebar');
    const collapseToggle = document.getElementById('dockify-sidebar-collapse-toggle');
    const storageKey = 'dockify.sidebar.collapsed';
    if (!sidebar) return;

    const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;
    const applyCollapsedState = (collapsed) => {
        body.classList.toggle('dockify-sidebar-collapsed', collapsed && isDesktop());
        if (collapseToggle) {
            collapseToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        }
    };

    const storedCollapsed = localStorage.getItem(storageKey) === '1';
    applyCollapsedState(storedCollapsed);

    if (collapseToggle) {
        collapseToggle.addEventListener('click', () => {
            const collapsed = !body.classList.contains('dockify-sidebar-collapsed');
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
            applyCollapsedState(collapsed);
        });
    }

    window.addEventListener('resize', () => {
        applyCollapsedState(localStorage.getItem(storageKey) === '1');
    });

    const parentItems = sidebar.querySelectorAll('.dockify-sidebar-item.has-children');

    const syncSubmenu = (item) => {
        const trigger = item.querySelector(':scope > .dockify-sidebar-link');
        const submenu = item.querySelector(':scope > .dockify-sidebar-submenu');
        const isOpen = item.classList.contains('is-open');

        if (trigger) {
            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        if (!submenu) {
            return;
        }

        submenu.style.maxHeight = isOpen ? `${submenu.scrollHeight}px` : '0px';
    };

    parentItems.forEach((item) => {
        const trigger = item.querySelector(':scope > .dockify-sidebar-link');
        if (!trigger) return;

        syncSubmenu(item);

        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            item.classList.toggle('is-open');
            syncSubmenu(item);
        });
    });

    window.addEventListener('resize', () => {
        parentItems.forEach(syncSubmenu);
    });
})();
JS,
);

?>

<aside id="dockify-sidebar" class="dockify-sidebar" aria-label="Primary navigation">
    <div class="dockify-sidebar-brand">
        <button
            id="dockify-sidebar-collapse-toggle"
            class="dockify-icon-button d-none d-lg-inline-flex"
            type="button"
            aria-label="Toggle sidebar"
            aria-expanded="true"
        >
            <i class="bi bi-list"></i>
        </button>
        <a class="dockify-brand-link" href="<?= Yii::$app->homeUrl ?>">
            <span class="dockify-brand-logo">
                <!--<i class="bi bi-grid-1x2-fill"></i>-->
                <?= Yii::$app->params['appIcon'] ?>
            </span>
            <span><?= Html::encode(Yii::$app->name) ?></span>
        </a>
    </div>

    <nav class="dockify-sidebar-nav" aria-label="Sidebar menu">
        <?php try {
            echo Menu::widget(
                [
                    'options'         => ['class' => 'dockify-sidebar-menu'],
                    'items'           => $items,
                    'encodeLabels'    => false,
                    'activateParents' => true,
                    'activateItems'   => true,
                    'activeCssClass'  => 'is-active',
                    'submenuTemplate' => "\n<ul class=\"dockify-sidebar-submenu\">\n{items}\n</ul>\n",
                    /** the token `{url}` will be replaced with the corresponding link URL; */
                    'linkTemplate'    => '<a href="{url}" class="dockify-sidebar-link">{label}</a>',
                ]);
        } catch (Throwable $e) {
            echo '<div class="alert alert-danger" role="alert">Error rendering menu: ' . Html::encode($e->getMessage()) . '</div>';
        }
        ?>
    </nav>

    <div class="dockify-sidebar-footer">
        <!--        <a class="dockify-sidebar-link" href="--><?php //= Yii::$app->homeUrl ?><!--">-->
        <!--            <i class="bi bi-question-circle me-2"></i>-->
        <!--            <span class="dockify-sidebar-footer-label">Support</span>-->
        <!--        </a>-->
        <?= Html::a(
            '<i class="bi bi-box-arrow-right me-2"></i><span class="dockify-sidebar-footer-label">Sign Out</span>',
            ['/site/logout'],
            [
                'class'       => 'dockify-sidebar-link',
                'data-method' => 'post',
            ],
        ) ?>
    </div>
</aside>
<button class="dockify-sidebar-backdrop" type="button" aria-label="Close sidebar"></button>
