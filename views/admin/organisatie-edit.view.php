<h1>Organisatie bewerken</h1>

<?php if (!empty($data['form_error'])): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
<?php endif; ?>

<p><strong><?= !empty($data['form_values']['OrganisatieID']) ? 'Bewerken' : 'Nieuw'; ?></strong></p>

<form id="organisatieForm" method="post" action="<?= htmlspecialchars(appUrl('organisatie-save')) ?>">
    <?= CSRF::token() ?>
    <input type="hidden" name="OrganisatieID" id="OrganisatieID" value="<?= htmlspecialchars((string) ($data['form_values']['OrganisatieID'] ?? '')) ?>">

    <p>
        <label for="Naam">Naam *</label><br>
        <input type="text" name="Naam" id="Naam" placeholder="Bijv. Zorgorganisatie X" value="<?= htmlspecialchars((string) ($data['form_values']['Naam'] ?? '')) ?>" required>
    </p>

    <p>
        <label for="Adres">Adres</label><br>
        <input type="text" name="Adres" id="Adres" placeholder="Straat + huisnummer" value="<?= htmlspecialchars((string) ($data['form_values']['Adres'] ?? '')) ?>">
    </p>

    <p>
        <label for="Telefoon">Telefoon</label><br>
        <input type="text" name="Telefoon" id="Telefoon" placeholder="Bijv. 0101234567" value="<?= htmlspecialchars((string) ($data['form_values']['Telefoon'] ?? '')) ?>">
    </p>

    <p>
        <label for="Email">Email</label><br>
        <input type="email" name="Email" id="Email" placeholder="contact@organisatie.nl" value="<?= htmlspecialchars((string) ($data['form_values']['Email'] ?? '')) ?>">
    </p>

    <p>
        <label for="Website">Website</label><br>
        <input type="url" name="Website" id="Website" placeholder="https://www.organisatie.nl" value="<?= htmlspecialchars((string) ($data['form_values']['Website'] ?? '')) ?>">
    </p>

    <p>
        <button type="submit">Opslaan</button>
        <a href="<?= htmlspecialchars(appUrl('organisaties')) ?>">Annuleren</a>
    </p>
</form>