<section class="grid min-h-screen place-items-center px-4 py-8">
    <div class="w-full max-w-md overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_22px_60px_rgba(15,23,42,0.12)]" aria-labelledby="loginTitle">
        <header class="border-b border-primary/10 bg-linear-to-br from-primary/8 to-secondary/10 px-5 pb-5 pt-6 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary/60">Beheer Jouw Gids</p>
            <h1 id="loginTitle" class="mt-3 text-2xl font-bold tracking-tight text-primary sm:text-[1.8rem]">Inloggen</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Gebruik je beheerdersaccount om toegang te krijgen tot het dashboard.</p>
        </header>

        <div class="px-5 pb-6 pt-5 sm:px-6">
            <?php if (!empty($data['error'])): ?>
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= htmlspecialchars(appUrl('login')) ?>" id="loginForm" autocomplete="on" novalidate>
                <?= CSRF::token() ?>

                <div class="mb-4">
                    <label for="emailInput" class="mb-1.5 inline-block text-sm font-semibold text-slate-700">E-mailadres</label>
                    <input
                        id="emailInput"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-base text-slate-800 transition placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10"
                        name="email"
                        type="email"
                        placeholder="naam@domein.nl"
                        autocomplete="email"
                        spellcheck="false"
                        value="<?= htmlspecialchars((string) ($data['email'] ?? '')) ?>"
                        required />
                </div>

                <div class="mb-4">
                    <label for="passwordInput" class="mb-1.5 inline-block text-sm font-semibold text-slate-700">Wachtwoord</label>
                    <div class="relative">
                        <input
                            id="passwordInput"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 pr-20 text-base text-slate-800 transition placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10"
                            name="password"
                            type="password"
                            placeholder="Vul je wachtwoord in"
                            autocomplete="current-password"
                            required />
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg px-2 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400/40" type="button" aria-label="Toon wachtwoord">Toon</button>
                    </div>
                </div>

                <button class="w-full rounded-xl bg-linear-to-br from-primary to-primary/80 px-4 py-3 text-[0.98rem] font-bold text-white transition hover:-translate-y-0.5 hover:saturate-110 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/15 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0 disabled:hover:saturate-100" type="submit">
                    Inloggen
                </button>
            </form>
        </div>
    </div>
</section>