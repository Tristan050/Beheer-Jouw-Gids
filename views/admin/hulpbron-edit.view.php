<?php
$sidebar = [
	'meta_label' => 'Editor',
	'meta_value' => 'Hulpbron',
	'back_url' => appUrl('hulpbronnen'),
	'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
	<div class="flex min-h-screen">
		<?php require __DIR__ . '/components/sidebar.view.php'; ?>

		<div class="flex min-h-screen flex-1 flex-col">
			<header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
				<h1 class="text-2xl font-semibold tracking-tight">Hulpbron bewerken</h1>
			</header>

			<main class="flex-1 space-y-6 px-6 py-6">
				<?php if (!empty($data['form_error'])): ?>
					<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
				<?php endif; ?>

				<section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
					<div class="flex flex-wrap items-center justify-between gap-3">
						<h2 class="text-lg font-semibold">Formulier gids_hulpbron</h2>
						<span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['HulpbronID']) ? 'Bewerken' : 'Nieuw'; ?></span>
					</div>

					<form id="hulpbronForm" method="post" action="<?= htmlspecialchars(appUrl('hulpbron-save')) ?>" class="mt-6 space-y-4" data-table="gids_hulpbron">
						<?= CSRF::token() ?>
						<input type="hidden" name="HulpbronID" id="HulpbronID" value="<?= htmlspecialchars((string) ($data['form_values']['HulpbronID'] ?? '')) ?>">

						<div>
							<label for="Hulpbron" class="block text-sm font-semibold text-slate-700">Hulpbron naam *</label>
							<input type="text" name="Hulpbron" id="Hulpbron" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. Iemand uit mijn omgeving" value="<?= htmlspecialchars((string) ($data['form_values']['Hulpbron'] ?? '')) ?>" required>
						</div>

						<div>
							<label for="Toelichting" class="block text-sm font-semibold text-slate-700">Toelichting</label>
							<textarea name="Toelichting" id="Toelichting" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Optionele toelichting..."><?= htmlspecialchars((string) ($data['form_values']['Toelichting'] ?? '')) ?></textarea>
						</div>

						<div>
							<fieldset class="rounded-lg border border-slate-200 p-4">
								<legend class="block text-sm font-semibold text-slate-700 -ml-1 px-2">Hulpbron toevoegen aan leefgebieden *</legend>
								<div class="mt-4 space-y-2">
									<?php
									$selectedLeefgebieden = (array) ($data['form_values']['selected_leefgebieden'] ?? []);
									$leefgebieden = $data['leefgebieden'] ?? [];
									
									if (empty($leefgebieden)):
									?>
										<p class="text-sm text-slate-500">Geen leefgebieden beschikbaar.</p>
									<?php else: ?>
										<?php foreach ($leefgebieden as $lg): ?>
											<?php
											$lgId = (int) ($lg['id'] ?? 0);
											$lgName = (string) ($lg['name'] ?? '');
											$isChecked = in_array($lgId, $selectedLeefgebieden, true);
											?>
											<label class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
												<input 
													type="checkbox" 
													name="selected_leefgebieden[]" 
													value="<?= $lgId ?>" 
													<?= $isChecked ? 'checked' : '' ?>
													class="h-4 w-4 rounded border-slate-300 text-[#A53714] focus:ring-[#A53714]"
												>
												<span class="text-sm font-medium text-slate-700"><?= htmlspecialchars($lgName) ?></span>
											</label>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</fieldset>
						</div>

						<div class="flex flex-col gap-3 pt-2 sm:flex-row">
							<button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
							<a href="<?= htmlspecialchars(appUrl('hulpbronnen')) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
						</div>
					</form>
				</section>
			</main>
		</div>
	</div>
</div>
