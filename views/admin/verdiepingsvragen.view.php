<?php
$sidebar = [
	'meta_label' => 'Module',
	'meta_value' => 'Verdiepingsvragen',
	'active' => 'verdiepingsvragen',
];
?>

<div class="admin-shell">
	<?php require __DIR__ . '/components/sidebar.view.php'; ?>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #eef9ff 100%);">
			<h1 class="topbar-title">Verdiepingsvragen beheren</h1>
		</header>

		<main class="page-wrap">
			<?php if (!empty($data['error'])): ?>
				<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
			<?php endif; ?>

			<?php if (!empty($data['success'])): ?>
				<div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
			<?php endif; ?>

			<nav class="breadcrumbs" aria-label="Breadcrumb">
				<a href="/admin">Dashboard</a>
				<span>/</span>
				<span class="current">Verdiepingsvragen</span>
			</nav>

			<section class="panel" style="border-color:#cbe6f5; background: linear-gradient(160deg, #fff 0%, #f3fbff 100%);">
				<div class="panel-header">
					<div>
						<h2 class="panel-title">Overzicht gids_verdieping_vragen</h2>
						<p class="text-sm text-slate-600 mt-1">Veldkoppeling: <strong>VerdiepingsvraagID</strong>, <strong>Vraag</strong>, <strong>AandachtspuntID</strong>.</p>
					</div>
					<a href="<?= htmlspecialchars(appUrl('verdieping-vraag-edit')) ?>" class="btn" style="background:#0f6d99;color:#fff;">Nieuwe vraag</a>
				</div>

				<div class="toolbar">
					<div class="toolbar-actions">
						<div class="search-wrap" style="flex:1;">
							<input id="verdiepingSearchInput" type="text" class="search-input" placeholder="Zoek op vraag of aandachtspunt..." />
							<span class="search-icon" aria-hidden="true">&#128269;</span>
						</div>
						<button type="button" class="btn btn-secondary" onclick="document.getElementById('verdiepingSearchInput').value=''; filterVerdiepingsvragen();">Wissen</button>
					</div>
				</div>

				<div class="table-wrap">
					<table class="data-table" id="verdiepingTable" data-source-table="gids_verdieping_vragen">
						<thead>
							<tr>
								<th>VerdiepingsvraagID</th>
								<th>Vraag</th>
								<th>Aandachtspunt</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="verdiepingTableBody">
							<?php if (!empty($data['items'])): ?>
								<?php foreach ($data['items'] as $row): ?>
									<tr data-search="<?= htmlspecialchars((string) ($row['search'] ?? '')) ?>">
										<td><?= (int) ($row['id'] ?? 0) ?></td>
										<td><?= htmlspecialchars((string) ($row['vraag'] ?? '')) ?></td>
										<td><?= htmlspecialchars((string) ($row['aandachtspunt_name'] ?? '')) ?></td>
										<td class="flex gap-2 py-2">
											<a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('verdieping-vraag-edit'))) ?>" class="btn btn-secondary">Bewerken</a>
											<form method="post" action="<?= htmlspecialchars(appUrl('verdieping-vraag-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze verdiepingsvraag wilt verwijderen?');" style="display:inline;">
												<?= CSRF::token() ?>
												<input type="hidden" name="VerdiepingsvraagID" value="<?= (int) ($row['id'] ?? 0) ?>">
												<button type="submit" class="btn btn-secondary">Verwijderen</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="4" class="text-center py-4">Nog geen verdiepingsvragen gevonden.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>
		</main>
	</div>
</div>

<script>
	function filterVerdiepingsvragen() {
		const input = document.getElementById('verdiepingSearchInput');
		const rows = Array.from(document.querySelectorAll('#verdiepingTableBody tr'));
		const term = (input && input.value ? input.value : '').toLowerCase().trim();

		rows.forEach((row) => {
			const haystack = (row.getAttribute('data-search') || '').toLowerCase();
			row.classList.toggle('hidden', term !== '' && !haystack.includes(term));
		});
	}

	const verdiepingSearchInput = document.getElementById('verdiepingSearchInput');
	if (verdiepingSearchInput) {
		verdiepingSearchInput.addEventListener('input', filterVerdiepingsvragen);
	}
</script>