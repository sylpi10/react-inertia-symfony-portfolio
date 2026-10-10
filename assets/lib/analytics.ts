// file d'attente de Matomo, créée par base.html.twig quand le suivi est actif
// (production) ; absente en dev : rien n'est envoyé
declare global {
    interface Window {
        _paq?: unknown[][];
    }
}

// première page : comptée par le script de base.html.twig
let trackedUrl = window.location.pathname + window.location.search;

// navigation Inertia : la page n'est pas rechargée, Matomo ne la verrait pas.
// url : chemin et paramètres de la nouvelle page ; une ancre (#contact) ou un
// retour sur la même page ne compte pas comme une nouvelle vue
export function trackPageView(url: string, title: string) {
    const target = new URL(url, window.location.origin);
    const pageUrl = target.pathname + target.search;
    if (!window._paq || pageUrl === trackedUrl) return;

    window._paq.push(["setReferrerUrl", window.location.origin + trackedUrl]);
    window._paq.push(["setCustomUrl", window.location.origin + pageUrl]);
    window._paq.push(["setDocumentTitle", title]);
    window._paq.push(["trackPageView"]);
    // liens sortants et téléchargements de la nouvelle page
    window._paq.push(["enableLinkTracking"]);
    trackedUrl = pageUrl;
}
