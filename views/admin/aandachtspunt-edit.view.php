<div class="admin-shell">
    <aside class="admin-sidebar" aria-label="Hoofdmenu beheer">
        <div class="sidebar-brand">Jouw-Gids Beheer</div>
        <div class="sidebar-user">Editor: <strong>Aandachtspunt</strong></div>
        <nav class="space-y-2 mt-3" aria-label="Navigatie modules">
            <a href="/aandachtspunten" class="btn btn-secondary w-full">Terug naar overzicht</a>
        </nav>
    </aside>

    <div class="admin-content">
        <header class="admin-topbar" style="background: linear-gradient(90deg, #fff 0%, #f9f6ff 100%);">
            <h1 class="topbar-title">Aandachtspunt bewerken</h1>
        </header>

        <main class="page-wrap">
            <section class="panel" style="max-width: 980px; border-color:#e4dcf2;">
                <div class="panel-header">
                    <h2 class="panel-title">Formulier gids_aandachtspunt</h2>
                    <span class="badge badge-primary">Frontend only</span>
                </div>

                <form id="aandachtspuntForm" method="post" action="/aandachtspunten/save" class="space-y-4" data-table="gids_aandachtspunt">
                    <input type="hidden" name="AandachtspuntID" id="AandachtspuntID" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="FunctieID" class="block text-sm font-semibold mb-1">FunctieID *</label>
                            <input type="number" name="FunctieID" id="FunctieID" class="search-input" min="1" step="1" required>
                        </div>
                        <div>
                            <label for="Sort_order" class="block text-sm font-semibold mb-1">Sort_order</label>
                            <input type="number" name="Sort_order" id="Sort_order" class="search-input" min="0" step="1">
                        </div>
                    </div>

                    <div>
                        <label for="Aandachtspunt" class="block text-sm font-semibold mb-1">Aandachtspunt *</label>
                        <input type="text" name="Aandachtspunt" id="Aandachtspunt" class="search-input" placeholder="Bijv. Onvoldoende zicht op budget" required>
                    </div>

                    <div>
                        <label for="Toelichting" class="block text-sm font-semibold mb-1">Toelichting</label>
                        <textarea name="Toelichting" id="Toelichting" rows="3" class="search-input" placeholder="Extra uitleg voor de beheerder..."></textarea>
                    </div>

                    <div>
                        <label for="Scan_tekst" class="block text-sm font-semibold mb-1">Scan_tekst</label>
                        <textarea name="Scan_tekst" id="Scan_tekst" rows="4" class="search-input" placeholder="Tekst die tijdens de scan getoond wordt..."></textarea>
                    </div>

                    <div>
                        <label for="Advies_tekst" class="block text-sm font-semibold mb-1">Advies_tekst</label>
                        <textarea name="Advies_tekst" id="Advies_tekst" rows="4" class="search-input" placeholder="Tekst met advies en vervolgstappen..."></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="btn" style="background:#5c3b87;color:#fff;">Opslaan</button>
                        <a href="/aandachtspunten" class="btn btn-secondary">Annuleren</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</div>