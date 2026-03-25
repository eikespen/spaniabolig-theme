<?php
/*
 * Template Name: Dictionary
 */
get_header();

$terms = [
    ['name' => 'Abogado',                'spanish' => 'Abogado',                          'cat' => 'Legal Requirements', 'related' => ['Gestor', 'NIE Number'],              'desc' => 'A Spanish lawyer who provides legal advice and representation. For property purchases, an abogado conducts legal checks on the property, reviews contracts, advises on tax implications, and protects your interests throughout the purchase process. While not legally required, hiring an independent abogado (separate from the seller\'s legal team) is strongly recommended for foreign buyers.'],
    ['name' => 'AJD',                    'spanish' => 'Actos Jurídicos Documentados',      'cat' => 'Taxes',              'related' => ['ITP', 'IVA'],                         'desc' => 'The stamp duty tax applied to legal documents in Spanish property transactions. This tax applies to new builds (alongside IVA). Rates vary by region, typically ranging from 0.5% to 1.5% of the property\'s purchase price. In some regions, reduced rates apply for certain buyer groups, such as first-time buyers or young people.'],
    ['name' => 'Ático',                  'spanish' => 'Ático',                             'cat' => 'Property Types',     'related' => ['Piso'],                               'desc' => 'A penthouse or top-floor apartment in Spain, typically featuring a large outdoor terrace. These highly desirable properties often offer panoramic views and excellent natural light. A regular ático has a terrace on one side, while a dúplex ático is spread across two floors, and an ático con solárium includes roof access or a rooftop terrace. Due to their desirable features, áticos typically command premium prices in the Spanish property market.'],
    ['name' => 'Bungalow',               'spanish' => 'Bungalow',                          'cat' => 'Property Types',     'related' => ['Chalet'],                             'desc' => 'In Spanish real estate, a bungalow typically refers to a small, single-story house or villa that may be detached or in a row of similar properties. Bungalows are very common in coastal areas and urbanizations popular with foreign buyers. They often feature small private gardens or terraces and shared community facilities. A bungalow alto refers to an upper-floor unit with a roof terrace, while a bungalow bajo is a ground-floor unit usually with a small garden.'],
    ['name' => 'Cadastral Value',        'spanish' => 'Valor Catastral',                   'cat' => 'Basic Terminology',  'related' => ['IBI', 'Plusvalía'],                   'desc' => 'The official valuation of a property assigned by the Spanish Cadastre (Catastro), a public registry that maintains information about all real estate in Spain. This value is used as the basis for calculating several taxes, including IBI (annual property tax) and Plusvalía. The cadastral value is typically lower than the market value, often representing 50–70% of the actual market price.'],
    ['name' => 'Chalet',                 'spanish' => 'Chalet',                            'cat' => 'Property Types',     'related' => ['Bungalow', 'Finca'],                  'desc' => 'A detached house or villa in Spain. A chalet adosado is a semi-detached house or townhouse sharing walls with neighboring properties, while a chalet pareado is a duplex sharing one wall with another house. A chalet independiente is fully detached. These properties typically have private gardens or pools and are popular in residential urbanizations and coastal areas.'],
    ['name' => 'Community Fees',         'spanish' => 'Cuotas de Comunidad',               'cat' => 'Costs & Fees',       'related' => ['Community of Owners', 'Urbanización'], 'desc' => 'Regular payments made by property owners to maintain common areas and services in an urbanization or apartment building. These fees typically cover maintenance of swimming pools, gardens, elevators, stairwells, and other shared facilities, as well as insurance for the building structure and sometimes utilities for common areas. Fees vary widely depending on the amenities and services provided.'],
    ['name' => 'Community of Owners',    'spanish' => 'Comunidad de Propietarios',         'cat' => 'Urbanizations',      'related' => ['Community Fees', 'Urbanización'],      'desc' => 'The legal entity formed by all property owners in a Spanish urbanization or apartment building. The community makes decisions about maintenance, repairs, and improvements to common areas through regular meetings and voting. Membership is mandatory for all property owners, and each member must pay community fees to cover shared expenses. The community is typically managed by a president elected from among the owners.'],
    ['name' => 'Escritura',              'spanish' => 'Escritura Pública',                 'cat' => 'Legal Requirements', 'related' => ['Nota Simple', 'Notary'],              'desc' => 'The Spanish property deed that transfers legal ownership. This official document is signed before a notary and contains all property details, including boundaries, conditions, and any existing charges. All parties (seller, buyer, and bank representative if a mortgage is involved) must be present at the signing. The escritura is later registered in the Property Registry (Registro de la Propiedad).'],
    ['name' => 'Finca',                  'spanish' => 'Finca',                             'cat' => 'Property Types',     'related' => ['Chalet'],                             'desc' => 'A country property or rural estate in Spain. Fincas can range from small country houses with a bit of land to large agricultural estates. A finca rústica is classified as rural land, while a finca urbana is within an urban or developed area. Buying a finca rústica often comes with restrictions on renovation and building, and foreign buyers should be particularly careful to check the legal status and permitted uses of such properties.'],
    ['name' => 'Gestor',                 'spanish' => 'Gestor Administrativo',             'cat' => 'Buying Process',     'related' => ['Abogado', 'Notary'],                  'desc' => 'A professional who handles administrative paperwork and procedures in Spain. For property transactions, a gestor coordinates with the notary and property registry, ensuring all documentation is correctly filed and taxes are paid. While not legally required, many foreign buyers hire a gestor to navigate the Spanish bureaucratic process. Fees vary but typically range from €300–600 for property purchase services.'],
    ['name' => 'Golden Visa',            'spanish' => 'Visa de Oro',                       'cat' => 'Legal Requirements', 'related' => ['NIE Number'],                         'desc' => 'A residency permit available to non-EU citizens who make a significant investment in Spain, such as purchasing property worth €500,000 or more. The Golden Visa grants the right to live and work in Spain and travel throughout the Schengen Area. Family members can also be included in the application. The visa is initially granted for two years and can be renewed for five-year periods, eventually leading to permanent residency.'],
    ['name' => 'IBI',                    'spanish' => 'Impuesto sobre Bienes Inmuebles',   'cat' => 'Taxes',              'related' => ['Cadastral Value', 'Plusvalía'],       'desc' => 'The annual property tax in Spain, similar to council tax in the UK. This tax is calculated based on the cadastral value of the property and varies depending on the municipality. IBI rates typically range from 0.4% to 1.1% of the cadastral value per year. As the property owner, you are responsible for paying this tax annually, and it is usually collected in a single payment between September and November.'],
    ['name' => 'ITP',                    'spanish' => 'Impuesto de Transmisiones Patrimoniales', 'cat' => 'Taxes',         'related' => ['IVA', 'AJD'],                         'desc' => 'The transfer tax paid when purchasing resale (second-hand) properties in Spain. The rate varies by region, typically ranging from 6% to 10% of the property\'s purchase price. In the Valencia region (which includes Ciudad Quesada), the standard ITP rate is 10%. This is one of the most significant additional costs when buying a resale property and must be budgeted for carefully.'],
    ['name' => 'IVA',                    'spanish' => 'Impuesto sobre el Valor Añadido',   'cat' => 'Taxes',              'related' => ['ITP', 'AJD'],                         'desc' => 'The Spanish Value Added Tax (VAT) that applies to new property purchases. For residential properties, IVA is currently 10% of the purchase price, while commercial properties are taxed at the standard 21% rate. IVA applies to new builds (obra nueva) instead of ITP (transfer tax). When buying a new property directly from the developer, you will pay IVA plus AJD (stamp duty) on top of the purchase price.'],
    ['name' => 'NIE Number',             'spanish' => 'Número de Identidad de Extranjero', 'cat' => 'Legal Requirements', 'related' => ['Golden Visa'],                        'desc' => 'An essential identification number required for all foreign buyers purchasing property in Spain. This tax identification number for foreigners is mandatory for opening a bank account, buying property, and signing any official documents. The application process can take several weeks, so it\'s recommended to obtain this early in your property search.'],
    ['name' => 'Nota Simple',            'spanish' => 'Nota Simple',                       'cat' => 'Legal Requirements', 'related' => ['Escritura', 'Property Registration'], 'desc' => 'An extract from the Property Registry that provides essential information about a property\'s legal status. This document confirms ownership, describes the property, and lists any mortgages, liens, or charges registered against it. Foreign buyers should always obtain a recent Nota Simple before proceeding with a purchase to verify there are no legal impediments or debts attached to the property.'],
    ['name' => 'Notary',                 'spanish' => 'Notario',                           'cat' => 'Buying Process',     'related' => ['Escritura', 'Property Registration'], 'desc' => 'A public official in Spain who authenticates and certifies legal documents. For property purchases, the notary plays a crucial role in verifying the identities of all parties, checking for any legal encumbrances on the property, and officiating the signing of the escritura. The notary\'s fee (arancel notarial) is regulated by the Spanish government and is typically 0.2–0.5% of the property value.'],
    ['name' => 'Obra Nueva',             'spanish' => 'Obra Nueva',                        'cat' => 'Property Types',     'related' => ['IVA', 'Vivienda'],                    'desc' => 'New construction or newly built property in Spain. When buying obra nueva, you typically pay a schedule of staged payments during construction, with IVA (VAT) at 10% instead of the transfer tax (ITP) that applies to resale properties. New builds often come with a 10-year structural warranty (seguro decenal) and modern energy efficiency standards. Buying off-plan (before construction is complete) can offer price advantages but requires careful due diligence.'],
    ['name' => 'Piso',                   'spanish' => 'Piso',                              'cat' => 'Property Types',     'related' => ['Ático'],                              'desc' => 'The Spanish word for an apartment or flat. Pisos can range from small studios (estudios) to large luxury apartments with multiple bedrooms. In Spanish property listings, you\'ll often see descriptions like piso exterior (apartment with outside-facing windows) or piso interior (apartment with windows facing an interior courtyard). Upper floors are generally more desirable and expensive, especially if they include a terrace.'],
    ['name' => 'Plusvalía',              'spanish' => 'Plusvalía Municipal',               'cat' => 'Taxes',              'related' => ['IBI', 'Cadastral Value'],             'desc' => 'A municipal tax on the increase in value of urban land from the time of purchase to the time of sale. Plusvalía is typically paid by the seller, though this can sometimes be negotiated. The tax is calculated based on the cadastral value of the land (not the buildings) and the number of years the seller has owned the property. Recent legislation requires the tax to reflect actual gains, so if the property has not increased in value, no plusvalía is due.'],
    ['name' => 'Private Purchase Contract', 'spanish' => 'Contrato de Arras',              'cat' => 'Buying Process',     'related' => ['Reservation Agreement'],              'desc' => 'The formal purchase agreement signed after due diligence is complete, but before the final deed transfer. This contract outlines all the conditions of sale and is accompanied by a larger deposit, typically 10% of the purchase price. If the buyer backs out, they forfeit this deposit; if the seller backs out, they must typically pay the buyer double the deposit amount as compensation. This contract is binding and includes the completion date for the final deed transfer.'],
    ['name' => 'Property Registration',  'spanish' => 'Registro de la Propiedad',          'cat' => 'Buying Process',     'related' => ['Escritura', 'Nota Simple'],           'desc' => 'The process of officially recording property ownership in the Spanish Property Registry. After signing the property deed (escritura) before a notary, the document must be registered to legally protect your ownership. The registry fee is based on the property value, typically ranging from 0.1% to 0.5%. Registration usually takes 2–4 weeks, during which time your lawyer or gestor should verify completion.'],
    ['name' => 'Reservation Agreement',  'spanish' => 'Contrato de Reserva',               'cat' => 'Buying Process',     'related' => ['Private Purchase Contract'],          'desc' => 'An initial agreement signed when making an offer on a property in Spain. This document reserves the property for the buyer for a specific period (typically 14–30 days) while due diligence is conducted and the formal purchase contract is prepared. The reservation is accompanied by a deposit, usually €3,000–€6,000, which is forfeited if the buyer withdraws without legal justification but returned if legal issues are discovered with the property.'],
    ['name' => 'Urbanización',           'spanish' => 'Urbanización',                      'cat' => 'Urbanizations',      'related' => ['Community of Owners', 'Community Fees'], 'desc' => 'A residential development or community in Spain, often with shared amenities like swimming pools, gardens, or sports facilities. Urbanizations can vary greatly in size, from small groups of houses to large developments with hundreds of properties. Many popular areas for foreign buyers, like Ciudad Quesada, are made up of multiple urbanizations, each with its own character and features.'],
    ['name' => 'Vivienda',               'spanish' => 'Vivienda',                          'cat' => 'Property Types',     'related' => ['Piso', 'Chalet'],                     'desc' => 'The Spanish word for housing or dwelling. In property listings, you\'ll often see properties classified as vivienda unifamiliar (single-family home), vivienda adosada (townhouse or attached house), or vivienda plurifamiliar (multi-family building). The term vivienda is used in official documentation and refers to any residential property.'],
];

