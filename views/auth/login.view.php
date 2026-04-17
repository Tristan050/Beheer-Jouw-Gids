<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="color-scheme" content="light" />
    <title>Beheer Login</title>
    <link href="/src/output.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-[radial-gradient(80rem_40rem_at_10%_-10%,rgba(157,51,24,0.14),transparent_60%),radial-gradient(70rem_35rem_at_95%_105%,rgba(137,164,108,0.22),transparent_60%),linear-gradient(140deg,#f2ece6,#f6f8f3)] font-['Segoe_UI','Trebuchet_MS','Tahoma',sans-serif] text-slate-800">
    <main class="grid min-h-screen place-items-center px-4 py-8">
        <section class="w-full max-w-md overflow-hidden rounded-[18px] border border-white/50 bg-white shadow-[0_18px_40px_rgba(36,28,21,0.16)]" aria-labelledby="loginTitle">
            <header class="border-b border-slate-300 bg-gradient-to-br from-[rgba(157,51,24,0.09)] to-[rgba(137,164,108,0.13)] px-4 pb-4 pt-6 sm:px-6">
                <h1 id="loginTitle" class="m-0 text-[1.38rem] font-bold tracking-[0.01em] text-[#7f2913] sm:text-[1.65rem]">Beheerportaal Login</h1>
                <p class="mt-1.5 text-[0.95rem] text-slate-500">Log veilig in om het admin-gedeelte te openen.</p>
            </header>

            <div class="px-4 pb-6 pt-5 sm:px-6">
                <form method="post" id="loginForm" autocomplete="on" novalidate>
                    <div class="mb-4">
                        <label for="emailInput" class="mb-1.5 inline-block text-sm font-semibold text-slate-700">E-mailadres</label>
                        <input
                            id="emailInput"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-base text-slate-800 transition focus:border-lime-700 focus:outline-none focus:ring-4 focus:ring-lime-700/20"
                            name="email"
                            type="email"
                            placeholder="naam@domein.nl"
                            autocomplete="email"
                            spellcheck="false"
                            required />
                    </div>

                    <div class="mb-4">
                        <label for="passwordInput" class="mb-1.5 inline-block text-sm font-semibold text-slate-700">Wachtwoord</label>
                        <div class="relative">
                            <input
                                id="passwordInput"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 pr-20 text-base text-slate-800 transition focus:border-lime-700 focus:outline-none focus:ring-4 focus:ring-lime-700/20"
                                name="password"
                                type="password"
                                placeholder="Vul je wachtwoord in"
                                autocomplete="current-password"
                                required />
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg px-2 py-1 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lime-700/30" type="button" aria-label="Toon wachtwoord">Toon</button>
                        </div>
                        <div class="invisible mt-1.5 min-h-4 text-xs text-amber-700" aria-live="polite">Caps Lock lijkt aan te staan.</div>
                    </div>

                    <button class="w-full rounded-xl bg-gradient-to-br from-[#9d3318] to-[#b84823] px-4 py-3 text-[0.98rem] font-bold text-white transition hover:-translate-y-0.5 hover:saturate-105 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-lime-700/30 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0 disabled:hover:saturate-100" type="submit">
                        Inloggen
                    </button>

                    <p class="mt-4 text-xs leading-relaxed text-slate-500">
                        Lokale testmodus zonder controller: gebruik admin@example.com met wachtwoord Admin123!.
                    </p>
                </form>
            </div>
        </section>
    </main>
</body>

</html>