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
					<a href="/leefgebieden/edit" class="btn" style="background:#A53714;color:#fff;">Nieuw leefgebied</a>
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
							<tr data-search="1 wonen huisvesting en omgeving 10">
								<td>1</td>
								<td>Wonen</td>
								<td>Huisvesting en omgeving.</td>
								<td>10</td>
								<td class="flex gap-2 py-2">
									<a href="/leefgebieden/edit?id=1" class="btn btn-secondary">Bewerken</a>
									<button type="button" class="btn btn-secondary">Verwijderen</button>
								</td>
							</tr>
							<tr data-search="2 gezondheid lichamelijk en mentaal welzijn 20">
								<td>2</td>
								<td>Gezondheid</td>
								<td>Lichamelijk en mentaal welzijn.</td>
								<td>20</td>
								<td class="flex gap-2 py-2">
									<a href="/leefgebieden/edit?id=2" class="btn btn-secondary">Bewerken</a>
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