$categories = ['All Terms', 'Basic Terminology', 'Buying Process', 'Costs & Fees', 'Legal Requirements', 'Property Types', 'Taxes', 'Urbanizations'];
$letters = range('A', 'Z');
?>

<div class="dict-page">

    <div class="dict-hero">
        <div class="section-inner">
            <h1>Spanish property buying dictionary</h1>
            <p>Navigating Spanish real estate terminology can be challenging, especially for foreign buyers. Our comprehensive dictionary explains key terms you'll encounter during the Spanish property buying process.</p>
        </div>
    </div>

    <div class="dict-controls section-inner">
        <div class="dict-search">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="dict-search-input" placeholder="Search for a term…" autocomplete="off">
        </div>

        <div class="dict-az" id="dict-az">
            <button class="dict-az-btn active" data-letter="all">All</button>
            <?php foreach ($letters as $l) : ?>
                <button class="dict-az-btn" data-letter="<?php echo $l; ?>"><?php echo $l; ?></button>
            <?php endforeach; ?>
        </div>

        <div class="dict-cats" id="dict-cats">
            <?php foreach ($categories as $cat) : ?>
                <button class="dict-cat-btn<?php echo $cat === 'All Terms' ? ' active' : ''; ?>" data-cat="<?php echo esc_attr($cat); ?>">
                    <?php echo esc_html($cat); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="dict-results section-inner">
        <div id="dict-grid">
            <?php foreach ($terms as $term) : ?>
            <div class="dict-card"
                 data-name="<?php echo esc_attr(strtolower($term['name'])); ?>"
                 data-letter="<?php echo esc_attr(strtoupper(substr($term['name'], 0, 1))); ?>"
                 data-cat="<?php echo esc_attr($term['cat']); ?>"
                 data-search="<?php echo esc_attr(strtolower($term['name'] . ' ' . $term['spanish'] . ' ' . $term['desc'])); ?>">
                <h3><?php echo esc_html($term['name']); ?></h3>
                <?php if ($term['spanish'] && $term['spanish'] !== $term['name']) : ?>
                    <p class="dict-spanish"><em><?php echo esc_html($term['spanish']); ?></em></p>
                <?php endif; ?>
                <p class="dict-desc"><?php echo esc_html($term['desc']); ?></p>
                <?php if (!empty($term['related'])) : ?>
                    <div class="dict-related">
                        <span><?php esc_html_e('Related terms:', 'spaniabolig'); ?></span>
                        <?php foreach ($term['related'] as $rel) : ?>
                            <button class="dict-rel-tag" data-jump="<?php echo esc_attr(strtolower($rel)); ?>"><?php echo esc_html($rel); ?></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="dict-no-results" style="display:none;">
            <p>No terms found. Try a different search or filter.</p>
        </div>
    </div>

