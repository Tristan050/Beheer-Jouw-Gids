<?php

class HomeController extends BaseController
{
    private AuthService $authService;
    private const DASHBOARD_DAYS = 7;
    private const RECENT_VISITS_LIMIT = 10;

    public function __construct(?AuthService $authService = null)
    {
        $this->authService = $authService ?? new AuthService();
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $user = $this->authService->getAuthenticatedUser();

        $this->render('admin/admin', [
            'user' => $user ?? [],
            'dashboard' => $this->buildDashboardData(),
        ]);
    }

    private function buildDashboardData(): array
    {
        $bezoekersStats = $this->getBezoekersStats();
        $scansStats = $this->getScansStats();

        return [
            'stats' => [
                'uniekeBezoekers' => $bezoekersStats['uniek'],
                'totaalBezoekers' => $bezoekersStats['totaal'],
                'nieuweScansVandaag' => $scansStats['vandaag'],
                'totaalScans' => $scansStats['totaal'],
            ],
            'bezoekersChartData' => $bezoekersStats['chart'],
            'scansChartData' => $scansStats['chart'],
            'bezoekersMomenten' => $bezoekersStats['recent'],
        ];
    }

    private function getBezoekersStats(): array
    {
        if (!$this->tableExists('gids_bezoekers')) {
            return [
                'totaal' => 0,
                'uniek' => 0,
                'recent' => [],
                'chart' => $this->emptySeries(),
            ];
        }

        $totalResult = execSQL('SELECT COUNT(*) AS total FROM gids_bezoekers', [], false);
        $uniqueResult = execSQL('SELECT COUNT(DISTINCT cookie_id) AS total FROM gids_bezoekers', [], false);

        $totaal = $this->fetchSingleCount($totalResult);
        $uniek = $this->fetchSingleCount($uniqueResult);

        $recent = $this->getRecentVisits();
        $chart = $this->buildDailySeries('gids_bezoekers', 'bezoektijd');

        return [
            'totaal' => $totaal,
            'uniek' => $uniek,
            'recent' => $recent,
            'chart' => $chart,
        ];
    }

    private function getScansStats(): array
    {
        if (!$this->tableExists('gids_scan_data')) {
            return [
                'totaal' => 0,
                'vandaag' => 0,
                'chart' => $this->emptySeries(),
            ];
        }

        $totalResult = execSQL('SELECT COUNT(*) AS total FROM gids_scan_data', [], false);
        $todayResult = execSQL('SELECT COUNT(*) AS total FROM gids_scan_data WHERE DATE(Created_at) = CURDATE()', [], false);

        return [
            'totaal' => $this->fetchSingleCount($totalResult),
            'vandaag' => $this->fetchSingleCount($todayResult),
            'chart' => $this->buildDailySeries('gids_scan_data', 'Created_at'),
        ];
    }

    private function fetchSingleCount($result): int
    {
        if (!$result || $result->num_rows === 0) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int) ($row['total'] ?? 0);
    }

    private function getRecentVisits(): array
    {
        $result = execSQL(
            'SELECT bezoektijd FROM gids_bezoekers ORDER BY bezoektijd DESC LIMIT ?',
            ['i', self::RECENT_VISITS_LIMIT],
            false
        );

        if (!$result) {
            return [];
        }

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $timestamp = (string) ($row['bezoektijd'] ?? '');
            if ($timestamp === '') {
                continue;
            }
            $date = date_create($timestamp);
            $items[] = $date ? $date->format('d-m-Y H:i') : $timestamp;
        }

        return $items;
    }

    private function buildDailySeries(string $table, string $dateColumn): array
    {
        $days = self::DASHBOARD_DAYS;
        $start = new DateTimeImmutable('today -' . ($days - 1) . ' days');
        $valuesByDate = [];
        $labels = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->modify('+' . $i . ' days');
            $key = $date->format('Y-m-d');
            $valuesByDate[$key] = 0;
            $labels[] = $this->formatDayLabel($date);
        }

        $sql = sprintf(
            'SELECT DATE(%s) AS day, COUNT(*) AS total FROM %s WHERE %s >= DATE_SUB(CURDATE(), INTERVAL %d DAY) GROUP BY day ORDER BY day ASC',
            $dateColumn,
            $table,
            $dateColumn,
            $days - 1
        );

        $result = execSQL($sql, [], false);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $day = (string) ($row['day'] ?? '');
                if ($day !== '' && array_key_exists($day, $valuesByDate)) {
                    $valuesByDate[$day] = (int) ($row['total'] ?? 0);
                }
            }
        }

        return [
            'labels' => $labels,
            'values' => array_values($valuesByDate),
        ];
    }

    private function emptySeries(): array
    {
        $start = new DateTimeImmutable('today -' . (self::DASHBOARD_DAYS - 1) . ' days');
        $labels = [];
        $values = [];

        for ($i = 0; $i < self::DASHBOARD_DAYS; $i++) {
            $date = $start->modify('+' . $i . ' days');
            $labels[] = $this->formatDayLabel($date);
            $values[] = 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function tableExists(string $table): bool
    {
        $result = execSQL(
            'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1',
            ['s', $table],
            false
        );

        return $result && $result->num_rows > 0;
    }

    private function formatDayLabel(DateTimeImmutable $date): string
    {
        $labels = ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'];
        $index = (int) $date->format('w');
        return $labels[$index] ?? $date->format('D');
    }
}
