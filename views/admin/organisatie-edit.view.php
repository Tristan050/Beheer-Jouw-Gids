<?php
$sidebar = [
    'meta_label' => 'Editor',
    'meta_value' => 'Organisatie',
    'back_url' => appUrl('organisaties'),
    'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Organisatie bewerken</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['form_error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
                <?php endif; ?>

                <section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold">Formulier organisatie</h2>
                        <span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['OrganisatieID']) ? 'Bewerken' : 'Nieuw'; ?></span>
                    </div>

                    <form id="organisatieForm" method="post" action="<?= htmlspecialchars(appUrl('organisatie-save')) ?>" class="mt-6 space-y-4">
                        <?= CSRF::token() ?>
                        <input type="hidden" name="OrganisatieID" id="OrganisatieID" value="<?= htmlspecialchars((string) ($data['form_values']['OrganisatieID'] ?? '')) ?>">

                        <div>
                            <label for="Naam" class="block text-sm font-semibold text-slate-700">Naam *</label>
                            <input type="text" name="Naam" id="Naam" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. Zorgorganisatie X" value="<?= htmlspecialchars((string) ($data['form_values']['Naam'] ?? '')) ?>" required>
                        </div>

                        <div>
                            <label for="Adres" class="block text-sm font-semibold text-slate-700">Adres</label>
                            <input type="text" name="Adres" id="Adres" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Straat + huisnummer" value="<?= htmlspecialchars((string) ($data['form_values']['Adres'] ?? '')) ?>">
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="Telefoon" class="block text-sm font-semibold text-slate-700">Telefoon</label>
                                <input type="text" name="Telefoon" id="Telefoon" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="Bijv. 0101234567" value="<?= htmlspecialchars((string) ($data['form_values']['Telefoon'] ?? '')) ?>">
                            </div>
                            <div>
                                <label for="Email" class="block text-sm font-semibold text-slate-700">Email</label>
                                <input type="email" name="Email" id="Email" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="contact@organisatie.nl" value="<?= htmlspecialchars((string) ($data['form_values']['Email'] ?? '')) ?>">
                            </div>
                        </div>

                        <div>
                            <label for="Website" class="block text-sm font-semibold text-slate-700">Website</label>
                            <input type="url" name="Website" id="Website" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="https://www.organisatie.nl" value="<?= htmlspecialchars((string) ($data['form_values']['Website'] ?? '')) ?>">
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
                            <a href="<?= htmlspecialchars(appUrl('organisaties')) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</div>