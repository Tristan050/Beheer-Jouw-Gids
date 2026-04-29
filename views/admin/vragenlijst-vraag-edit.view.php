<h1>Vragenlijst vraag bewerken</h1>

<?php if (!empty($data['form_error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
<?php endif; ?>

<p><strong><?= !empty($data['form_values']['QuestionID']) ? 'Bewerken' : 'Nieuw'; ?></strong></p>

<form method="post" action="<?= htmlspecialchars(appUrl('vragenlijst-vraag-save')) ?>">
    <?= CSRF::token() ?>
    <input type="hidden" name="QuestionID" value="<?= htmlspecialchars((string) ($data['form_values']['QuestionID'] ?? '')) ?>">

    <p>
        <label for="Roleid">Rol *</label><br>
        <select name="Roleid" id="Roleid" required>
            <option value="">Selecteer een rol</option>
            <?php foreach (($data['roles'] ?? []) as $role): ?>
                <?php
                $optionId = (int) ($role['id'] ?? 0);
                $selectedRoleId = (int) ($data['form_values']['Roleid'] ?? 0);
                ?>
                <option value="<?= $optionId ?>" <?= $selectedRoleId === $optionId ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) ($role['name'] ?? '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="Question_key">Question_key *</label><br>
        <input type="text" name="Question_key" id="Question_key" value="<?= htmlspecialchars((string) ($data['form_values']['Question_key'] ?? '')) ?>" required>
    </p>

    <p>
        <label for="Label">Label *</label><br>
        <input type="text" name="Label" id="Label" value="<?= htmlspecialchars((string) ($data['form_values']['Label'] ?? '')) ?>" required>
    </p>

    <p>
        <label for="Question_type_id">Vraagtype *</label><br>
        <select name="Question_type_id" id="Question_type_id" required>
            <option value="">Selecteer vraagtype</option>
            <?php foreach (($data['question_types'] ?? []) as $type): ?>
                <?php
                $typeId = (int) ($type['id'] ?? 0);
                $selectedTypeId = (int) ($data['form_values']['Question_type_id'] ?? 0);
                $typeName = (string) ($type['name'] ?? '');
                $typeSuffix = !empty($type['has_options']) ? ' (met opties)' : '';
                ?>
                <option value="<?= $typeId ?>" <?= $selectedTypeId === $typeId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($typeName . $typeSuffix) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="Default_value">Default_value (JSON of leeg)</label><br>
        <textarea name="Default_value" id="Default_value" rows="4"><?= htmlspecialchars((string) ($data['form_values']['Default_value'] ?? '')) ?></textarea>
    </p>

    <p>
        <label for="Sort_order">Sort_order *</label><br>
        <input type="number" name="Sort_order" id="Sort_order" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>" required>
    </p>

    <p>
        <label for="Option_lines">Opties (alleen voor radio/checkbox)</label><br>
        <textarea name="Option_lines" id="Option_lines" rows="8" placeholder="voorbeeld_waarde|Voorbeeld label|1"><?= htmlspecialchars((string) ($data['form_values']['Option_lines'] ?? '')) ?></textarea><br>
        Formaat per regel: option_value|label|sort_order
    </p>

    <p>
        <button type="submit">Opslaan</button>
        <a href="<?= htmlspecialchars(appUrl('vragenlijsten') . '?role_id=' . (int) ($data['form_values']['Roleid'] ?? 0)) ?>">Annuleren</a>
    </p>
</form>