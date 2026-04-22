<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Editor: <strong>Leefgebied</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/leefgebieden" class="btn btn-secondary w-full">Terug naar overzicht</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #fff7ef 100%);">
			<h1 class="topbar-title">Leefgebied bewerken</h1>
		</header>

		<main class="page-wrap">
			<section class="panel" style="max-width: 900px; border-color:#f1d9cc;">
				<div class="panel-header">
					<h2 class="panel-title">Formulier gids_leefgebied</h2>
					<span class="badge badge-primary">Frontend only</span>
				</div>

				<form id="leefgebiedForm" method="post" action="/leefgebieden/save" class="space-y-4" data-table="gids_leefgebied">
					<input type="hidden" name="LeefgebiedID" id="LeefgebiedID" value="">

					<div>
						<label for="Naam_leefgebied" class="block text-sm font-semibold mb-1">Naam_leefgebied *</label>
						<input type="text" name="Naam_leefgebied" id="Naam_leefgebied" class="search-input" placeholder="Bijv. Wonen" required>
					</div>

					<div>
						<label for="beschrijving_leefgebied" class="block text-sm font-semibold mb-1">beschrijving_leefgebied</label>
						<textarea name="beschrijving_leefgebied" id="beschrijving_leefgebied" rows="5" class="search-input" placeholder="Korte omschrijving van dit leefgebied..."></textarea>
					</div>

					<div>
						<label for="Sort_order" class="block text-sm font-semibold mb-1">Sort_order</label>
						<input type="number" name="Sort_order" id="Sort_order" class="search-input" min="0" step="1" placeholder="10">
					</div>

					<div class="flex flex-col sm:flex-row gap-3 pt-2">
						<button type="submit" class="btn" style="background:#A53714;color:#fff;">Opslaan</button>
						<a href="/leefgebieden" class="btn btn-secondary">Annuleren</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</div>
