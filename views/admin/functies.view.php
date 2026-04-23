<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Module: <strong>Functies</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/admin" class="btn btn-secondary w-full">Dashboard</a>
			<a href="/leefgebieden" class="btn btn-secondary w-full">Leefgebieden</a>
			<a href="/functies" class="btn w-full" style="background:#A53714;color:#fff;">Functies</a>
			<a href="/aandachtspunten" class="btn btn-secondary w-full">Aandachtspunten</a>
			<a href="/verdiepingsvragen" class="btn btn-secondary w-full">Verdiepingsvragen</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f4fbf7 100%);">
			<h1 class="topbar-title">Functies beheren</h1>
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
				<span class="current">Functies</span>
			</nav>

			<section class="panel" style="border-color:#d5e8dc; background: linear-gradient(150deg, #fff 0%, #f5fcf8 100%);">
				<div class="panel-header">
					<div>
						<h2 class="panel-title">Overzicht gids_functie</h2>
						<p class="text-sm text-slate-600 mt-1">Veldkoppeling: <strong>FunctieID</strong>, <strong>LeefgebiedID</strong>, <strong>Naam_functie</strong>, <strong>Beschrijving_functie</strong>, <strong>Sort_order</strong>.</p>
					</div>
					<a href="<?= htmlspecialchars(appUrl('functie-edit')) ?>" class="btn" style="background:#1f6f4a;color:#fff;">Nieuwe functie</a>
				</div>

				<div class="toolbar">
					<div class="toolbar-actions">
						<div class="search-wrap" style="flex:1;">
							<input id="functieSearchInput" type="text" class="search-input" placeholder="Zoek op functie of leefgebied..." />
							<span class="search-icon" aria-hidden="true">&#128269;</span>
						</div>
						<button type="button" class="btn btn-secondary" onclick="document.getElementById('functieSearchInput').value=''; filterFuncties();">Wissen</button>
					</div>
				</div>

				<div class="table-wrap">
					<table class="data-table" id="functieTable" data-source-table="gids_functie">
						<thead>
							<tr>
								<th>FunctieID</th>
								<th>LeefgebiedID</th>
								<th>Naam_functie</th>
								<th>Beschrijving_functie</th>
								<th>Sort_order</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="functieTableBody">
							<?php if (!empty($data['items'])): ?>
								<?php foreach ($data['items'] as $row): ?>
									<tr data-search="<?= htmlspecialchars((string) ($row['search'] ?? '')) ?>">
										<td><?= (int) ($row['id'] ?? 0) ?></td>
										<td><?= (int) ($row['leefgebied_id'] ?? 0) ?></td>
										<td><?= htmlspecialchars((string) ($row['name'] ?? '')) ?></td>
										<td><?= htmlspecialchars((string) ($row['description'] ?? '')) ?></td>
										<td><?= (int) ($row['sort_order'] ?? 0) ?></td>
										<td class="flex gap-2 py-2">
											<a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('functie-edit'))) ?>" class="btn btn-secondary">Bewerken</a>
											<form method="post" action="<?= htmlspecialchars(appUrl('functie-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze functie wilt verwijderen?');" style="display:inline;">
												<?= CSRF::token() ?>
												<input type="hidden" name="FunctieID" value="<?= (int) ($row['id'] ?? 0) ?>">
												<button type="submit" class="btn btn-secondary">Verwijderen</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="6" class="text-center py-4">Nog geen functies gevonden.</td>
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
	function filterFuncties() {
		const input = document.getElementById('functieSearchInput');
		const rows = Array.from(document.querySelectorAll('#functieTableBody tr'));
		const term = (input && input.value ? input.value : '').toLowerCase().trim();

		rows.forEach((row) => {
			const haystack = (row.getAttribute('data-search') || '').toLowerCase();
			row.classList.toggle('hidden', term !== '' && !haystack.includes(term));
		});
	}

	const functieSearchInput = document.getElementById('functieSearchInput');
	if (functieSearchInput) {
		functieSearchInput.addEventListener('input', filterFuncties);
	}
</script>
