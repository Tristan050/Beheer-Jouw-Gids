<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Editor: <strong>Functie</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/functies" class="btn btn-secondary w-full">Terug naar overzicht</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f4fbf7 100%);">
			<h1 class="topbar-title">Functie bewerken</h1>
		</header>

		<main class="page-wrap">
			<?php if (!empty($data['form_error'])): ?>
				<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
			<?php endif; ?>

			<section class="panel" style="max-width: 900px; border-color:#d5e8dc;">
				<div class="panel-header">
					<h2 class="panel-title">Formulier gids_functie</h2>
					<span class="badge badge-primary"><?php echo !empty($data['form_values']['FunctieID']) ? 'Bewerken' : 'Nieuw'; ?></span>
				</div>

				<form id="functieForm" method="post" action="<?= htmlspecialchars(appUrl('functie-save')) ?>" class="space-y-4" data-table="gids_functie">
					<?= CSRF::token() ?>
					<input type="hidden" name="FunctieID" id="FunctieID" value="<?= htmlspecialchars((string) ($data['form_values']['FunctieID'] ?? '')) ?>">

					<div>
						<label for="LeefgebiedID" class="block text-sm font-semibold mb-1">LeefgebiedID *</label>
						<input type="number" name="LeefgebiedID" id="LeefgebiedID" class="search-input" min="1" step="1" placeholder="Koppeling naar gids_leefgebied" value="<?= htmlspecialchars((string) ($data['form_values']['LeefgebiedID'] ?? '')) ?>" required>
					</div>

					<div>
						<label for="Naam_functie" class="block text-sm font-semibold mb-1">Naam_functie *</label>
						<input type="text" name="Naam_functie" id="Naam_functie" class="search-input" placeholder="Bijv. Dagbesteding" value="<?= htmlspecialchars((string) ($data['form_values']['Naam_functie'] ?? '')) ?>" required>
					</div>

					<div>
						<label for="Beschrijving_functie" class="block text-sm font-semibold mb-1">Beschrijving_functie</label>
						<textarea name="Beschrijving_functie" id="Beschrijving_functie" rows="5" class="search-input" placeholder="Korte toelichting op de functie..."><?= htmlspecialchars((string) ($data['form_values']['Beschrijving_functie'] ?? '')) ?></textarea>
					</div>

					<div>
						<label for="Sort_order" class="block text-sm font-semibold mb-1">Sort_order</label>
						<input type="number" name="Sort_order" id="Sort_order" class="search-input" min="0" step="1" placeholder="10" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>">
					</div>

					<div class="flex flex-col sm:flex-row gap-3 pt-2">
						<button type="submit" class="btn" style="background:#1f6f4a;color:#fff;">Opslaan</button>
						<a href="<?= htmlspecialchars(appUrl('functies')) ?>" class="btn btn-secondary">Annuleren</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</div>
