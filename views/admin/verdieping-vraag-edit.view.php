<?php
$sidebar = [
	'meta_label' => 'Editor',
	'meta_value' => 'Verdiepingsvraag',
	'back_url' => appUrl('verdiepingsvragen'),
	'back_label' => 'Terug naar overzicht',
];
?>

<div class="admin-shell">
	<?php require __DIR__ . '/components/sidebar.view.php'; ?>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #eef9ff 100%);">
			<h1 class="topbar-title">Verdiepingsvraag bewerken</h1>
		</header>

		<main class="page-wrap">
			<section class="panel" style="max-width: 900px; border-color:#cbe6f5;">
				<div class="panel-header">
					<h2 class="panel-title">Formulier gids_verdieping_vragen</h2>
					<span class="badge badge-primary">Frontend only</span>
				</div>

				<form id="verdiepingForm" method="post" action="/verdiepingsvragen/save" class="space-y-4" data-table="gids_verdieping_vragen">
					<input type="hidden" name="VerdiepingsvraagID" id="VerdiepingsvraagID" value="">

					<div>
						<label for="Vraag" class="block text-sm font-semibold mb-1">Vraag *</label>
						<textarea name="Vraag" id="Vraag" rows="4" class="search-input" placeholder="Bijv. Wat is al geprobeerd om dit op te lossen?" required></textarea>
					</div>

					<div>
						<label for="AandachtspuntID" class="block text-sm font-semibold mb-1">AandachtspuntID *</label>
						<input type="number" name="AandachtspuntID" id="AandachtspuntID" class="search-input" min="1" step="1" placeholder="Koppeling naar gids_aandachtspunt" required>
					</div>

					<div class="flex flex-col sm:flex-row gap-3 pt-2">
						<button type="submit" class="btn" style="background:#0f6d99;color:#fff;">Opslaan</button>
						<a href="/verdiepingsvragen" class="btn btn-secondary">Annuleren</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</div>