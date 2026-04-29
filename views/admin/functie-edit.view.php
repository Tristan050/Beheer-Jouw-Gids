<?php
$sidebar = [
	'meta_label' => 'Editor',
	'meta_value' => 'Functie',
	'back_url' => appUrl('functies'),
	'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
	<div class="flex min-h-screen">
		<?php require __DIR__ . '/components/sidebar.view.php'; ?>

		<div class="flex min-h-screen flex-1 flex-col">
			<header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
				<h1 class="text-2xl font-semibold tracking-tight">Functie bewerken</h1>
			</header>

			<main class="flex-1 space-y-6 px-6 py-6">
				<?php if (!empty($data['form_error'])): ?>
					<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
				<?php endif; ?>

				<section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
					<div class="flex flex-wrap items-center justify-between gap-3">
						<h2 class="text-lg font-semibold">Formulier gids_functie</h2>
						<span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['FunctieID']) ? 'Bewerken' : 'Nieuw'; ?></span>
					</div>

					<form id="functieForm" method="post" action="<?= htmlspecialchars(appUrl('functie-save')) ?>" class="mt-6 space-y-4" data-table="gids_functie">
						<?= CSRF::token() ?>
						<input type="hidden" name="FunctieID" id="FunctieID" value="<?= htmlspecialchars((string) ($data['form_values']['FunctieID'] ?? '')) ?>">

						<div>
							<label for="LeefgebiedID" class="block text-sm font-semibold text-slate-700">LeefgebiedID *</label>
							<select name="LeefgebiedID" id="LeefgebiedID" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" required>
								<option value="">Selecteer een leefgebied</option>
								<?php foreach (($data['leefgebieden'] ?? []) as $leefgebied): ?>
									<?php
									$optionId = (int) ($leefgebied['id'] ?? 0);
									$selectedLeefgebiedId = (int) ($data['form_values']['LeefgebiedID'] ?? 0);
									?>
									<option value="<?= $optionId ?>" <?= $selectedLeefgebiedId === $optionId ? 'selected' : '' ?>>
										<?= htmlspecialchars((string) ($leefgebied['name'] ?? '')) ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="Naam_functie" class="block text-sm font-semibold text-slate-700">Naam_functie *</label>
							<input type="text" name="Naam_functie" id="Naam_functie" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. Dagbesteding" value="<?= htmlspecialchars((string) ($data['form_values']['Naam_functie'] ?? '')) ?>" required>
						</div>

						<div>
							<label for="Beschrijving_functie" class="block text-sm font-semibold text-slate-700">Beschrijving_functie</label>
							<textarea name="Beschrijving_functie" id="Beschrijving_functie" rows="5" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Korte toelichting op de functie..."><?= htmlspecialchars((string) ($data['form_values']['Beschrijving_functie'] ?? '')) ?></textarea>
						</div>

						<div>
							<label for="Sort_order" class="block text-sm font-semibold text-slate-700">Sort_order</label>
							<input type="number" name="Sort_order" id="Sort_order" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" min="0" step="1" placeholder="10" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>">
						</div>

						<div class="flex flex-col gap-3 pt-2 sm:flex-row">
							<button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
							<a href="<?= htmlspecialchars(appUrl('functies')) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
						</div>
					</form>
				</section>
			</main>
		</div>
	</div>
</div>