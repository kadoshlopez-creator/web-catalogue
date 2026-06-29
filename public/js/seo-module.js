document.addEventListener('DOMContentLoaded', () => {
    // Inputs
    const titleInput = document.getElementById('meta_title');
    const descInput = document.getElementById('meta_description');
    const slugInput = document.getElementById('slug');
    
    // Counters
    const titleCounter = document.getElementById('titleCounter');
    const descCounter = document.getElementById('descCounter');
    
    // SERP Previews
    const serpTitle = document.getElementById('serpTitle');
    const serpDesc = document.getElementById('serpDesc');
    const serpUrl = document.getElementById('serpUrl');
    
    // Score Bar
    const seoScoreBar = document.getElementById('seoScoreBar');
    const seoScoreText = document.getElementById('seoScoreText');
    
    // Base values to restore when empty
    const defaultTitle = serpTitle.innerText;
    const baseUrl = serpUrl.innerText.substring(0, serpUrl.innerText.lastIndexOf('/') + 1);
    
    function updateSERP() {
        // Update SERP Title
        const newTitle = titleInput.value.trim();
        serpTitle.innerText = newTitle.length > 0 ? newTitle : defaultTitle;
        
        // Update SERP Desc
        const newDesc = descInput.value.trim();
        serpDesc.innerText = newDesc.length > 0 ? newDesc : 'Sin descripción SEO configurada. Google tomará contenido aleatorio de la página.';
        
        // Update URL
        let newSlug = slugInput.value.trim();
        serpUrl.innerText = baseUrl + newSlug;
    }
    
    function updateCounters() {
        const titleLen = titleInput.value.trim().length;
        const descLen = descInput.value.trim().length;
        
        titleCounter.innerText = `${titleLen}/60`;
        descCounter.innerText = `${descLen}/160`;
        
        // Color coding for counters
        titleCounter.className = `text-xs ${titleLen >= 30 && titleLen <= 60 ? 'text-green-500' : (titleLen > 60 ? 'text-red-500' : 'text-gray-400')}`;
        descCounter.className = `text-xs ${descLen >= 120 && descLen <= 160 ? 'text-green-500' : (descLen > 160 ? 'text-red-500' : 'text-gray-400')}`;
    }
    
    function calculateScore() {
        let score = 0;
        
        const titleLen = titleInput.value.trim().length;
        if (titleLen >= 30 && titleLen <= 60) score += 20;
        else if (titleLen > 0) score += 10;
        
        const descLen = descInput.value.trim().length;
        if (descLen >= 120 && descLen <= 160) score += 20;
        else if (descLen > 0) score += 10;
        
        // Additional naive calculations for instant feedback
        if (slugInput.value.trim().length > 0) score += 10;
        
        const schema = document.querySelector('textarea[name="schema_json"]').value.trim();
        if (schema.length > 0) score += 15;
        
        const canonical = document.querySelector('input[name="canonical_url"]').value.trim();
        if (canonical.length > 0) score += 10;
        
        // Cap score at 100
        score = Math.min(score, 100);
        
        // Update UI
        seoScoreBar.style.width = `${score}%`;
        seoScoreText.innerText = `${score}/100`;
        
        // Color coding
        seoScoreBar.className = `h-2.5 rounded-full ${score > 70 ? 'bg-green-500' : (score > 40 ? 'bg-yellow-400' : 'bg-red-500')}`;
        seoScoreText.className = `text-sm font-bold ${score > 70 ? 'text-green-600' : (score > 40 ? 'text-yellow-600' : 'text-red-600')}`;
    }

    // Bind events
    const allInputs = document.querySelectorAll('input, textarea');
    allInputs.forEach(input => {
        input.addEventListener('input', () => {
            updateSERP();
            updateCounters();
            calculateScore();
        });
    });
    
    // Initial run
    updateCounters();
});
