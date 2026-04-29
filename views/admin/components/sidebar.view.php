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

if ($variant === 'dashboard'):
    $username = (string) ($sidebar['username'] ?? 'Administrator');
    $logoutUrl = (string) ($sidebar['logout_url'] ?? appUrl('logout'));
?>
    <aside class="admin-sidebar" aria-label="Hoofdmenu" style="display:flex; background:#A53714; color:#fff; border-right:none;">
        <div class="sidebar-brand" style="color:#fff; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-cog" aria-hidden="true"></i>
            <span>Beheerpagina</span>
        </div>

        <div class="sidebar-user" style="color:rgba(255,255,255,0.9);">
            Ingelogd als <strong id="usernameSidebar" style="color:#fff;"><?= htmlspecialchars($username) ?></strong>
        </div>

        <nav class="space-y-2 mt-3" aria-label="Navigatie modules">
            <a href="<?= htmlspecialchars($leefgebiedenUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.1); color:#fff; justify-content:flex-start; gap:10px;">
                <i class="fas fa-folder-open" aria-hidden="true"></i>
                <span>Pas onderwerpen aan</span>
            </a>
            <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.2); color:#fff; justify-content:flex-start; gap:10px;">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= htmlspecialchars($aandachtspuntenUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.1); color:#fff; justify-content:flex-start; gap:10px;">
                <i class="fas fa-list-alt" aria-hidden="true"></i>
                <span>Laatst gemaakte scans</span>
            </a>
            <a href="<?= htmlspecialchars($functiesUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.1); color:#fff; justify-content:flex-start; gap:10px;">
                <i class="fas fa-database" aria-hidden="true"></i>
                <span>Check scan data</span>
            </a>
            <a href="<?= htmlspecialchars($verdiepingsvragenUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.1); color:#fff; justify-content:flex-start; gap:10px;">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
                <span>Vragenlijst data</span>
            </a>
        </nav>

        <div style="margin-top:auto; border-top:1px solid rgba(255,255,255,0.2); padding-top:16px;">
            <div style="display:flex; align-items:center; gap:10px; color:#fff; margin-bottom:12px;">
                <span style="width:34px; height:34px; border-radius:9999px; background:rgba(255,255,255,0.2); display:inline-flex; align-items:center; justify-content:center;">
                    <i class="fas fa-user" aria-hidden="true"></i>
                </span>
                <div>
                    <div id="usernameSidebarBottom" style="font-weight:600; line-height:1.2;"><?= htmlspecialchars($username) ?></div>
                    <div style="font-size:12px; opacity:0.85;">Administrator</div>
                </div>
            </div>

            <form method="post" action="<?= htmlspecialchars($logoutUrl) ?>">
                <?= CSRF::token() ?>
                <button type="submit" class="btn w-full" style="background:rgba(255,255,255,0.14); color:#fff; gap:8px;">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span>Uitloggen</span>
                </button>
            </form>
        </div>
    </aside>
<?php else: ?>
    <aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
        <div class="sidebar-brand"><?= htmlspecialchars($brand) ?></div>
        <div class="sidebar-user"><?= htmlspecialchars($metaLabel) ?>: <strong><?= htmlspecialchars($metaValue) ?></strong></div>
        <nav class="space-y-2 mt-3" aria-label="Navigatie modules">
            <?php if ($backUrl !== ''): ?>
                <a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-secondary w-full"><?= htmlspecialchars($backLabel) ?></a>
            <?php else: ?>
                <?php
                $links = [
                    ['key' => 'dashboard', 'url' => $dashboardUrl, 'label' => 'Dashboard'],
                    ['key' => 'leefgebieden', 'url' => $leefgebiedenUrl, 'label' => 'Leefgebieden'],
                    ['key' => 'functies', 'url' => $functiesUrl, 'label' => 'Functies'],
                    ['key' => 'aandachtspunten', 'url' => $aandachtspuntenUrl, 'label' => 'Aandachtspunten'],
                    ['key' => 'verdiepingsvragen', 'url' => $verdiepingsvragenUrl, 'label' => 'Verdiepingsvragen'],
                ];
                ?>
                <?php foreach ($links as $link): ?>
                    <?php $isActive = $link['key'] === $activeKey; ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" class="btn<?= $isActive ? '' : ' btn-secondary' ?> w-full" <?= $isActive ? ' style="background:#A53714;color:#fff;"' : '' ?>>
                        <?= htmlspecialchars($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </aside>
<?php endif; ?>