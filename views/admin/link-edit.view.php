<?php
$sidebar = [
    'meta_label' => 'Editor',
    'meta_value' => 'Link',
    'back_url' => appUrl('links'),
    'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Link bewerken</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['form_error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
                <?php endif; ?>

                <section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold">Formulier link</h2>
                        <span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['LinkID']) ? 'Bewerken' : 'Nieuw'; ?></span>
                    </div>

                    <form id="linkForm" method="post" action="<?= htmlspecialchars(appUrl('link-save')) ?>" class="mt-6 space-y-4">
                        <?= CSRF::token() ?>
                        <input type="hidden" name="LinkID" id="LinkID" value="<?= htmlspecialchars((string) ($data['form_values']['LinkID'] ?? '')) ?>">

                        <div>
                            <label for="titel" class="block text-sm font-semibold text-slate-700">Titel *</label>
                            <input type="text" name="titel" id="titel" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. MantelzorgNL" value="<?= htmlspecialchars((string) ($data['form_values']['titel'] ?? '')) ?>" required>
                        </div>

                        <div>
                            <label for="url" class="block text-sm font-semibold text-slate-700">URL *</label>
                            <input type="url" name="url" id="url" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="https://www.voorbeeld.nl" value="<?= htmlspecialchars((string) ($data['form_values']['url'] ?? '')) ?>" required>
                        </div>

                        <div>
                            <label for="belangrijk_bericht" class="block text-sm font-semibold text-slate-700">Belangrijk bericht</label>
                            <textarea name="belangrijk_bericht" id="belangrijk_bericht" rows="5" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Optioneel bericht dat bij deze link hoort."><?= htmlspecialchars((string) ($data['form_values']['belangrijk_bericht'] ?? '')) ?></textarea>
                        </div>

                        <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 shadow-sm">
                            <input type="checkbox" name="toon_popup" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#A53714] focus:ring-[#A53714]/30" <?= (string) ($data['form_values']['toon_popup'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <span>
                                <span class="block font-semibold text-slate-800">Toon pop-up</span>
                                <span class="mt-0.5 block text-slate-600">Gebruik het belangrijke bericht als extra melding voordat iemand de link opent.</span>
                            </span>
                        </label>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
                            <a href="<?= htmlspecialchars(appUrl('links')) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</div>
