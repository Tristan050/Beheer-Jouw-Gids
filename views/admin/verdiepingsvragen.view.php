<?php
$sidebar = [
	'meta_label' => 'Module',
	'meta_value' => 'Verdiepingsvragen',
	'active' => 'verdiepingsvragen',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
	<div class="flex min-h-screen">
		<?php require __DIR__ . '/components/sidebar.view.php'; ?>

		<div class="flex min-h-screen flex-1 flex-col">
			<header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
				<h1 class="text-2xl font-semibold tracking-tight">Verdiepingsvragen beheren</h1>
			</header>

			<main class="flex-1 space-y-6 px-6 py-6">
				<?php if (!empty($data['error'])): ?>
					<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
				<?php endif; ?>

				<?php if (!empty($data['success'])): ?>
					<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
				<?php endif; ?>

				<nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
					<a href="/admin" class="font-medium text-slate-600 hover:text-slate-900">Dashboard</a>
					<span class="text-slate-400">/</span>
					<span class="font-medium text-slate-700">Verdiepingsvragen</span>
				</nav>

				<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
					<div class="flex flex-wrap items-start justify-between gap-4">
						<div>
							<h2 class="text-lg font-semibold">Overzicht gids_verdieping_vragen</h2>
							<p class="mt-1 text-sm text-slate-600">Veldkoppeling: <strong>VerdiepingsvraagID</strong>, <strong>Vraag</strong>, <strong>AandachtspuntID</strong>.</p>
						</div>
						<a href="<?= htmlspecialchars(appUrl('verdieping-vraag-edit')) ?>" class="inline-flex items-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">
							<i class="fas fa-plus" aria-hidden="true"></i>
							Nieuwe vraag
						</a>
					</div>

					<div class="mt-4 flex flex-wrap items-center gap-3">
						<div class="relative min-w-[220px] flex-1">
							<input id="verdiepingSearchInput" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pr-9 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Zoek op vraag of aandachtspunt..." />
							<span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true">&#128269;</span>
						</div>
						<button type="button" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50" onclick="document.getElementById('verdiepingSearchInput').value=''; filterVerdiepingsvragen();">Wissen</button>
					</div>

					<div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
						<table class="min-w-full divide-y divide-slate-200" id="verdiepingTable" data-source-table="gids_verdieping_vragen">
							<thead class="bg-slate-100 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3">VerdiepingsvraagID</th>
									<th class="px-4 py-3">Vraag</th>
									<th class="px-4 py-3">Aandachtspunt</th>
									<th class="px-4 py-3">Acties</th>
								</tr>
							</thead>
							<tbody id="verdiepingTableBody" class="divide-y divide-slate-200 bg-white">
								<?php if (!empty($data['items'])): ?>
									<?php foreach ($data['items'] as $row): ?>
										<tr data-search="<?= htmlspecialchars((string) ($row['search'] ?? '')) ?>">
											<td class="px-4 py-3 text-sm text-slate-700"><?= (int) ($row['id'] ?? 0) ?></td>
											<td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['vraag'] ?? '')) ?></td>
											<td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['aandachtspunt_name'] ?? '')) ?></td>
											<td class="px-4 py-3">
												<div class="flex flex-wrap gap-2">
													<a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('verdieping-vraag-edit'))) ?>" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Bewerken</a>
													<form method="post" action="<?= htmlspecialchars(appUrl('verdieping-vraag-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze verdiepingsvraag wilt verwijderen?');">
														<?= CSRF::token() ?>
														<input type="hidden" name="VerdiepingsvraagID" value="<?= (int) ($row['id'] ?? 0) ?>">
														<button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 shadow-sm transition hover:bg-red-50">Verwijderen</button>
													</form>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Nog geen verdiepingsvragen gevonden.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</section>
			</main>
		</div>
	</div>
</div>

<script>
	function filterVerdiepingsvragen() {
		const input = document.getElementById('verdiepingSearchInput');
		const rows = Array.from(document.querySelectorAll('#verdiepingTableBody tr'));
		const term = (input && input.value ? input.value : '').toLowerCase().trim();

		rows.forEach((row) => {
			const haystack = (row.getAttribute('data-search') || '').toLowerCase();
			row.classList.toggle('hidden', term !== '' && !haystack.includes(term));
		});
	}

	const verdiepingSearchInput = document.getElementById('verdiepingSearchInput');
	if (verdiepingSearchInput) {
		verdiepingSearchInput.addEventListener('input', filterVerdiepingsvragen);
	}
</script>