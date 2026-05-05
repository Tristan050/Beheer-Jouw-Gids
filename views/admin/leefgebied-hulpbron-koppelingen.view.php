<?php
$sidebar = [
    'meta_label' => 'Module',
    'meta_value' => 'Leefgebied-Hulpbron koppelingen',
    'active' => 'leefgebied-hulpbron-koppelingen',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Hulpbronnen koppelen aan leefgebieden</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
                <?php endif; ?>

                <?php if (!empty($data['form_error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
                <?php endif; ?>

                <?php if (!empty($data['success'])): ?>
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
                <?php endif; ?>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Koppelingen beheren</h2>
                    <form method="post" action="<?= htmlspecialchars(appUrl('leefgebied-hulpbron-koppeling-save')) ?>" class="mt-4 space-y-4">
                        <?= CSRF::token() ?>

                        <div>
                            <label for="LeefgebiedID" class="block text-sm font-semibold text-slate-700">Leefgebied *</label>
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
                            <p class="text-sm font-semibold text-slate-700">Hulpbronnen</p>
                            <?php
                            $selectedHulpbronIds = array_map('intval', (array) ($data['form_values']['HulpbronIDs'] ?? []));
                            ?>
                            <div class="mt-3 space-y-2">
                                <?php foreach (($data['hulpbronnen'] ?? []) as $hulpbron): ?>
                                    <?php $hulpbronId = (int) ($hulpbron['id'] ?? 0); ?>
                                    <label class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm hover:bg-slate-50 cursor-pointer">
                                        <input type="checkbox" name="HulpbronIDs[]" value="<?= $hulpbronId ?>" class="h-4 w-4 rounded border-slate-300 text-[#A53714] focus:ring-[#A53714]/30" <?= in_array($hulpbronId, $selectedHulpbronIds, true) ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars((string) ($hulpbron['name'] ?? '')) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600">Laat alles uitgevinkt om alle koppelingen voor het gekozen leefgebied te verwijderen.</p>

                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Koppelingen opslaan</button>
                    </form>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Bestaande koppelingen</h2>
                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200" id="koppelingTable">
                            <thead class="bg-slate-100 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Leefgebied</th>
                                    <th class="px-4 py-3">Hulpbron</th>
                                    <th class="px-4 py-3">Sort order</th>
                                    <th class="px-4 py-3">Acties</th>
                                </tr>
                            </thead>
                            <tbody id="koppelingTableBody" class="divide-y divide-slate-200 bg-white">
                                <?php if (!empty($data['items'])): ?>
                                    <?php foreach ($data['items'] as $row): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['leefgebied_name'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['hulpbron_name'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= (int) ($row['sort_order'] ?? 0) ?></td>
                                            <td class="px-4 py-3">
                                                <form method="post" action="<?= htmlspecialchars(appUrl('leefgebied-hulpbron-koppeling-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze koppeling wilt verwijderen?');">
                                                    <?= CSRF::token() ?>
                                                    <input type="hidden" name="LeefgebiedID" value="<?= (int) ($row['leefgebied_id'] ?? 0) ?>">
                                                    <input type="hidden" name="HulpbronID" value="<?= (int) ($row['hulpbron_id'] ?? 0) ?>">
                                                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 shadow-sm transition hover:bg-red-50">Verwijderen</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Nog geen koppelingen gevonden.</td>
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
