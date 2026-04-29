<?php
$sidebar = [
    'meta_label' => 'Module',
    'meta_value' => 'Vragenlijsten',
    'active' => 'vragenlijsten',
];
?>

<div class="min-h-screen bg-slate-50 text-slate-900">
    <div class="flex min-h-screen">
        <?php require __DIR__ . '/components/sidebar.view.php'; ?>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white/80 px-6 py-5 backdrop-blur">
                <h1 class="text-2xl font-semibold tracking-tight">Vragenlijsten per rol</h1>
            </header>

            <main class="flex-1 space-y-6 px-6 py-6">
                <?php if (!empty($data['error'])): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
                <?php endif; ?>

                <?php if (!empty($data['success'])): ?>
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= htmlspecialchars((string) $data['success']) ?></div>
                <?php endif; ?>

                <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
                    <a href="/admin" class="font-medium text-slate-600 hover:text-slate-900">Dashboard</a>
                    <span class="text-slate-400">/</span>
                    <span class="font-medium text-slate-700">Vragenlijsten</span>
                </nav>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">Selecteer een rol</h2>
                            <p class="mt-1 text-sm text-slate-600">Kies een rol om vragen en opties te beheren.</p>
                        </div>
                        <?php $selectedRoleId = (int) ($data['selected_role_id'] ?? 0); ?>
                        <a href="<?= htmlspecialchars(appUrl('vragenlijst-vraag-edit') . ($selectedRoleId > 0 ? '?role_id=' . $selectedRoleId : '')) ?>" class="inline-flex items-center gap-2 rounded-lg bg-[#A53714] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8f2f11] focus:outline-none focus:ring-2 focus:ring-[#A53714]/30">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Nieuwe vraag
                        </a>
                    </div>

                    <form method="get" action="<?= htmlspecialchars(appUrl('vragenlijsten')) ?>" class="mt-4 flex flex-wrap items-end gap-3">
                        <div class="min-w-[220px] flex-1">
                            <label for="role_id" class="block text-sm font-semibold text-slate-700">Rol</label>
                            <select name="role_id" id="role_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#A53714] focus:outline-none focus:ring-2 focus:ring-[#A53714]/20" required>
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
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Toon vragen</button>
                    </form>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Vragenlijstvragen</h2>
                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Question_key</th>
                                    <th class="px-4 py-3">Label</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Default_value</th>
                                    <th class="px-4 py-3">Sort_order</th>
                                    <th class="px-4 py-3">Opties</th>
                                    <th class="px-4 py-3">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                <?php if (!empty($data['items'])): ?>
                                    <?php foreach ($data['items'] as $row): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= (int) ($row['id'] ?? 0) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['question_key'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['label'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['question_type'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['default_value'] ?? '')) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= (int) ($row['sort_order'] ?? 0) ?></td>
                                            <td class="px-4 py-3 text-sm text-slate-700"><?= htmlspecialchars((string) ($row['options_preview'] ?? '')) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="<?= htmlspecialchars((string) ($row['edit_url'] ?? appUrl('vragenlijst-vraag-edit'))) ?>" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Bewerken</a>
                                                    <form method="post" action="<?= htmlspecialchars(appUrl('vragenlijst-vraag-delete')) ?>" onsubmit="return confirm('Weet je zeker dat je deze vraag wilt verwijderen?');">
                                                        <?= CSRF::token() ?>
                                                        <input type="hidden" name="QuestionID" value="<?= (int) ($row['id'] ?? 0) ?>">
                                                        <input type="hidden" name="Roleid" value="<?= (int) ($row['role_id'] ?? 0) ?>">
                                                        <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 shadow-sm transition hover:bg-red-50">Verwijderen</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">Nog geen vragen gevonden voor deze rol.</td>
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