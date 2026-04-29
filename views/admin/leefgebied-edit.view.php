<?php
$sidebar = [
	'meta_label' => 'Editor',
	'meta_value' => 'Leefgebied',
	'back_url' => appUrl('leefgebieden'),
	'back_label' => 'Terug naar overzicht',
];
?>

<div class="admin-shell">
	<?php require __DIR__ . '/components/sidebar.view.php'; ?>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #fff7ef 100%);">
			<h1 class="topbar-title">Leefgebied bewerken</h1>
		</header>

		<main class="page-wrap">
			<?php if (!empty($data['form_error'])): ?>
				<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
			<?php endif; ?>

			<section class="panel" style="max-width: 900px; border-color:#f1d9cc;">
				<div class="panel-header">
					<h2 class="panel-title">Formulier gids_leefgebied</h2>
					<span class="badge badge-primary"><?php echo !empty($data['form_values']['LeefgebiedID']) ? 'Bewerken' : 'Nieuw'; ?></span>
				</div>

				<form id="leefgebiedForm" method="post" action="<?= htmlspecialchars(appUrl('leefgebied-save')) ?>" class="space-y-4" data-table="gids_leefgebied">
					<?= CSRF::token() ?>
					<input type="hidden" name="LeefgebiedID" id="LeefgebiedID" value="<?= htmlspecialchars((string) ($data['form_values']['LeefgebiedID'] ?? '')) ?>">

					<div>
						<label for="Naam_leefgebied" class="block text-sm font-semibold mb-1">Naam_leefgebied *</label>
						<input type="text" name="Naam_leefgebied" id="Naam_leefgebied" class="search-input" placeholder="Bijv. Wonen" value="<?= htmlspecialchars((string) ($data['form_values']['Naam_leefgebied'] ?? '')) ?>" required>
					</div>

					<div>
						<label for="beschrijving_leefgebied" class="block text-sm font-semibold mb-1">beschrijving_leefgebied</label>
						<textarea name="beschrijving_leefgebied" id="beschrijving_leefgebied" rows="5" class="search-input" placeholder="Korte omschrijving van dit leefgebied..."><?= htmlspecialchars((string) ($data['form_values']['beschrijving_leefgebied'] ?? '')) ?></textarea>
					</div>

					<div>
						<label for="Sort_order" class="block text-sm font-semibold mb-1">Sort_order</label>
						<input type="number" name="Sort_order" id="Sort_order" class="search-input" min="0" step="1" placeholder="10" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>">
					</div>

					<div class="flex flex-col sm:flex-row gap-3 pt-2">
						<button type="submit" class="btn" style="background:#A53714;color:#fff;">Opslaan</button>
						<a href="<?= htmlspecialchars(appUrl('leefgebieden')) ?>" class="btn btn-secondary">Annuleren</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</div>
