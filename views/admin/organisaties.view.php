<h1>Organisaties beheren</h1>

<?php if (!empty($data['error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
<?php endif; ?>

<?php if (!empty($data['success'])): ?>
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
<?php endif; ?>

<p>
    <a href="<?= htmlspecialchars(appUrl('organisatie-edit')) ?>">
        <button type="button">Nieuwe organisatie</button>
    </a>
</p>

<table>
    <thead>
        <tr>
            <th>OrganisatieID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Telefoon</th>
            <th>Email</th>
            <th>Website</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data['items'])): ?>
            <?php foreach ($data['items'] as $row): ?>
                <tr>
                    <td><?= (int) ($row['id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string) ($row['name'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['address'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['phone'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['email'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string) ($row['website'] ?? '')) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('organisatie-edit'))) ?>">
                            <button type="button">Bewerken</button>
                        </a>
                        <form method="post" action="<?= htmlspecialchars(appUrl('organisatie-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze organisatie wilt verwijderen?');" style="display:inline;">
                            <?= CSRF::token() ?>
                            <input type="hidden" name="OrganisatieID" value="<?= (int) ($row['id'] ?? 0) ?>">
                            <button type="submit">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Nog geen organisaties gevonden.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>