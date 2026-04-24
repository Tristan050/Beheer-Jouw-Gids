<h1>Vragenlijsten per rol</h1>

<?php if (!empty($data['error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
<?php endif; ?>

<?php if (!empty($data['success'])): ?>
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
<?php endif; ?>

<form method="get" action="<?= htmlspecialchars(appUrl('vragenlijsten')) ?>">
    <p>
        <label for="role_id">Rol</label><br>
        <select name="role_id" id="role_id" required>
            <option value="">Selecteer een rol</option>
            <?php foreach (($data['roles'] ?? []) as $role): ?>
                <?php
                $roleId = (int) ($role['id'] ?? 0);
                $selectedRoleId = (int) ($data['selected_role_id'] ?? 0);
                ?>
                <option value="<?= $roleId ?>" <?= $selectedRoleId === $roleId ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string) ($role['name'] ?? '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Toon vragen</button>
    </p>
</form>

<p>
    <?php $selectedRoleId = (int) ($data['selected_role_id'] ?? 0); ?>
    <a href="<?= htmlspecialchars(appUrl('vragenlijst-vraag-edit') . ($selectedRoleId > 0 ? '?role_id=' . $selectedRoleId : '')) ?>">
        <button type="button">Nieuwe vraag</button>
    </a>
</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question_key</th>
            <th>Label</th>
            <th>Type</th>
            <th>Default_value</th>
            <th>Sort_order</th>
            <th>Opties</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data['items'])): ?>
            <?php foreach ($data['items'] as $row): ?>
                <tr>
                    <td><?= (int) ($row['id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['question_key'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['label'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['question_type'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['default_value'] ?? '')) ?></td>
                    <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['options_preview'] ?? '')) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('vragenlijst-vraag-edit'))) ?>">
                            <button type="button">Bewerken</button>
                        </a>
                        <form method="post" action="<?= htmlspecialchars(appUrl('vragenlijst-vraag-delete')) ?>" style="display:inline;" onsubmit="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?');">
                            <?= CSRF::token() ?>
                            <input type="hidden" name="QuestionID" value="<?= (int) ($row['id'] ?? 0) ?>">
                            <input type="hidden" name="Roleid" value="<?= (int) ($row['role_id'] ?? 0) ?>">
                            <button type="submit">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Nog geen vragen gevonden voor deze rol.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>