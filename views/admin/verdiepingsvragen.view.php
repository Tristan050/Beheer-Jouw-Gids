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
					<a href="/verdiepingsvragen/edit" class="btn" style="background:#0f6d99;color:#fff;">Nieuwe vraag</a>
				</div>

				<div class="toolbar">
					<div class="toolbar-actions">
						<div class="search-wrap" style="flex:1;">
							<input id="verdiepingSearchInput" type="text" class="search-input" placeholder="Zoek op vraag of aandachtspuntID..." />
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
								<th>AandachtspuntID</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="verdiepingTableBody">
							<tr data-search="1 wat is al geprobeerd 1">
								<td>1</td>
								<td>Wat is al geprobeerd om dit aan te pakken?</td>
								<td>1</td>
								<td class="flex gap-2 py-2">
									<a href="/verdiepingsvragen/edit?id=1" class="btn btn-secondary">Bewerken</a>
									<button type="button" class="btn btn-secondary">Verwijderen</button>
								</td>
							</tr>
							<tr data-search="2 wie in het netwerk kan ondersteunen 2">
								<td>2</td>
								<td>Wie in het netwerk kan hier ondersteuning bieden?</td>
								<td>2</td>
								<td class="flex gap-2 py-2">
									<a href="/verdiepingsvragen/edit?id=2" class="btn btn-secondary">Bewerken</a>
									<button type="button" class="btn btn-secondary">Verwijderen</button>
								</td>
							</tr>
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