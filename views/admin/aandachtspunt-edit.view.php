<?php
$sidebar = [
    'meta_label' => 'Editor',
    'meta_value' => 'Aandachtspunt',
    'back_url' => appUrl('aandachtspunten'),
    'back_label' => 'Terug naar overzicht',
];
?>

<div class="admin-shell">
    <?php require __DIR__ . '/components/sidebar.view.php'; ?>

    <div class="admin-content">
        <header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f9f6ff 100%);">
            <h1 class="topbar-title">Aandachtspunt bewerken</h1>
        </header>

        <main class="page-wrap">
            <?php if (!empty($data['form_error'])): ?>
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
            <?php endif; ?>

            <section class="panel" style="max-width: 980px; border-color:#e4dcf2;">
                <div class="panel-header">
                    <h2 class="panel-title">Formulier gids_aandachtspunt</h2>
                    <span class="badge badge-primary"><?= !empty($data['form_values']['AandachtspuntID']) ? 'Bewerken' : 'Nieuw'; ?></span>
                </div>

                <form id="aandachtspuntForm" method="post" action="<?= htmlspecialchars(appUrl('aandachtspunt-save')) ?>" class="space-y-4" data-table="gids_aandachtspunt">
                    <?= CSRF::token() ?>
                    <input type="hidden" name="AandachtspuntID" id="AandachtspuntID" value="<?= htmlspecialchars((string) ($data['form_values']['AandachtspuntID'] ?? '')) ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="FunctieID" class="block text-sm font-semibold mb-1">Functie *</label>
                            <select name="FunctieID" id="FunctieID" class="search-input" required>
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
                            <label for="Sort_order" class="block text-sm font-semibold mb-1">Sort_order</label>
                            <input type="number" name="Sort_order" id="Sort_order" class="search-input" min="0" step="1" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>">
                        </div>
                    </div>

                    <div>
                        <label for="Aandachtspunt" class="block text-sm font-semibold mb-1">Aandachtspunt *</label>
                        <input type="text" name="Aandachtspunt" id="Aandachtspunt" class="search-input" placeholder="Bijv. Onvoldoende zicht op budget" value="<?= htmlspecialchars((string) ($data['form_values']['Aandachtspunt'] ?? '')) ?>" required>
                    </div>

                    <div>
                        <label for="Toelichting" class="block text-sm font-semibold mb-1">Toelichting</label>
                        <textarea name="Toelichting" id="Toelichting" rows="3" class="search-input" placeholder="Extra uitleg voor de beheerder..."><?= htmlspecialchars((string) ($data['form_values']['Toelichting'] ?? '')) ?></textarea>
                    </div>

                    <div>
                        <label for="Scan_tekst" class="block text-sm font-semibold mb-1">Scan_tekst</label>
                        <textarea name="Scan_tekst" id="Scan_tekst" rows="4" class="search-input" placeholder="Tekst die tijdens de scan getoond wordt..."><?= htmlspecialchars((string) ($data['form_values']['Scan_tekst'] ?? '')) ?></textarea>
                    </div>

                    <div>
                        <label for="Advies_tekst" class="block text-sm font-semibold mb-1">Advies_tekst</label>
                        <textarea name="Advies_tekst" id="Advies_tekst" rows="4" class="search-input" placeholder="Tekst met advies en vervolgstappen..."><?= htmlspecialchars((string) ($data['form_values']['Advies_tekst'] ?? '')) ?></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="btn" style="background:#5c3b87;color:#fff;">Opslaan</button>
                        <a href="<?= htmlspecialchars(appUrl('aandachtspunten')) ?>" class="btn btn-secondary">Annuleren</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</div>