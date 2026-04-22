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
					<a href="/functies/edit" class="btn" style="background:#1f6f4a;color:#fff;">Nieuwe functie</a>
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
							<tr data-search="1 1 inkomensondersteuning hulp bij inkomen 10">
								<td>1</td>
								<td>1</td>
								<td>Inkomensondersteuning</td>
								<td>Hulp bij inkomen.</td>
								<td>10</td>
								<td class="flex gap-2 py-2">
									<a href="/functies/edit?id=1" class="btn btn-secondary">Bewerken</a>
									<button type="button" class="btn btn-secondary">Verwijderen</button>
								</td>
							</tr>
							<tr data-search="2 2 dagbesteding meedoen en structuur 20">
								<td>2</td>
								<td>2</td>
								<td>Dagbesteding</td>
								<td>Meedoen en structuur.</td>
								<td>20</td>
								<td class="flex gap-2 py-2">
									<a href="/functies/edit?id=2" class="btn btn-secondary">Bewerken</a>
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
