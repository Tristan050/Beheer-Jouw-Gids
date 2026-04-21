<section class="mx-auto max-w-3xl px-6 py-16">
    <div class="rounded-xl border border-red-200 bg-red-50 p-6">
        <h1 class="mb-2 text-3xl font-bold text-red-900">500</h1>
        <p class="text-red-700 mb-4">Er is een interne fout opgetreden.</p>
        <p class="text-sm text-red-600">Het probleem is gelogd. Probeer het over enkele minuten opnieuw.</p>
        <?php if (jg_db_debug_enabled()): ?>
            <details class="mt-4 text-xs text-red-600">
                <summary class="cursor-pointer font-semibold">Debug info (development only)</summary>
                <pre class="mt-2 overflow-auto bg-red-100 p-2 rounded text-red-800">Check logs/app.log voor meer details</pre>
            </details>
        <?php endif; ?>
    </div>
</section>
