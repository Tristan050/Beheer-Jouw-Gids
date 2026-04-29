<?php
$sidebar = is_array($sidebar ?? null) ? $sidebar : [];

$variant = (string) ($sidebar['variant'] ?? 'module');
$brand = (string) ($sidebar['brand'] ?? 'Jouw-Gids Beheer');
$metaLabel = (string) ($sidebar['meta_label'] ?? 'Module');
$metaValue = (string) ($sidebar['meta_value'] ?? 'Dashboard');
$activeKey = (string) ($sidebar['active'] ?? 'dashboard');
$backUrl = isset($sidebar['back_url']) ? (string) $sidebar['back_url'] : '';
$backLabel = (string) ($sidebar['back_label'] ?? 'Terug naar overzicht');

$dashboardUrl = appUrl('admin');
$leefgebiedenUrl = appUrl('leefgebieden');
$functiesUrl = appUrl('functies');
$aandachtspuntenUrl = appUrl('aandachtspunten');
$verdiepingsvragenUrl = appUrl('verdiepingsvragen');
$vragenlijstenUrl = appUrl('vragenlijsten');
$organisatiesUrl = appUrl('organisaties');
$verdiepingKoppelingenUrl = appUrl('verdieping-koppelingen');

$linkBase = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition';
$linkDark = $linkBase . ' text-white/90 hover:bg-white/10 hover:text-white';
$linkLight = $linkBase . ' text-slate-700 hover:bg-slate-100';
$activeLight = $linkBase . ' bg-[#A53714] text-white shadow-sm';

if ($variant === 'dashboard'):
    $username = (string) ($sidebar['username'] ?? 'Administrator');
    $logoutUrl = (string) ($sidebar['logout_url'] ?? appUrl('logout'));
?>
    <aside class="flex w-72 shrink-0 flex-col bg-[#A53714] px-6 py-6 text-white" aria-label="Hoofdmenu">
        <div class="flex items-center gap-3 text-lg font-semibold">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                <i class="fas fa-cog" aria-hidden="true"></i>
            </span>
            <span>Beheerpagina</span>
        </div>

        <div class="mt-6 text-sm text-white/80">
            Ingelogd als <strong id="usernameSidebar" class="text-white"><?= htmlspecialchars($username) ?></strong>
        </div>

        <nav class="mt-6 space-y-2" aria-label="Navigatie modules">
            <a href="<?= htmlspecialchars($leefgebiedenUrl) ?>" class="<?= $linkDark ?>">
                <i class="fas fa-folder-open" aria-hidden="true"></i>
                <span>Pas onderwerpen aan</span>
            </a>
            <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="<?= $linkDark ?> bg-white/15 text-white">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= htmlspecialchars($aandachtspuntenUrl) ?>" class="<?= $linkDark ?>">
                <i class="fas fa-list-alt" aria-hidden="true"></i>
                <span>Laatst gemaakte scans</span>
            </a>
            <a href="<?= htmlspecialchars($functiesUrl) ?>" class="<?= $linkDark ?>">
                <i class="fas fa-database" aria-hidden="true"></i>
                <span>Check scan data</span>
            </a>
            <a href="<?= htmlspecialchars($verdiepingsvragenUrl) ?>" class="<?= $linkDark ?>">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
                <span>Vragenlijst data</span>
            </a>
        </nav>

        <div class="mt-auto border-t border-white/20 pt-6">
            <div class="flex items-center gap-3 text-white/90">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
                    <i class="fas fa-user" aria-hidden="true"></i>
                </span>
                <div>
                    <div id="usernameSidebarBottom" class="font-semibold leading-tight text-white"><?= htmlspecialchars($username) ?></div>
                    <div class="text-xs text-white/70">Administrator</div>
                </div>
            </div>

            <form method="post" action="<?= htmlspecialchars($logoutUrl) ?>" class="mt-4">
                <?= CSRF::token() ?>
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Uitloggen</span>
                </button>
            </form>
        </div>
    </aside>
<?php else: ?>
    <aside class="flex w-72 shrink-0 flex-col border-r border-slate-200 bg-white px-6 py-6" aria-label="Hoofdmenu beheer">
        <div class="text-lg font-semibold text-slate-900"><?= htmlspecialchars($brand) ?></div>
        <div class="mt-3 text-sm text-slate-600"><?= htmlspecialchars($metaLabel) ?>: <strong class="text-slate-900"><?= htmlspecialchars($metaValue) ?></strong></div>
        <nav class="mt-6 space-y-2" aria-label="Navigatie modules">
            <?php if ($backUrl !== ''): ?>
                <a href="<?= htmlspecialchars($backUrl) ?>" class="<?= $linkLight ?> border border-slate-200">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($backLabel) ?></span>
                </a>
            <?php else: ?>
                <?php
                $links = [
                    ['key' => 'dashboard', 'url' => $dashboardUrl, 'label' => 'Dashboard'],
                    ['key' => 'leefgebieden', 'url' => $leefgebiedenUrl, 'label' => 'Leefgebieden'],
                    ['key' => 'functies', 'url' => $functiesUrl, 'label' => 'Functies'],
                    ['key' => 'aandachtspunten', 'url' => $aandachtspuntenUrl, 'label' => 'Aandachtspunten'],
                    ['key' => 'verdiepingsvragen', 'url' => $verdiepingsvragenUrl, 'label' => 'Verdiepingsvragen'],
                    ['key' => 'vragenlijsten', 'url' => $vragenlijstenUrl, 'label' => 'Vragenlijsten'],
                    ['key' => 'organisaties', 'url' => $organisatiesUrl, 'label' => 'Organisaties'],
                    ['key' => 'verdieping-koppelingen', 'url' => $verdiepingKoppelingenUrl, 'label' => 'Verdieping koppelingen'],
                ];
                ?>
                <?php foreach ($links as $link): ?>
                    <?php $isActive = $link['key'] === $activeKey; ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" class="<?= $isActive ? $activeLight : $linkLight ?>">
                        <?= htmlspecialchars($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </aside>
<?php endif; ?>