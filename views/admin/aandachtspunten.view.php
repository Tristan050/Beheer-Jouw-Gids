<?php
$sidebar = [
	'meta_label' => 'Module',
	'meta_value' => 'Aandachtspunten',
	'active' => 'aandachtspunten',
];
?>

<div class="admin-shell">
	<?php require __DIR__ . '/components/sidebar.view.php'; ?>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f9f6ff 100%);">
			<h1 class="topbar-title">Aandachtspunten beheren</h1>
		</header>

		<main class="page-wrap">
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
					<a href="/aandachtspunten/edit" class="btn" style="background:#5c3b87;color:#fff;">Nieuw aandachtspunt</a>
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
								<th>FunctieID</th>
								<th>Aandachtspunt</th>
								<th>Sort_order</th>
								<th>Scan_tekst</th>
								<th>Advies_tekst</th>
								<th>Acties</th>
							</tr>
						</thead>
						<tbody id="aandachtspuntTableBody">
							<tr data-search="1 1 inkomen onvoldoende zicht op budget maak inkomsten en uitgaven inzichtelijk 10">
								<td>1</td>
								<td>1</td>
								<td>Onvoldoende zicht op budget</td>
								<td>10</td>
								<td>Maak inkomsten en uitgaven inzichtelijk.</td>
								<td>Plan een budgetgesprek.</td>
								<td class="flex gap-2 py-2">
									<a href="/aandachtspunten/edit?id=1" class="btn btn-secondary">Bewerken</a>
									<button type="button" class="btn btn-secondary">Verwijderen</button>
								</td>
							</tr>
							<tr data-search="2 2 gezondheid weinig sociale contacten bespreek netwerk en daginvulling stimuleer lokale activiteiten 20">
								<td>2</td>
								<td>2</td>
								<td>Weinig sociale contacten</td>
								<td>20</td>
								<td>Bespreek netwerk en daginvulling.</td>
								<td>Stimuleer lokale activiteiten.</td>
								<td class="flex gap-2 py-2">
									<a href="/aandachtspunten/edit?id=2" class="btn btn-secondary">Bewerken</a>
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
