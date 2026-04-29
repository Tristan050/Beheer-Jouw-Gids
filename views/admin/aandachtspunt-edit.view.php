<?php
$sidebar = [
    'meta_label' => 'Editor',
    'meta_value' => 'Aandachtspunt',
    'back_url' => appUrl('aandachtspunten'),
    'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Aandachtspunt bewerken</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['form_error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
                <?php endif; ?>

                <section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold">Formulier gids_aandachtspunt</h2>
                        <span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['AandachtspuntID']) ? 'Bewerken' : 'Nieuw'; ?></span>
                    </div>

                    <form id="aandachtspuntForm" method="post" action="<?= htmlspecialchars(appUrl('aandachtspunt-save')) ?>" class="mt-6 space-y-4" data-table="gids_aandachtspunt">
                        <?= CSRF::token() ?>
                        <input type="hidden" name="AandachtspuntID" id="AandachtspuntID" value="<?= htmlspecialchars((string) ($data['form_values']['AandachtspuntID'] ?? '')) ?>">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="FunctieID" class="block text-sm font-semibold text-slate-700">Functie *</label>
                                <select name="FunctieID" id="FunctieID" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" required>
                                    <option value="">Selecteer een functie</option>
                                    <?php foreach (($data['functies'] ?? []) as $functie): ?>
                                        <?php
                                        $optionId = (int) ($functie['id'] ?? 0);
                                        $selectedFunctieId = (int) ($data['form_values']['FunctieID'] ?? 0);
                                        ?>
                                        <option value="<?= $optionId ?>" <?= $selectedFunctieId === $optionId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars((string) ($functie['name'] ?? '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="Sort_order" class="block text-sm font-semibold text-slate-700">Sort_order</label>
                                <input type="number" name="Sort_order" id="Sort_order" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" min="0" step="1" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>">
                            </div>
                        </div>

                        <div>
                            <label for="Aandachtspunt" class="block text-sm font-semibold text-slate-700">Aandachtspunt *</label>
                            <input type="text" name="Aandachtspunt" id="Aandachtspunt" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. Onvoldoende zicht op budget" value="<?= htmlspecialchars((string) ($data['form_values']['Aandachtspunt'] ?? '')) ?>" required>
                        </div>

                        <div>
                            <label for="Toelichting" class="block text-sm font-semibold text-slate-700">Toelichting</label>
                            <textarea name="Toelichting" id="Toelichting" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Extra uitleg voor de beheerder..."><?= htmlspecialchars((string) ($data['form_values']['Toelichting'] ?? '')) ?></textarea>
                        </div>

                        <div>
                            <label for="Scan_tekst" class="block text-sm font-semibold text-slate-700">Scan_tekst</label>
                            <textarea name="Scan_tekst" id="Scan_tekst" rows="4" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Tekst die tijdens de scan getoond wordt..."><?= htmlspecialchars((string) ($data['form_values']['Scan_tekst'] ?? '')) ?></textarea>
                        </div>

                        <div>
                            <label for="Advies_tekst" class="block text-sm font-semibold text-slate-700">Advies_tekst</label>
                            <textarea name="Advies_tekst" id="Advies_tekst" rows="4" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Tekst met advies en vervolgstappen..."><?= htmlspecialchars((string) ($data['form_values']['Advies_tekst'] ?? '')) ?></textarea>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
                            <a href="<?= htmlspecialchars(appUrl('aandachtspunten')) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</div>