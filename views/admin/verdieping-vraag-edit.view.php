<div class="admin-shell">
	<aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
		<div class="sidebar-brand">Jouw-Gids Beheer</div>
		<div class="sidebar-user">Editor: <strong>Verdiepingsvraag</strong></div>
		<nav class="space-y-2 mt-3" aria-label="Navigatie modules">
			<a href="/verdiepingsvragen" class="btn btn-secondary w-full">Terug naar overzicht</a>
		</nav>
	</aside>

	<div class="admin-content">
		<header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #eef9ff 100%);">
			<h1 class="topbar-title">Verdiepingsvraag bewerken</h1>
		</header>

		<main class="page-wrap">
			<?php if (!empty($data['form_error'])): ?>
				<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
			<?php endif; ?>

			<section class="panel" style="max-width: 900px; border-color:#cbe6f5;">
				<div class="panel-header">
					<h2 class="panel-title">Formulier gids_verdieping_vragen</h2>
					<span class="badge badge-primary"><?= !empty($data['form_values']['VerdiepingsvraagID']) ? 'Bewerken' : 'Nieuw'; ?></span>
				</div>

				<form id="verdiepingForm" method="post" action="<?= htmlspecialchars(appUrl('verdieping-vraag-save')) ?>" class="space-y-4" data-table="gids_verdieping_vragen">
					<?= CSRF::token() ?>
					<input type="hidden" name="VerdiepingsvraagID" id="VerdiepingsvraagID" value="<?= htmlspecialchars((string) ($data['form_values']['VerdiepingsvraagID'] ?? '')) ?>">

					<div>
						<label for="Vraag" class="block text-sm font-semibold mb-1">Vraag *</label>
						<textarea name="Vraag" id="Vraag" rows="4" class="search-input" placeholder="Bijv. Wat is al geprobeerd om dit op te lossen?" required><?= htmlspecialchars((string) ($data['form_values']['Vraag'] ?? '')) ?></textarea>
					</div>

					<div>
						<label for="AandachtspuntID" class="block text-sm font-semibold mb-1">AandachtspuntID *</label>
						<select name="AandachtspuntID" id="AandachtspuntID" class="search-input" required>
							<option value="">Selecteer een aandachtspunt</option>
							<?php foreach (($data['aandachtspunten'] ?? []) as $aandachtspunt): ?>
								<?php
								$optionId = (int) ($aandachtspunt['id'] ?? 0);
								$selectedAandachtspuntId = (int) ($data['form_values']['AandachtspuntID'] ?? 0);
								?>
								<option value="<?= $optionId ?>" <?= $selectedAandachtspuntId === $optionId ? 'selected' : '' ?>>
									<?= htmlspecialchars((string) ($aandachtspunt['name'] ?? '')) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="flex flex-col sm:flex-row gap-3 pt-2">
						<button type="submit" class="btn" style="background:#0f6d99;color:#fff;">Opslaan</button>
						<a href="<?= htmlspecialchars(appUrl('verdiepingsvragen')) ?>" class="btn btn-secondary">Annuleren</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</div>
