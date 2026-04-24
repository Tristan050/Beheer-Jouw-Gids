<?php
$user = is_array($data['user'] ?? null) ? $data['user'] : [];
$fullName = trim((string) ($user['first_name'] ?? '') . ' ' . (string) ($user['last_name'] ?? ''));
$username = $fullName !== '' ? $fullName : 'Administrator';

$dashboardUrl = appUrl('admin');
$leefgebiedenUrl = appUrl('leefgebieden');
$functiesUrl = appUrl('functies');
$aandachtspuntenUrl = appUrl('aandachtspunten');
$verdiepingsvragenUrl = appUrl('verdiepingsvragen');
$vragenlijstenUrl = appUrl('vragenlijsten');
$organisatiesUrl = appUrl('organisaties');
$verdiepingKoppelingenUrl = appUrl('verdieping-koppelingen');
$logoutUrl = appUrl('logout');
?>

<div id="adminPanel" class="admin-shell">
    <aside class="admin-sidebar" aria-label="Hoofdmenu" style="display:flex; background:#A53714; color:#fff; border-right:none;">
        <div class="sidebar-brand" style="color:#fff; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-cog" aria-hidden="true"></i>
            <span>Beheerpagina</span>
        </div>

        <div class="sidebar-user" style="color:rgba(255,255,255,0.9);">
            Ingelogd als <strong id="usernameSidebar" style="color:#fff;">Administrator</strong>
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
            <a href="<?= htmlspecialchars($vragenlijstenUrl) ?>" class="btn w-full" style="background:rgba(255,255,255,0.1); color:#fff; justify-content:flex-start; gap:10px;">
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
                    <div id="usernameSidebarBottom" style="font-weight:600; line-height:1.2;">Administrator</div>
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

    <div class="admin-content transition-all duration-300">
        <header class="admin-topbar" aria-label="Bovenbalk">
            <h1 class="topbar-title">Beheerpagina</h1>
        </header>

        <main class="page-wrap">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="<?= htmlspecialchars($dashboardUrl) ?>">Dashboard</a>
                <span>/</span>
                <span class="current">Overzicht</span>
            </nav>

            <div class="page-header">
                <div>
                    <h2 class="page-title">Welkom, <span id="usernameDisplay">Administrator</span></h2>
                    <p class="page-subtitle">Hier zie je direct de belangrijkste trends, bezoeken en scanactiviteit.</p>
                </div>
            </div>

            <section class="panel" aria-label="Snelnavigatie beheer" style="margin-top: 12px;">
                <div class="panel-header">
                    <h3 class="panel-title">Snel naar beheeronderdelen</h3>
                    <span class="badge badge-secondary">Alle modules</span>
                </div>
                <div class="toolbar-actions" style="width:100%;">
                    <a href="<?= htmlspecialchars($leefgebiedenUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-folder-open" aria-hidden="true"></i><span>Leefgebieden</span></a>
                    <a href="<?= htmlspecialchars($functiesUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-database" aria-hidden="true"></i><span>Functies</span></a>
                    <a href="<?= htmlspecialchars($aandachtspuntenUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-list-alt" aria-hidden="true"></i><span>Aandachtspunten</span></a>
                    <a href="<?= htmlspecialchars($verdiepingsvragenUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-chart-line" aria-hidden="true"></i><span>Verdiepingsvragen</span></a>
                    <a href="<?= htmlspecialchars($vragenlijstenUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-list" aria-hidden="true"></i><span>Vragenlijsten</span></a>
                    <a href="<?= htmlspecialchars($organisatiesUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-building" aria-hidden="true"></i><span>Organisaties</span></a>
                    <a href="<?= htmlspecialchars($verdiepingKoppelingenUrl) ?>" class="btn btn-secondary" style="justify-content:flex-start; gap:8px;"><i class="fas fa-link" aria-hidden="true"></i><span>Verdieping koppelingen</span></a>
                </div>
            </section>

            <section class="stats-grid" aria-label="Kernstatistieken">
                <article class="metric-card" aria-label="Unieke bezoekers">
                    <div class="metric-row">
                        <div>
                            <h3 class="metric-label">Unieke bezoekers</h3>
                            <p class="metric-value" id="metricUniekeBezoekers">0</p>
                        </div>
                        <div class="metric-icon" aria-hidden="true">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                </article>

                <article class="metric-card metric-card--secondary" aria-label="Nieuwe scans vandaag">
                    <div class="metric-row">
                        <div>
                            <h3 class="metric-label">Nieuwe scans</h3>
                            <p class="metric-value" id="metricNieuweScans">0</p>
                            <p class="metric-note">Vandaag</p>
                        </div>
                        <div class="metric-icon" aria-hidden="true">
                            <i class="fas fa-chart-bar text-secondary"></i>
                        </div>
                    </div>
                </article>

                <article class="metric-card" aria-label="Totaal scans">
                    <div class="metric-row">
                        <div>
                            <h3 class="metric-label">Totaal scans</h3>
                            <p class="metric-value" id="metricTotaalScans">0</p>
                        </div>
                        <div class="metric-icon" aria-hidden="true">
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                    </div>
                </article>

                <article class="metric-card metric-card--secondary" aria-label="Bezoekers Jouw-Gids">
                    <div class="metric-row">
                        <div>
                            <h3 class="metric-label">Bezoekers Jouw-Gids</h3>
                            <p class="metric-value" id="metricTotaalBezoekers">0</p>
                        </div>
                        <div class="metric-icon" aria-hidden="true">
                            <i class="fas fa-globe text-secondary"></i>
                        </div>
                    </div>
                </article>
            </section>

            <section class="panel-grid" aria-label="Dashboard grafieken">
                <article class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">Bezoekers per dag (laatste 7 dagen)</h3>
                    </div>
                    <div class="h-64" role="img" aria-label="Staafdiagram bezoekers per dag">
                        <canvas id="usersChart" class="w-full"></canvas>
                    </div>
                </article>

                <article class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">Scans per dag (laatste 7 dagen)</h3>
                    </div>
                    <div class="h-64" role="img" aria-label="Lijndiagram scans per dag">
                        <canvas id="scansChart" class="w-full"></canvas>
                    </div>
                </article>
            </section>

            <section class="panel" aria-label="Recente bezoeken" id="recente-bezoeken">
                <div class="panel-header">
                    <h3 class="panel-title">Recente bezoeken</h3>
                    <span class="badge badge-primary" id="visitCountBadge">0 momenten</span>
                </div>

                <div class="toolbar">
                    <div class="toolbar-actions" style="width: 100%;">
                        <div class="search-wrap" style="flex: 1; min-width: 220px;">
                            <input id="visitSearchInput" type="text" placeholder="Zoek bezoekmoment..." class="search-input" autocomplete="off" />
                            <i class="fas fa-search search-icon" aria-hidden="true"></i>
                        </div>
                        <button class="btn btn-secondary" type="button" onclick="resetVisitFilters()">Filters wissen</button>
                    </div>
                </div>

                <p id="visitFilterStatus" class="text-sm text-gray-600 mb-3" aria-live="polite"></p>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Actie</th>
                            </tr>
                        </thead>
                        <tbody id="visitsTableBody"></tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
    const adminData = {
        username: <?= json_encode($username, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
        stats: {
            uniekeBezoekers: 1284,
            nieuweScansVandaag: 37,
            totaalScans: 9280,
            totaalBezoekers: 19432,
        },
        bezoekersChartData: {
            labels: ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'],
            values: [143, 188, 176, 220, 205, 169, 183],
        },
        scansChartData: {
            labels: ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'],
            values: [42, 57, 49, 63, 72, 39, 51],
        },
        bezoekersMomenten: [
            '20-04-2026 09:14',
            '20-04-2026 08:37',
            '19-04-2026 22:05',
            '19-04-2026 19:48',
            '19-04-2026 16:11',
            '19-04-2026 13:03',
            '19-04-2026 10:27',
        ],
    };

    let usersChartInstance = null;
    let scansChartInstance = null;

    function formatMetric(value) {
        const safeNumber = Number.isFinite(Number(value)) ? Number(value) : 0;
        return safeNumber.toLocaleString('nl-NL');
    }

    function sanitizeChartData(data) {
        const labels = Array.isArray(data && data.labels) ? data.labels : [];
        const values = Array.isArray(data && data.values) ? data.values : [];

        return {
            labels: labels.map((label) => String(label)),
            values: values.map((value) => {
                const parsed = Number(value);
                return Number.isFinite(parsed) ? parsed : 0;
            }),
        };
    }

    function setText(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }

    function renderStats() {
        const stats = adminData.stats || {};
        setText('metricUniekeBezoekers', formatMetric(stats.uniekeBezoekers));
        setText('metricNieuweScans', formatMetric(stats.nieuweScansVandaag));
        setText('metricTotaalScans', formatMetric(stats.totaalScans));
        setText('metricTotaalBezoekers', formatMetric(stats.totaalBezoekers));
    }

    function renderUser() {
        const username = String(adminData.username || 'Administrator');
        setText('usernameDisplay', username);
        setText('usernameSidebar', username);
        setText('usernameSidebarBottom', username);
    }

    function renderVisits() {
        const visitsTableBody = document.getElementById('visitsTableBody');
        if (!visitsTableBody) {
            return;
        }

        const visits = Array.isArray(adminData.bezoekersMomenten) ? adminData.bezoekersMomenten : [];
        if (visits.length === 0) {
            visitsTableBody.innerHTML = '' +
                '<tr>' +
                '<td colspan="2">' +
                '<div class="empty-state">' +
                '<i class="fas fa-calendar-times" aria-hidden="true"></i>' +
                'Nog geen recente bezoeken beschikbaar' +
                '</div>' +
                '</td>' +
                '</tr>';
            return;
        }

        const rows = visits
            .map((moment) => {
                const safeMoment = String(moment).trim();
                const dataMoment = safeMoment.toLowerCase();
                return '' +
                    `<tr data-moment="${dataMoment.replace(/"/g, '&quot;')}">` +
                    `<td>${safeMoment.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</td>` +
                    '<td><span class="badge badge-secondary">Bezocht</span></td>' +
                    '</tr>';
            })
            .join('');

        visitsTableBody.innerHTML = rows;
    }

    function getVisitRows(visitsTableBody) {
        return visitsTableBody ? Array.from(visitsTableBody.querySelectorAll('tr[data-moment]')) : [];
    }

    function applyVisitFilter() {
        const visitSearchInput = document.getElementById('visitSearchInput');
        const visitsTableBody = document.getElementById('visitsTableBody');
        const visitCountBadge = document.getElementById('visitCountBadge');
        const visitFilterStatus = document.getElementById('visitFilterStatus');

        if (!visitSearchInput || !visitsTableBody) {
            return;
        }

        const term = visitSearchInput.value.trim().toLowerCase();
        const rows = getVisitRows(visitsTableBody);

        rows.forEach((row) => {
            const moment = row.getAttribute('data-moment') || '';
            const shouldHide = term !== '' && !moment.includes(term);
            row.classList.toggle('hidden', shouldHide);
        });

        const visible = rows.filter((row) => !row.classList.contains('hidden')).length;
        if (visitCountBadge) {
            visitCountBadge.textContent = `${visible} momenten`;
        }
        if (visitFilterStatus) {
            visitFilterStatus.textContent = term === '' ?
                `Toont alle ${rows.length} bezoekmomenten` :
                `Filter actief: ${visible} van ${rows.length} bezoekmomenten`;
        }
    }

    function resetVisitFilters() {
        const visitSearchInput = document.getElementById('visitSearchInput');
        if (visitSearchInput) {
            visitSearchInput.value = '';
        }
        applyVisitFilter();

        if (typeof window.adminToast === 'function') {
            window.adminToast('Filters voor recente bezoeken zijn gewist.', 'info');
        }
    }

    function destroyExistingCharts() {
        if (usersChartInstance) {
            usersChartInstance.destroy();
            usersChartInstance = null;
        }
        if (scansChartInstance) {
            scansChartInstance.destroy();
            scansChartInstance = null;
        }
    }

    function initCharts() {
        if (typeof Chart === 'undefined') {
            return;
        }

        const usersCanvas = document.getElementById('usersChart');
        const scansCanvas = document.getElementById('scansChart');
        if (!usersCanvas || !scansCanvas) {
            return;
        }

        const safeBezoekersData = sanitizeChartData(adminData.bezoekersChartData);
        const safeScansData = sanitizeChartData(adminData.scansChartData);

        destroyExistingCharts();

        usersChartInstance = new Chart(usersCanvas, {
            type: 'bar',
            data: {
                labels: safeBezoekersData.labels,
                datasets: [{
                    label: 'Aantal bezoekers',
                    data: safeBezoekersData.values,
                    backgroundColor: '#A53714',
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            drawBorder: false
                        },
                    },
                    x: {
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });

        scansChartInstance = new Chart(scansCanvas, {
            type: 'line',
            data: {
                labels: safeScansData.labels,
                datasets: [{
                    label: 'Aantal scans',
                    data: safeScansData.values,
                    borderColor: '#ACBC92',
                    backgroundColor: 'rgba(172, 188, 146, 0.14)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#ACBC92',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 1,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            drawBorder: false
                        },
                    },
                    x: {
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });
    }

    window.resetVisitFilters = resetVisitFilters;

    document.addEventListener('DOMContentLoaded', function() {
        const visitSearchInput = document.getElementById('visitSearchInput');

        renderUser();
        renderStats();
        renderVisits();

        if (visitSearchInput) {
            visitSearchInput.addEventListener('input', applyVisitFilter);
        }

        applyVisitFilter();
        initCharts();
    });
</script>