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

$sidebar = [
    'variant' => 'dashboard',
    'username' => $username,
    'logout_url' => $logoutUrl,
];
?>

<div id="adminPanel" class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur" aria-label="Bovenbalk">
                <div class="flex items-center justify-between gap-4">
                    <h1 class="text-2xl font-semibold tracking-tight">Beheerpagina</h1>
                    <div class="hidden items-center gap-2 text-xs font-semibold text-slate-500 md:flex">
                        <span class="h-2 w-2 rounded-full bg-[#ACBC92]"></span>
                        <span>Live overzicht</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
                    <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="font-medium text-slate-600 hover:text-slate-900">Dashboard</a>
                    <span class="text-slate-400">/</span>
                    <span class="font-medium text-slate-700">Overzicht</span>
                </nav>

                <div class="rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                    <h2 class="text-xl font-semibold">Welkom, <span id="usernameDisplay" class="text-[#A53714]">Administrator</span></h2>
                    <p class="mt-2 text-sm text-slate-600">Hier zie je direct de belangrijkste trends, bezoeken en scanactiviteit.</p>
                </div>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-label="Snelnavigatie beheer">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Snel naar beheeronderdelen</h3>
                        <span class="inline-flex items-center rounded-full bg-[#ACBC92]/20 px-3 py-1 text-xs font-semibold text-[#55624A]">Alle modules</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        <a href="<?= htmlspecialchars($leefgebiedenUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                            <span>Leefgebieden</span>
                        </a>
                        <a href="<?= htmlspecialchars($functiesUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-database" aria-hidden="true"></i>
                            <span>Functies</span>
                        </a>
                        <a href="<?= htmlspecialchars($aandachtspuntenUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-list-alt" aria-hidden="true"></i>
                            <span>Aandachtspunten</span>
                        </a>
                        <a href="<?= htmlspecialchars($verdiepingsvragenUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                            <span>Verdiepingsvragen</span>
                        </a>
                        <a href="<?= htmlspecialchars($vragenlijstenUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-list" aria-hidden="true"></i>
                            <span>Vragenlijsten</span>
                        </a>
                        <a href="<?= htmlspecialchars($organisatiesUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-building" aria-hidden="true"></i>
                            <span>Organisaties</span>
                        </a>
                        <a href="<?= htmlspecialchars($verdiepingKoppelingenUrl) ?>" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#A53714]/40 hover:text-[#A53714]">
                            <i class="fas fa-link" aria-hidden="true"></i>
                            <span>Verdieping koppelingen</span>
                        </a>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Kernstatistieken">
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" aria-label="Unieke bezoekers">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500">Unieke bezoekers</h3>
                                <p class="mt-2 text-2xl font-semibold text-slate-900" id="metricUniekeBezoekers">0</p>
                            </div>
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#A53714]/10 text-[#A53714]" aria-hidden="true">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-[#DDE6CF] bg-[#F8FAF6] p-4 shadow-sm" aria-label="Nieuwe scans vandaag">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500">Nieuwe scans</h3>
                                <p class="mt-2 text-2xl font-semibold text-slate-900" id="metricNieuweScans">0</p>
                                <p class="text-xs font-semibold text-[#6B7A55]">Vandaag</p>
                            </div>
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#ACBC92]/30 text-[#6B7A55]" aria-hidden="true">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" aria-label="Totaal scans">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500">Totaal scans</h3>
                                <p class="mt-2 text-2xl font-semibold text-slate-900" id="metricTotaalScans">0</p>
                            </div>
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#A53714]/10 text-[#A53714]" aria-hidden="true">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-[#DDE6CF] bg-[#F8FAF6] p-4 shadow-sm" aria-label="Bezoekers Jouw-Gids">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500">Bezoekers Jouw-Gids</h3>
                                <p class="mt-2 text-2xl font-semibold text-slate-900" id="metricTotaalBezoekers">0</p>
                            </div>
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#ACBC92]/30 text-[#6B7A55]" aria-hidden="true">
                                <i class="fas fa-globe"></i>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="grid gap-4 xl:grid-cols-2" aria-label="Dashboard grafieken">
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Bezoekers per dag (laatste 7 dagen)</h3>
                        </div>
                        <div class="h-64" role="img" aria-label="Staafdiagram bezoekers per dag">
                            <canvas id="usersChart" class="h-full w-full"></canvas>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Scans per dag (laatste 7 dagen)</h3>
                        </div>
                        <div class="h-64" role="img" aria-label="Lijndiagram scans per dag">
                            <canvas id="scansChart" class="h-full w-full"></canvas>
                        </div>
                    </article>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-label="Recente bezoeken" id="recente-bezoeken">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Recente bezoeken</h3>
                        <span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]" id="visitCountBadge">0 momenten</span>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[220px] flex-1">
                            <input id="visitSearchInput" type="text" placeholder="Zoek bezoekmoment..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pr-9 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" autocomplete="off" />
                            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
                        </div>
                        <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50" type="button" onclick="resetVisitFilters()">Filters wissen</button>
                    </div>

                    <p id="visitFilterStatus" class="mt-3 text-sm text-slate-600" aria-live="polite"></p>

                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Datum</th>
                                    <th class="px-4 py-3">Actie</th>
                                </tr>
                            </thead>
                            <tbody id="visitsTableBody" class="divide-y divide-slate-200 bg-white"></tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>

<?php
$dashboard = is_array($data['dashboard'] ?? null) ? $data['dashboard'] : [];
$dashboard['username'] = $username;
$dashboardJson = json_encode(
    $dashboard,
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script>
    const adminData = <?= $dashboardJson ?: '{}' ?>;

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
                '<td colspan="2" class="px-4 py-6">' +
                '<div class="flex items-center justify-center gap-2 text-sm text-slate-500">' +
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
                        '<td><span class="inline-flex items-center rounded-full bg-[#ACBC92]/20 px-2 py-0.5 text-xs font-semibold text-[#55624A]">Bezocht</span></td>' +
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