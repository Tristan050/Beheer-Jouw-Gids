<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Module: <strong>Leefgebieden</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/admin" class="btn btn-secondary w-full">Dashboard</a>
			<a href="/leefgebieden" class="btn w-full" style="background:#A53714;color:#fff;">Leefgebieden</a>
			<a href="/functies" class="btn btn-secondary w-full">Functies</a>
			<a href="/aandachtspunten" class="btn btn-secondary w-full">Aandachtspunten</a>
			<a href="/verdiepingsvragen" class="btn btn-secondary w-full">Verdiepingsvragen</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #fff7ef 100%);">
			<h1 class="topbar-title">Leefgebieden beheren</h1>
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
				<span class="current">Leefgebieden</span>
			</nav>

			<section class="panel" style="border-color:#f1d9cc; background: linear-gradient(160deg, #fff 0%, #fff8f2 100%);">
				<div class="panel-header">
					<div>
						<h2 class="panel-title">Overzicht gids_leefgebied</h2>
						<p class="text-sm text-slate-600 mt-1">Veldkoppeling: <strong>LeefgebiedID</strong>, <strong>Naam_leefgebied</strong>, <strong>beschrijving_leefgebied</strong>, <strong>Sort_order</strong>.</p>
					</div>
					<a href="<?= htmlspecialchars(appUrl('leefgebied-edit')) ?>" class="btn" style="background:#A53714;color:#fff;">Nieuw leefgebied</a>
				</div>

				<div class="toolbar">
					<div class="toolbar-actions">
						<div class="search-wrap" style="flex:1;">
							<input id="leefgebiedSearchInput" type="text" class="search-input" placeholder="Zoek op naam of beschrijving..." />
							<span class="search-icon" aria-hidden="true">&#128269;</span>
						</div>
						<button type="button" class="btn btn-secondary" onclick="document.getElementById('leefgebiedSearchInput').value=''; filterLeefgebieden();">Wissen</button>
					</div>
				</div>

				<div class="table-wrap">
					<table class="data-table" id="leefgebiedTable" data-source-table="gids_leefgebied">
						<thead>
							<tr>
								<th>LeefgebiedID</th>
								<th>Naam_leefgebied</th>
								<th>beschrijving_leefgebied</th>
								<th>Sort_order</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="leefgebiedTableBody">
							<?php if (!empty($data['items'])): ?>
								<?php foreach ($data['items'] as $row): ?>
									<tr data-search="<?= htmlspecialchars((string) ($row['search'] ?? '')) ?>">
										<td><?= (int) ($row['id'] ?? 0) ?></td>
										<td><?= htmlspecialchars((string) ($row['name'] ?? '')) ?></td>
										<td><?= htmlspecialchars((string) ($row['description'] ?? '')) ?></td>
										<td><?= (int) ($row['sort_order'] ?? 0) ?></td>
										<td class="flex gap-2 py-2">
											<a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('leefgebied-edit'))) ?>" class="btn btn-secondary">Bewerken</a>
											<form method="post" action="<?= htmlspecialchars(appUrl('leefgebied-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je dit leefgebied wilt verwijderen?');" style="display:inline;">
												<?= CSRF::token() ?>
												<input type="hidden" name="LeefgebiedID" value="<?= (int) ($row['id'] ?? 0) ?>">
												<button type="submit" class="btn btn-secondary">Verwijderen</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="5" class="text-center py-4">Nog geen leefgebieden gevonden.</td>
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
	function filterLeefgebieden() {
		const input = document.getElementById('leefgebiedSearchInput');
		const rows = Array.from(document.querySelectorAll('#leefgebiedTableBody tr'));
		const term = (input && input.value ? input.value : '').toLowerCase().trim();

		rows.forEach((row) => {
			const haystack = (row.getAttribute('data-search') || '').toLowerCase();
			row.classList.toggle('hidden', term !== '' && !haystack.includes(term));
		});
	}

	const leefgebiedSearchInput = document.getElementById('leefgebiedSearchInput');
	if (leefgebiedSearchInput) {
		leefgebiedSearchInput.addEventListener('input', filterLeefgebieden);
	}
</script>
