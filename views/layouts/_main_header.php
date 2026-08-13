<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Json;

$menuSearchSource = Json::htmlEncode($this->params['menuSearchTypeAhead'] ?? []);

$this->registerJs(
    <<<JS
(() => {
    const body = document.body;
    const toggle = document.getElementById('dockify-sidebar-toggle');
    const backdrop = document.querySelector('.dockify-sidebar-backdrop');
    const searchToggle = document.getElementById('dockify-search-toggle');
    const searchField = document.getElementById('dockify-search-input');
    const searchContainer = document.getElementById('dockify-topbar-search-container');
    const searchResults = document.getElementById('dockify-search-suggestions');
    const menuSearchData = $menuSearchSource;
    let filteredItems = [];
    let visibleItems = [];
    let activeIndex = -1;

    const flattenMenuSearch = (tree, group = null, collector = []) => {
        Object.entries(tree || {}).forEach(([key, value]) => {
            if (value && typeof value === 'object' && !Array.isArray(value)) {
                flattenMenuSearch(value, key, collector);
                return;
            }

            collector.push({
                route: key,
                label: String(value ?? ''),
                group
            });
        });

        return collector;
    };

    const menuSearchIndex = flattenMenuSearch(menuSearchData);

    const hideSuggestions = () => {
        if (searchResults) {
            searchResults.classList.add('d-none');
            searchResults.innerHTML = '';
        }
        activeIndex = -1;
        visibleItems = [];
    };

    const goToRoute = (route) => {
        if (!route) {
            return;
        }

        window.location.href = route;
    };

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');

    const renderSuggestions = (query) => {
        if (!searchResults) {
            return;
        }

        const needle = String(query || '').trim().toLowerCase();
        if (needle === '') {
            filteredItems = [];
            hideSuggestions();
            return;
        }

        filteredItems = menuSearchIndex.filter((item) =>
            item.label.toLowerCase().includes(needle)
            || item.route.toLowerCase().includes(needle)
            || (item.group && item.group.toLowerCase().includes(needle))
        );

        if (filteredItems.length === 0) {
            hideSuggestions();
            return;
        }

        visibleItems = [];
        const grouped = new Map();
        filteredItems.forEach((item) => {
            const key = item.group || '__ungrouped__';
            if (!grouped.has(key)) {
                grouped.set(key, []);
            }
            grouped.get(key).push(item);
        });

        const html = [];
        grouped.forEach((items, group) => {
            if (group !== '__ungrouped__') {
                html.push('<div class="dockify-search-group">' + escapeHtml(group) + '</div>');
            }

            items.forEach((item) => {
                visibleItems.push(item);
                html.push(
                    '<button type="button" class="dockify-search-item" data-route="' + escapeHtml(item.route) + '" data-label="' + escapeHtml(item.label) + '">' +
                        escapeHtml(item.label) +
                    '</button>'
                );
            });
        });

        searchResults.innerHTML = html.join('');
        searchResults.classList.remove('d-none');
        activeIndex = -1;
    };

    const setActiveSuggestion = (index) => {
        if (!searchResults || visibleItems.length === 0) {
            return;
        }

        const buttons = searchResults.querySelectorAll('.dockify-search-item');
        if (buttons.length === 0) {
            return;
        }

        const boundedIndex = ((index % buttons.length) + buttons.length) % buttons.length;
        activeIndex = boundedIndex;

        buttons.forEach((button, idx) => {
            button.classList.toggle('is-active', idx === activeIndex);
        });

        const activeButton = buttons[activeIndex];
        const activeItem = visibleItems[activeIndex];

        if (searchField && activeItem) {
            searchField.value = activeItem.label;
        }

        if (activeButton instanceof HTMLElement) {
            activeButton.scrollIntoView({block: 'nearest'});
        }
    };

    if (toggle) {
        toggle.addEventListener('click', () => {
            body.classList.toggle('dockify-sidebar-open');
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', () => {
            body.classList.remove('dockify-sidebar-open');
        });
    }

    if (searchToggle && searchContainer) {
        searchToggle.addEventListener('click', (e) => {
            e.preventDefault();
            searchContainer.classList.toggle('dockify-search-expanded');
            if (searchContainer.classList.contains('dockify-search-expanded')) {
                searchField?.focus();
                renderSuggestions(searchField?.value || '');
            } else {
                hideSuggestions();
            }
        });
    }

    document.addEventListener('keyup', (event) => {
        const isSlash = event.key === '/' || event.code === 'Slash';
        const withShortcutKey = (event.ctrlKey || event.metaKey) && isSlash;
        if (!withShortcutKey) {
            return;
        }

        event.preventDefault();

        if (searchContainer) {
            searchContainer.classList.add('dockify-search-expanded');
        }

        if (searchField) {
            searchField.focus();
            searchField.select();
            renderSuggestions(searchField.value);
        }
    });

    if (searchField) {
        searchField.addEventListener('input', () => {
            renderSuggestions(searchField.value);
        });

        searchField.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideSuggestions();
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (visibleItems.length === 0) {
                    renderSuggestions(searchField.value);
                }
                if (visibleItems.length > 0) {
                    setActiveSuggestion(activeIndex + 1);
                }
                return;
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                if (visibleItems.length === 0) {
                    renderSuggestions(searchField.value);
                }
                if (visibleItems.length > 0) {
                    setActiveSuggestion(activeIndex - 1);
                }
                return;
            }

            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();

            if (activeIndex >= 0 && visibleItems[activeIndex]) {
                goToRoute(visibleItems[activeIndex].route);
                return;
            }

            const exact = filteredItems.find((item) => item.label.toLowerCase() === searchField.value.trim().toLowerCase());
            goToRoute((exact || visibleItems[0] || {}).route);
        });

        searchField.addEventListener('blur', () => {
            window.setTimeout(() => {
                hideSuggestions();
                if (!searchField.value.trim()) {
                    searchContainer?.classList.remove('dockify-search-expanded');
                }
            }, 120);
        });
    }

    if (searchResults) {
        searchResults.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const button = target.closest('.dockify-search-item');
            if (!(button instanceof HTMLElement)) {
                return;
            }

            const route = button.dataset.route || '';
            const label = button.dataset.label || '';
            if (searchField && label) {
                searchField.value = label;
            }
            goToRoute(route);
        });
    }

    document.addEventListener('click', (event) => {
        if (!searchContainer || !searchResults) {
            return;
        }

        const target = event.target;
        if (!(target instanceof Node)) {
            return;
        }

        if (!searchContainer.contains(target)) {
            hideSuggestions();
        }
    });
})();
JS,
);

