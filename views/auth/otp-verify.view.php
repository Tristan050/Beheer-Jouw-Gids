<?php
?>
<section class="grid min-h-screen place-items-center px-4 py-8">
    <div class="w-full max-w-md overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_22px_60px_rgba(15,23,42,0.12)]" aria-labelledby="verifyTitle">
        <header class="border-b border-primary/10 bg-linear-to-br from-primary/8 to-secondary/10 px-5 pb-5 pt-6 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary/60">Beheer Jouw Gids</p>
            <h1 id="verifyTitle" class="mt-3 text-2xl font-bold tracking-tight text-primary sm:text-[1.8rem]">Verificatiecode</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Voer je 6-cijferige code in</p>
        </header>

        <div class="px-5 pb-6 pt-5 sm:px-6">
            <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                <p>Verificatiecode verzonden naar <strong><?= htmlspecialchars((string) $data['email']) ?></strong></p>
                <p class="text-xs mt-1.5 opacity-90">De code is 10 minuten geldig</p>
            </div>

            <?php if ($data['debug_mode'] && $data['debug_code']): ?>
                <div class="mb-4 rounded-xl border">
                    <p class="font-semibold">Debug Mode - Verificatiecode:</p>
                    <p class="text-2xl font-bold tracking-widest text-yellow-900 mt-2"><?= htmlspecialchars($data['debug_code']) ?></p>
                    <p class="text-xs mt-2 opacity-90">Deze code wordt alleen getoond in debug modus. Email is niet verzonden.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['error'])): ?>
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= htmlspecialchars((string) $data['error']) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= htmlspecialchars(appUrl('otp-verify')) ?>" id="verifyForm" autocomplete="off" novalidate>
                <?= CSRF::token() ?>

                <input type="hidden" name="action" value="verify_code">

                <div class="mb-6">
                    <label for="codeInput" class="mb-1.5 inline-block text-sm font-semibold text-slate-700">Code</label>
                    <input
                        id="codeInput"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-center text-2xl font-bold tracking-widest text-slate-800 transition placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        maxlength="6"
                        placeholder="000000"
                        autocomplete="off"
                        required />
                </div>

                <button class="w-full rounded-xl bg-linear-to-br from-primary to-primary/80 px-4 py-3 text-[0.98rem] font-bold text-white transition hover:-translate-y-0.5 hover:saturate-110 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/15 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0 disabled:hover:saturate-100" type="submit">
                    Verifiëren
                </button>
            </form>

            <div class="mt-4 space-y-2 border-t border-slate-100 pt-4">
                <p class="text-center text-xs text-slate-600">
                    <a href="<?= htmlspecialchars(appUrl('login')) ?>" class="text-primary hover:underline font-semibold">Terug naar inloggen</a>
                </p>
                <form method="post" action="<?= htmlspecialchars(appUrl('otp-logout')) ?>" class="text-center">
                    <?= CSRF::token() ?>
                    <button type="submit" class="text-xs text-slate-500 hover:text-slate-700 underline">Afmelden</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const codeInput = document.getElementById('codeInput');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                document.getElementById('verifyForm').submit();
            }
        });
        codeInput.focus();
    }
</script>
