<h1>Organisaties koppelen aan verdiepingsvragen</h1>

<?php if (!empty($data['error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
<?php endif; ?>

<?php if (!empty($data['form_error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
<?php endif; ?>

<?php if (!empty($data['success'])): ?>
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
<?php endif; ?>

<h2>Koppelingen beheren</h2>
<form method="post" action="<?= htmlspecialchars(appUrl('verdieping-koppeling-save')) ?>">
    <?= CSRF::token() ?>

    <p>
        <label for="VerdiepingsvraagID">Verdiepingsvraag *</label><br>
        <select name="VerdiepingsvraagID" id="VerdiepingsvraagID" required>
            <option value="">Selecteer een verdiepingsvraag</option>
            <?php foreach (($data['verdiepingsvragen'] ?? []) as $vraag): ?>
                <?php
                $optionId = (int) ($vraag['id'] ?? 0);
                $selectedVraagId = (int) ($data['form_values']['VerdiepingsvraagID'] ?? 0);
                ?>
                <option value="<?= $optionId ?>" <?= $selectedVraagId === $optionId ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) ($vraag['name'] ?? '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p><strong>Organisaties</strong></p>
    <?php
    $selectedOrganisatieIds = array_map('intval', (array) ($data['form_values']['OrganisatieIDs'] ?? []));
    ?>
    <?php foreach (($data['organisaties'] ?? []) as $organisatie): ?>
        <?php $organisatieId = (int) ($organisatie['id'] ?? 0); ?>
        <p>
            <label>
                <input type="checkbox" name="OrganisatieIDs[]" value="<?= $organisatieId ?>" <?= in_array($organisatieId, $selectedOrganisatieIds, true) ? 'checked' : '' ?>>
                <?= htmlspecialchars((string) ($organisatie['name'] ?? '')) ?>
            </label>
        </p>
    <?php endforeach; ?>

    <p>Laat alles uitgevinkt om alle koppelingen voor de gekozen vraag te verwijderen.</p>

    <p>
        <button type="submit">Koppelingen opslaan</button>
    </p>
</form>

<h2>Bestaande koppelingen</h2>
<table border="1" cellpadding="6" cellspacing="0" id="koppelingTable">
    <thead>
        <tr>
            <th>VerdiepingsvraagID</th>
            <th>Vraag</th>
            <th>OrganisatieID</th>
            <th>Organisatie</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody id="koppelingTableBody">
        <?php if (!empty($data['items'])): ?>
            <?php foreach ($data['items'] as $row): ?>
                <tr>
                    <td><?= (int) ($row['verdiepingsvraag_id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['vraag'] ?? '')) ?></td>
                    <td><?= (int) ($row['organisatie_id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['organisatie_name'] ?? '')) ?></td>
                    <td>
                        <form method="post" action="<?= htmlspecialchars(appUrl('verdieping-koppeling-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze koppeling wilt verwijderen?');" style="display:inline;">
                            <?= CSRF::token() ?>
                            <input type="hidden" name="VerdiepingsvraagID" value="<?= (int) ($row['verdiepingsvraag_id'] ?? 0) ?>">
                            <input type="hidden" name="OrganisatieID" value="<?= (int) ($row['organisatie_id'] ?? 0) ?>">
                            <button type="submit">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nog geen koppelingen gevonden.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>