$user = Yii::$app->user->identity;
$username = $user?->username ?? 'Guest';
$initial = strtoupper((string)mb_substr($username, 0, 1));
$photo = $user?->getPhoto();
$nickname = $user?->getNickname();

?>

<header class="dockify-topbar">
    <div class="dockify-topbar-section dockify-topbar-left">
        <button id="dockify-sidebar-toggle" class="dockify-icon-button d-lg-none" type="button"
                aria-label="Open sidebar">
            <i class="bi bi-list"></i>
        </button>
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(
                [
                    'tag'      => 'ol',
                    'options'  => ['class' => 'dockify-breadcrumb list-unstyled mb-0 d-none d-lg-flex'],
                    'homeLink' => [
                        'label' => Html::encode('Home'),
                        'url'   => Yii::$app->homeUrl,
                    ],
                    'links'    => $this->params['breadcrumbs'],
                ],
            ) ?>
        <?php endif ?>
    </div>

    <div class="dockify-topbar-search" id="dockify-topbar-search-container">
        <button id="dockify-search-toggle" class="dockify-search-toggle" type="button" aria-label="Search">
            <i class="bi bi-search"></i>
        </button>
        <input type="search" id="dockify-search-input" placeholder="Search menu ... Ctrl / " aria-label="Search menu">
        <div id="dockify-search-suggestions" class="dockify-search-suggestions d-none" role="listbox"
             aria-label="Menu suggestions"></div>
    </div>

    <div class="dockify-topbar-actions">
        <?= Html::button(
            '<i class="bi bi-moon-stars"></i>',
            [
                'id'         => 'theme-toggle',
                'class'      => 'dockify-icon-button',
                'aria-label' => 'Switch to dark mode',
            ],
        ) ?>
        <button class="dockify-icon-button" type="button" aria-label="Notifications">
            <i class="bi bi-bell"></i>
        </button>
        <?php if (\app\enums\PermissionEnum::isSuperAdmin()): ?>
            <?= Html::a('<i class="bi bi-gear"></i>', ['/settings'], [
                'class'      => 'dockify-icon-button ', // d-none d-md-inline-flex
                'aria-label' => 'Settings',
                'type'       => 'button',
            ]) ?>
        <?php endif ?>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button
                    class="btn btn-link p-0 border-0 dockify-profile-avatar-button"
                    type="button"
                    id="profileDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Profile menu"
            >
                <?php if (!empty($photo)): ?>
                    <img
                            src="<?= Html::encode($photo) ?>"
                            alt="<?= Html::encode($username) ?>"
                            class="dockify-profile-avatar"
                            style="object-fit: cover;"
                    >
                <?php else: ?>
                    <span class="dockify-profile-avatar" aria-hidden="true"><?= Html::encode($initial) ?></span>
                <?php endif ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end dockify-profile-dropdown" aria-labelledby="profileDropdown">
                <!-- Profile Header -->
                <div class="dockify-profile-dropdown-header">
                    <div class="dockify-profile-dropdown-photo">
                        <?php if (!empty($photo)): ?>
                            <img
                                    src="<?= Html::encode($photo) ?>"
                                    alt="<?= Html::encode($username) ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                            >
                        <?php else: ?>
                            <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: var(--dockify-primary-soft); color: var(--dockify-primary); font-weight: 700; font-size: 2.5rem;">
                                <?= Html::encode($initial) ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="dockify-profile-dropdown-info">
                        <div class="dockify-profile-username text-nowrap"><?= Html::encode($username) ?></div>
                        <div class="dockify-profile-nickname"><?= Html::encode($nickname ?? '') ?></div>
                    </div>
                </div>

                <!-- Menu Divider -->
                <hr class="dropdown-divider my-2">

                <!-- Menu Items -->
                <?php foreach ($this->params['menuAvatarRightItems'] as $item): ?>
                    <?php if (isset($item['items']) && !empty($item['items'])): ?>
                        <!-- Parent with submenu -->
                        <div class="dropdown-submenu">
                            <button class="dropdown-item dropdown-toggle" type="button">
                                <?php if (!empty($item['options']['icon'])): ?>
                                    <i class="<?= Html::encode($item['options']['icon']) ?> me-2"></i>
                                <?php endif ?>
                                <?= Html::encode($item['label']) ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($item['items'] as $subItem): ?>
                                    <li>
                                        <?= Html::a(
                                            (isset($subItem['options']['icon']) ? '<i class="' . Html::encode($subItem['options']['icon']) . ' me-2"></i>' : '') .
                                            Html::encode($subItem['label']),
                                            $subItem['url'],
                                            ['class' => 'dropdown-item', 'encode' => false]
                                        ) ?>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Simple menu item -->
                        <?= Html::a(
                            (isset($item['options']['icon']) ? '<i class="bi bi-' . Html::encode($item['options']['icon']) . ' me-2"></i>' : '') .
                            Html::encode($item['label']),
                            $item['url'],
                            ['class' => 'dropdown-item', 'encode' => false]
                        ) ?>
                    <?php endif ?>
                <?php endforeach ?>

                <!-- Divider before Sign Out -->
                <hr class="dropdown-divider my-2">

                <!-- Sign Out -->
                <?= Html::a(
                    '<i class="bi bi-box-arrow-right me-2"></i>Sign Out',
                    ['/site/logout'],
                    [
                        'class'       => 'dropdown-item text-danger',
                        'data-method' => 'post',
                        'encode'      => false
                    ]
                ) ?>
            </div>
        </div>
    </div>
</header>
