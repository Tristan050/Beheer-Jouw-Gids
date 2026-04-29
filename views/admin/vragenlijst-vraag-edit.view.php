<?php
$sidebar = [
    'meta_label' => 'Editor',
    'meta_value' => 'Vragenlijst vraag',
    'back_url' => appUrl('vragenlijsten') . '?role_id=' . (int) ($data['form_values']['Roleid'] ?? 0),
    'back_label' => 'Terug naar overzicht',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Vragenlijst vraag bewerken</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['form_error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['form_error']) ?></div>
                <?php endif; ?>

                <section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold">Formulier vragenlijst</h2>
                        <span class="inline-flex items-center rounded-full bg-[#A53714]/10 px-3 py-1 text-xs font-semibold text-[#A53714]"><?= !empty($data['form_values']['QuestionID']) ? 'Bewerken' : 'Nieuw'; ?></span>
                    </div>

                    <form method="post" action="<?= htmlspecialchars(appUrl('vragenlijst-vraag-save')) ?>" class="mt-6 space-y-4">
                        <?= CSRF::token() ?>
                        <input type="hidden" name="QuestionID" value="<?= htmlspecialchars((string) ($data['form_values']['QuestionID'] ?? '')) ?>">

                        <div>
                            <label for="Roleid" class="block text-sm font-semibold text-slate-700">Rol *</label>
                            <select name="Roleid" id="Roleid" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" required>
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
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="Question_key" class="block text-sm font-semibold text-slate-700">Question_key *</label>
                                <input type="text" name="Question_key" id="Question_key" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" value="<?= htmlspecialchars((string) ($data['form_values']['Question_key'] ?? '')) ?>" required>
                            </div>
                            <div>
                                <label for="Label" class="block text-sm font-semibold text-slate-700">Label *</label>
                                <input type="text" name="Label" id="Label" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" value="<?= htmlspecialchars((string) ($data['form_values']['Label'] ?? '')) ?>" required>
                            </div>
                        </div>

                        <div>
                            <label for="Question_type_id" class="block text-sm font-semibold text-slate-700">Vraagtype *</label>
                            <select name="Question_type_id" id="Question_type_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" required>
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
                        </div>

                        <div>
                            <label for="Default_value" class="block text-sm font-semibold text-slate-700">Default_value (JSON of leeg)</label>
                            <textarea name="Default_value" id="Default_value" rows="4" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20"><?= htmlspecialchars((string) ($data['form_values']['Default_value'] ?? '')) ?></textarea>
                        </div>

                        <div>
                            <label for="Sort_order" class="block text-sm font-semibold text-slate-700">Sort_order *</label>
                            <input type="number" name="Sort_order" id="Sort_order" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" value="<?= htmlspecialchars((string) ($data['form_values']['Sort_order'] ?? '0')) ?>" required>
                        </div>

                        <div>
                            <label for="Option_lines" class="block text-sm font-semibold text-slate-700">Opties (alleen voor radio/checkbox)</label>
                            <textarea name="Option_lines" id="Option_lines" rows="8" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" placeholder="voorbeeld_waarde|Voorbeeld label|1"><?= htmlspecialchars((string) ($data['form_values']['Option_lines'] ?? '')) ?></textarea>
                            <p class="mt-2 text-xs text-slate-500">Formaat per regel: option_value|label|sort_order</p>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">Opslaan</button>
                            <a href="<?= htmlspecialchars(appUrl('vragenlijsten') . '?role_id=' . (int) ($data['form_values']['Roleid'] ?? 0)) ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Annuleren</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</div>