</div>

<script>
(function() {
    const searchInput = document.getElementById('dict-search-input');
    const azBtns      = document.querySelectorAll('.dict-az-btn');
    const catBtns     = document.querySelectorAll('.dict-cat-btn');
    const cards       = document.querySelectorAll('.dict-card');
    const noResults   = document.getElementById('dict-no-results');

    let activeLetter = 'all';
    let activeCat    = 'All Terms';
    let searchQuery  = '';

    function filter() {
        let visible = 0;
        cards.forEach(card => {
            const letter  = card.dataset.letter;
            const cat     = card.dataset.cat;
            const search  = card.dataset.search;

            const letterOk = activeLetter === 'all' || letter === activeLetter;
            const catOk    = activeCat === 'All Terms' || cat === activeCat;
            const searchOk = !searchQuery || search.includes(searchQuery.toLowerCase());

            if (letterOk && catOk && searchOk) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });
        noResults.style.display = visible === 0 ? '' : 'none';
    }

    // A–Z buttons
    azBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            azBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeLetter = btn.dataset.letter;
            filter();
        });
    });

    // Category buttons
    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCat = btn.dataset.cat;
            filter();
        });
    });

    // Search
    let debounce;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            searchQuery = searchInput.value.trim();
            filter();
        }, 250);
    });

    // Related term jump
    document.querySelectorAll('.dict-rel-tag').forEach(tag => {
        tag.addEventListener('click', () => {
            const name = tag.dataset.jump;
            // Reset filters
            activeLetter = 'all';
            activeCat = 'All Terms';
            searchQuery = name;
            searchInput.value = name;
            azBtns.forEach(b => b.classList.toggle('active', b.dataset.letter === 'all'));
            catBtns.forEach(b => b.classList.toggle('active', b.dataset.cat === 'All Terms'));
            filter();
            // Scroll to matching card
            const target = [...cards].find(c => c.dataset.name === name);
            if (target) setTimeout(() => target.scrollIntoView({ behavior: 'smooth', block: 'center' }), 50);
        });
    });
})();
</script>

<?php get_footer(); ?>
