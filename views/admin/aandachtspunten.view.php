<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Module: <strong>Aandachtspunten</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/admin" class="btn btn-secondary w-full">Dashboard</a>
			<a href="/leefgebieden" class="btn btn-secondary w-full">Leefgebieden</a>
			<a href="/functies" class="btn btn-secondary w-full">Functies</a>
			<a href="/aandachtspunten" class="btn w-full" style="background:#A53714;color:#fff;">Aandachtspunten</a>
			<a href="/verdiepingsvragen" class="btn btn-secondary w-full">Verdiepingsvragen</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f9f6ff 100%);">
			<h1 class="topbar-title">Aandachtspunten beheren</h1>
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
				<span class="current">Aandachtspunten</span>
			</nav>

			<section class="panel" style="border-color:#e4dcf2; background: linear-gradient(150deg, #fff 0%, #fbf9ff 100%);">
				<div class="panel-header">
					<div>
						<h2 class="panel-title">Overzicht gids_aandachtspunt</h2>
						<p class="text-sm text-slate-600 mt-1">Veldkoppeling: <strong>AandachtspuntID</strong>, <strong>FunctieID</strong>, <strong>Sort_order</strong>, <strong>Aandachtspunt</strong>, <strong>Toelichting</strong>, <strong>Scan_tekst</strong>, <strong>Advies_tekst</strong>.</p>
					</div>
					<a href="<?= htmlspecialchars(appUrl('aandachtspunt-edit')) ?>" class="btn" style="background:#5c3b87;color:#fff;">Nieuw aandachtspunt</a>
				</div>

				<div class="toolbar">
					<div class="toolbar-actions">
						<div class="search-wrap" style="flex:1;">
							<input id="aandachtspuntSearchInput" type="text" class="search-input" placeholder="Zoek op aandachtspunt, scan of advies..." />
							<span class="search-icon" aria-hidden="true">&#128269;</span>
						</div>
						<button type="button" class="btn btn-secondary" onclick="document.getElementById('aandachtspuntSearchInput').value=''; filterAandachtspunten();">Wissen</button>
					</div>
				</div>

				<div class="table-wrap">
					<table class="data-table" id="aandachtspuntTable" data-source-table="gids_aandachtspunt">
						<thead>
							<tr>
								<th>AandachtspuntID</th>
								<th>Functie</th>
								<th>Aandachtspunt</th>
								<th>Sort_order</th>
								<th>Scan_tekst</th>
								<th>Advies_tekst</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="aandachtspuntTableBody">
							<?php if (!empty($data['items'])): ?>
								<?php foreach ($data['items'] as $row): ?>
									<tr data-search="<?= htmlspecialchars((string) ($row['search'] ?? '')) ?>">
										<td><?= (int) ($row['id'] ?? 0) ?></td>
										<td><?= htmlspecialchars((string) ($row['functie_name'] ?? '')) ?></td>
										<td><?= htmlspecialchars((string) ($row['aandachtspunt'] ?? '')) ?></td>
										<td><?= (int) ($row['sort_order'] ?? 0) ?></td>
										<td><?= htmlspecialchars((string) ($row['scan_tekst'] ?? '')) ?></td>
										<td><?= htmlspecialchars((string) ($row['advies_tekst'] ?? '')) ?></td>
										<td class="flex gap-2 py-2">
											<a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('aandachtspunt-edit'))) ?>" class="btn btn-secondary">Bewerken</a>
											<form method="post" action="<?= htmlspecialchars(appUrl('aandachtspunt-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je dit aandachtspunt wilt verwijderen?');" style="display:inline;">
												<?= CSRF::token() ?>
												<input type="hidden" name="AandachtspuntID" value="<?= (int) ($row['id'] ?? 0) ?>">
												<button type="submit" class="btn btn-secondary">Verwijderen</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="7" class="text-center py-4">Nog geen aandachtspunten gevonden.</td>
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
	function filterAandachtspunten() {
		const input = document.getElementById('aandachtspuntSearchInput');
		const rows = Array.from(document.querySelectorAll('#aandachtspuntTableBody tr'));
		const term = (input && input.value ? input.value : '').toLowerCase().trim();

		rows.forEach((row) => {
			const haystack = (row.getAttribute('data-search') || '').toLowerCase();
			row.classList.toggle('hidden', term !== '' && !haystack.includes(term));
		});
	}

	const aandachtspuntSearchInput = document.getElementById('aandachtspuntSearchInput');
	if (aandachtspuntSearchInput) {
		aandachtspuntSearchInput.addEventListener('input', filterAandachtspunten);
	}
</script>
